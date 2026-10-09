# Auditoria de AutoCtrl PvP

Fecha: 2026-09-14
Fuente auditada: `D:\Plugin\Source`
Proyecto: `D:\Plugin\AutoCtrl`

## Resultado ejecutivo

La implementacion actual de AutoCtrl no esta integrada con el mecanismo original de entrada del cliente MU. Usa hooks globales adicionales y, en versiones anteriores, ejecutaba funciones del cliente desde callbacks de teclado y mouse.

Esto explica los dos problemas principales:

- El mensaje ingame no aparece de forma confiable.
- El cliente puede congelarse al mantener Ctrl y pasar el mouse sobre otro jugador.

La correccion debe integrarse en el flujo original del cliente, no simular Ctrl continuamente desde hooks globales.

## 1. Metodo real utilizado por el cliente para Ctrl

El cliente original usa `GetAsyncKeyState` mediante la funcion `KeysProc`:

```cpp
SHORT WINAPI KeysProc(int nCode)
{
    if(GetForegroundWindow() != *(HWND*)(MAIN_WINDOW))
    {
        return 0;
    }

    return GetAsyncKeyState(nCode);
}
```

Archivo:

`Source/Main/Main/Main.cpp`

Durante `InitDLL()` esta funcion se registra en la direccion que consulta el cliente:

```cpp
SetDword(0x007B145C,(DWORD)KeysProc);
```

No se encontro una implementacion original basada directamente en:

- `GetKeyState`
- `GetKeyboardState`
- `WM_KEYDOWN`
- `WM_KEYUP`
- `VK_CONTROL`
- `VK_LCONTROL`
- `VK_RCONTROL`

El mecanismo original es una consulta Win32 de estado mediante `GetAsyncKeyState`.

`VK_CONTROL` vale `0x11`, equivalente decimal a `17`, pero debe usarse como virtual-key de Win32. No debe confundirse con tablas de JavaScript u otros sistemas de key codes.

## 2. El loader del cliente si llama EntryProc

En `Source/Main/Main/Protect.cpp`, el cliente carga los plugins y busca la exportacion `EntryProc`:

```cpp
void (*EntryProc)() = (void(*)())GetProcAddress(module,"EntryProc");

if(EntryProc != 0)
{
    EntryProc();
}
```

Por tanto, `EntryProc` es el punto de entrada correcto para la DLL.

## 3. Por que el logger no genera logs

### 3.1 No registra la inicializacion

La version actual solo usa `OutputDebugStringA` y no crea un registro inicial del tipo:

```text
[AUTOCTRL] initialized
```

Si falla `GetForegroundWindow()` o `SetWindowsHookExW`, el logger puede no iniciar nunca.

### 3.2 El logger se crea demasiado tarde

El orden actual es:

```cpp
g_keyboardHook = SetWindowsHookExW(...);
g_mouseHook = SetWindowsHookExW(...);

g_loggerThread = CreateThread(...);
```

Si uno de los hooks falla, la funcion retorna antes de crear el logger.

### 3.3 No registra errores de archivo

El codigo abre:

```cpp
std::ofstream log(g_logPath, std::ios::app);
```

pero no registra:

- la ruta final;
- si `GetModuleFileNameA` fallo;
- si `CreateThread` fallo;
- si `SetWindowsHookExW` fallo;
- el valor de `GetLastError()`;
- si el archivo se pudo abrir.

### 3.4 Los eventos se pierden

El logger usa una sola ranura:

```cpp
InterlockedExchange(&g_eventCode, code);
```

Si llegan varios eventos antes de que el hilo escriba, el evento anterior se reemplaza.

### 3.5 El mouse solo se registra si AutoCtrl ya esta activo

El registro del clic depende de:

```cpp
if(g_enabled != 0)
```

Si el doble Ctrl no se detecto, el logger no registra los eventos del mouse y parece que no funciona.

## 4. Causa probable del freeze

No se encontro un bucle del tipo:

```cpp
while(GetAsyncKeyState(VK_CONTROL) & 0x8000)
```

El problema principal es la coexistencia de dos sistemas de input.

El cliente original ya instala sus propios hooks:

```cpp
HookKB = SetWindowsHookEx(WH_KEYBOARD,KeyboardProc,hins,GetCurrentThreadId());
HookMS = SetWindowsHookEx(WH_MOUSE,MouseProc,hins,GetCurrentThreadId());
```

AutoCtrl instala otros hooks globales:

```cpp
SetWindowsHookExW(WH_KEYBOARD_LL,KeyboardHook,g_module,0);
SetWindowsHookExW(WH_MOUSE_LL,MouseHook,g_module,0);
```

La version anterior tambien ejecutaba desde callbacks de hooks:

```cpp
pDrawMessage(message,1);
```

y:

```cpp
SendInput(...);
```

Esto es peligroso porque el cliente puede estar procesando al mismo tiempo:

- mouse over de personajes;
- seleccion del objetivo;
- verificacion de Ctrl;
- render del nombre;
- seleccion PvP;
- mensajes de ventana.

Llamar una funcion grafica del cliente desde un hook global puede bloquear el hilo de render. Usar `SendInput` desde el callback del mouse puede provocar reentrada en el sistema de entrada.

## 5. El doble Ctrl no es edge-triggered correctamente

El comportamiento deseado requiere dos transiciones distintas:

```text
Ctrl UP -> DOWN: primer toque
Ctrl UP -> DOWN: segundo toque
```

La implementacion actual revisa eventos `WM_KEYDOWN` y `WM_SYSKEYDOWN`, pero no mantiene formalmente el estado anterior ni procesa `WM_KEYUP`.

Debe existir una logica equivalente a:

```cpp
static bool previousCtrl = false;
bool currentCtrl = ...;

if(currentCtrl && !previousCtrl)
{
    // nueva pulsacion
}

previousCtrl = currentCtrl;
```

El doble toque debe medirse entre dos eventos `DOWN` distintos usando `GetTickCount` o `GetTickCount64`, sin `Sleep`.

## 6. Estado actual de la DLL

La version de diagnostico usa:

```cpp
constexpr bool InjectCtrlInDebug = false;
```

Por tanto, no ejecuta `SendInput`; solo registra el clic.

Ademas, `ShowStatus()` solo hace:

```cpp
OutputDebugStringA(...);
```

No muestra ningun mensaje ingame.

Esto explica por que:

- no aparece el mensaje ingame;
- el DLL no activa Ctrl en modo debug;
- el cambio de `g_enabled` no llega al mecanismo original `KeysProc`.

## 7. Correccion minima recomendada

No se recomienda mantener los hooks globales `WH_KEYBOARD_LL` y `WH_MOUSE_LL`.

La correccion debe:

1. Usar el mecanismo original `KeysProc`.
2. Detectar Ctrl dentro del hilo del juego.
3. Detectar solo transiciones `UP -> DOWN`.
4. Mantener un unico estado:

```cpp
bool AutoCtrlEnabled;
```

5. Reemplazar conceptualmente la consulta original:

```cpp
PhysicalCtrl
```

por:

```cpp
PhysicalCtrl || AutoCtrlEnabled
```

6. No usar `SendInput` para mantener Ctrl.
7. No llamar `pDrawMessage` desde hooks de teclado o mouse.
8. Mostrar el mensaje desde el ciclo de render del cliente, por ejemplo mediante `NoticeAdd` o un punto seguro como `CalcFPS`.

La funcion `CalcFPS()` de `Source/Main/Main/Common.cpp` es un candidato adecuado para procesar mensajes diferidos porque ya se ejecuta dentro del ciclo del cliente.

## 8. Logs de diagnostico necesarios

El primer log debe ejecutarse al inicio de `EntryProc`:

```text
[AUTOCTRL] initialized
```

Luego deben registrarse:

```text
[AUTOCTRL] log path=...
[AUTOCTRL] game window=...
[AUTOCTRL] keyboard hook result=...
[AUTOCTRL] mouse hook result=...
[AUTOCTRL] update reached
[AUTOCTRL] ctrl DOWN
[AUTOCTRL] ctrl UP
[AUTOCTRL] first press tick=...
[AUTOCTRL] second press delta=...
[AUTOCTRL] toggled ON
[AUTOCTRL] toggled OFF
```

El logger debe:

- abrir el archivo antes de instalar hooks;
- usar una ruta absoluta junto al ejecutable o junto a la DLL;
- registrar `GetLastError()` cuando falle una API;
- registrar el resultado de `CreateFile` o `ofstream`;
- evitar escribir en cada frame;
- usar throttling o registrar una sola vez por evento.

## 9. Flujo que debe verificarse

### Al presionar Ctrl

1. El cliente recibe el evento.
2. Se detecta transicion `UP -> DOWN`.
3. Se escribe `ctrl DOWN`.
4. Si no existe un toque previo reciente, se guarda el tick.

### Al soltar Ctrl

1. Se detecta `CTRL UP`.
2. Se actualiza el estado fisico.
3. No se cambia AutoCtrl.

### Al presionar Ctrl por segunda vez

1. Se detecta otra transicion `UP -> DOWN`.
2. Se calcula el delta.
3. Si esta dentro de la ventana configurada, se cambia el estado.
4. Se registra `toggled ON` o `toggled OFF`.
5. Se agenda el mensaje para el hilo de render.

### Al mover el mouse sobre un jugador

1. No se debe ejecutar `SendInput`.
2. No se debe llamar a funciones graficas desde hooks.
3. No se debe seleccionar el objetivo desde AutoCtrl.
4. El cliente debe continuar usando su target original.
5. La unica diferencia debe ser el valor efectivo de Ctrl.

## 10. Casos obligatorios

| Estado | Ctrl fisico | Resultado esperado |
|---|---|---|
| AutoCtrl OFF | suelto | comportamiento original sin Ctrl |
| AutoCtrl OFF | sostenido | comportamiento original con Ctrl |
| Doble Ctrl | suelto despues del toque | AutoCtrl ON |
| AutoCtrl ON | suelto | se comporta como Ctrl sostenido para PvP |
| AutoCtrl ON | sostenido | no duplica eventos ni congela |
| Doble Ctrl nuevamente | suelto | AutoCtrl OFF |

## 11. Conclusion

La causa no es simplemente que `17` sea incorrecto.

Los problemas concretos son:

1. El cliente usa `GetAsyncKeyState` mediante `KeysProc`.
2. AutoCtrl no modifica ese mecanismo original.
3. AutoCtrl instala hooks globales paralelos.
4. La version anterior llamaba funciones graficas y `SendInput` desde callbacks de hooks.
5. El doble Ctrl no esta implementado formalmente como transicion `UP -> DOWN`.
6. El logger no registra inicializacion ni errores y se crea demasiado tarde.
7. La version debug no muestra mensaje ingame porque `pDrawMessage` fue eliminado y no se reemplazo por un hook seguro de frame.
8. Con `InjectCtrlInDebug=false`, el DLL no activa Ctrl; solo registra eventos.

La implementacion correcta debe integrar un estado `AutoCtrlEnabled` en el punto de consulta original de Ctrl y procesar el mensaje desde el hilo de render. No debe simular Ctrl continuamente con `SendInput` ni reinventar el sistema de targeting del cliente.
