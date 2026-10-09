# Auditoria avanzada EX101KOR y build actual

Fecha: 2026-09-14

## Alcance

Cliente analizado:

```text
D:\Trabajo\CRFMU\CrfMU Classic\main.exe
```

Plugin:

```text
D:\Trabajo\CRFMU\CrfMU Classic\AutoCtrl.dll
```

Source de referencia:

```text
D:\Plugin\Wizard Team Source\SOURCE\Source\EX101KOR
```

No se modifico `AutoCtrl.cpp` ni se aplicaron nuevos parches durante esta auditoria.

## Hallazgo principal

La source EX101KOR no utiliza `0x007B145C`.

En `Main.cpp` instala el callback mediante:

```cpp
SetDword(0x006A74FC,(DWORD)KeysProc);
```

Por lo tanto:

```text
0x007B145C = direccion heredada de otra build
0x006A74FC = slot KeysProc documentado por EX101KOR
```

Esto explica que `0x007B145C` aparezca como `NULL` en el cliente actual.

## Mecanismo de teclado EX101KOR

| Archivo | Funcion | Linea aproximada | Funcion |
|---|---|---:|---|
| `Main.cpp` | `KeyboardProc` | 54 | Hook de teclado del hilo principal |
| `Main.cpp` | `KeyboardProc` | 62 | Consulta `GetKeyState(VK_CONTROL)` |
| `Main.cpp` | `MouseProc` | 101 | Camara, rueda y boton central; no PvP |
| `Main.cpp` | `KeysProc` | 132 | Valida ventana y llama `GetAsyncKeyState(nCode)` |
| `Main.cpp` | `EntryProc` | 169 | Configura direcciones y hooks |
| `Main.cpp` | `EntryProc` | 206 | Instala `KeysProc` en `0x006A74FC` |
| `Main.cpp` | `EntryProc` | 362 | Instala `WH_KEYBOARD` y `WH_MOUSE` |
| `ChangeWare.cpp` | funciones de inventario | 61, 109 | Consulta `GetAsyncKeyState(VK_LBUTTON)` |
| `ClearInv.cpp` | limpieza de inventario | 36 | Consulta `GetKeyState(VK_ESCAPE)` |
| `JewelBank.cpp` | banco de joyas | 65 | Consulta `GetKeyState(VK_ESCAPE)` |

La implementacion de `KeysProc` en EX101KOR es:

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

## Ctrl PvP de EX101KOR

El archivo relevante es:

```text
D:\Plugin\Wizard Team Source\SOURCE\Source\EX101KOR\Main\Main\AutoCtrl.cpp
```

La source no contiene una funcion C++ llamada `CheckPlayerTarget` ni una rama legible como:

```cpp
if(targetIsPlayer && ctrl)
```

El comportamiento PvP esta en codigo original del cliente. `AutoCtrl.cpp` solo cambia dos operandos inmediatos:

```cpp
void CtrlOn()
{
    SetByte(0x0054CFBA + 2, 0);
    SetByte(0x0054D108 + 2, 0);
}

void CtrlOff()
{
    SetByte(0x0054CFBA + 2, 0x80);
    SetByte(0x0054D108 + 2, 0x80);
}
```

Direcciones efectivamente modificadas:

```text
0x0054CFBC
0x0054D10A
```

Estas son candidatas de EX101KOR, pero todavia no estan validadas contra el `main.exe` protegido actual.

## Activacion del AutoCtrl original

En `KeyboardProc` de EX101KOR:

```cpp
if (wParam == VK_SHIFT)
{
    if (GetKeyState(VK_CONTROL) & 0x8000)
    {
        CtrlToggle();
    }
}
```

La source implementa:

```text
Ctrl + Shift
```

No implementa doble Ctrl. El doble Ctrl solicitado actualmente seria una funcionalidad nueva.

## Update y mensajes EX101KOR

En `Common.cpp`:

```cpp
SetCompleteHook(0xE8,0x0062814C,&CalcFPS);
```

Direccion documentada para el call site de `CalcFPS`:

```text
0x0062814C
```

La direccion usada anteriormente por AutoCtrl:

```text
0x006CFCFC
```

pertenece a otra build y queda descartada para EX101KOR.

El mensaje de EX101KOR esta definido en `Offset.h`:

```cpp
#define pDrawMessage ((int(__cdecl*)(char*,int))0x00548AB0)
```

La direccion anterior `0x005A01C0` tambien pertenece a otra build y queda descartada.

La cola de avisos existe en `Notice.cpp`, mediante `NoticeAdd`, pero no esta validada contra el binario protegido actual.

## Comparacion con el binario actual

### `main.exe`

```text
Tamano:       1,564,672 bytes
SHA-256:      c2bafcbb3d2a5e97a3c7187cd8fc77d4d116c7b7b3586753dd93bba59f167243
Arquitectura: x86
ImageBase:    0x00400000
EntryPoint:   0x007F55698
```

Imports relevantes encontrados:

```text
USER32.GetAsyncKeyState
USER32.SetWindowsHookExA
KERNEL32.LoadLibraryA
KERNEL32.GetProcAddress
KERNEL32.VirtualProtect
```

No aparecen:

```text
USER32.GetKeyState
USER32.GetKeyboardState
USER32.SetWindowsHookExW
```

El ejecutable contiene secciones `.vmp0` y `.vmp1`, con codigo virtualizado/protegido. Las referencias estaticas a `GetAsyncKeyState` y los patrones `push 0x11` no pueden atribuirse de forma fiable a una funcion concreta sin analizar el codigo desempaquetado en memoria.

### `Main.dll`

```text
Tamano:       795,648 bytes
Arquitectura: x86
ImageBase:    0x10000000
SHA-256:      6a4621da5c924215462a9948199a1e37e78bdb3d3f87c0a64381f5d3c4f19a2e
```

El analisis estatico no encontro referencias fiables a:

```text
KeysProc
KeyboardProc
MouseProc
VK_CONTROL
EntryProc
GetAsyncKeyState
SetWindowsHookExA
```

Por lo tanto tampoco puede asumirse que `Main.dll` sea una copia directa de EX101KOR.

## Comparacion de direcciones

| Direccion | EX101KOR | Estado en la build actual |
|---|---|---|
| `0x007B145C` | No existe en EX101KOR | Direccion heredada de otra build |
| `0x006A74FC` | Slot `KeysProc` de EX101KOR | No validada en memoria real |
| `0x0054CFBC` | Operando PvP ON/OFF | No validado en binario actual |
| `0x0054D10A` | Operando PvP ON/OFF | No validado en binario actual |
| `0x0062814C` | Hook de `CalcFPS` EX101KOR | No validado en binario actual |
| `0x00548AB0` | `pDrawMessage` EX101KOR | No validado en binario actual |
| `0x006CFCFC` | Direccion de otra build | Descartada |

## Conclusiones confirmadas

```text
0x007B145C belongs to another client build
0x006CFCFC belongs to another client build
```

La source EX101KOR documenta estos puntos:

```text
KeysProc:      0x006A74FC
Ctrl PvP site: 0x0054CFBC y 0x0054D10A
CalcFPS hook:  0x0062814C
pDrawMessage:  0x00548AB0
```

Pero el `main.exe` actual esta protegido con VMProtect y aun no se puede afirmar que esas direcciones pertenezcan al binario ejecutado.

## Estado de implementacion

Todavia no existe una firma valida ni un punto de parche confirmado para el `main.exe` actual.

No se deben modificar aun estas direcciones:

```text
0x006A74FC
0x0054CFBC
0x0054D10A
0x0062814C
0x00548AB0
```

La siguiente etapa necesaria es obtener una imagen desempaquetada del modulo en memoria o un dump controlado con debugger. Solo entonces se pueden confirmar:

```text
bytes originales
RVA real
funcion PvP
firma unica
update seguro
funcion de mensaje
```

Hasta esa validacion, cualquier parche seria otra suposicion de build.

## Validacion runtime controlada (2026-09-14)

Se cargo el inspector de solo lectura desde `EntryProc` en el cliente real. No se
llamo `VirtualProtect`, no se escribio memoria de `main.exe` y no se instalaron
hooks de teclado, mouse o frame.

| Direccion | Region | Proteccion | Bytes relevantes | Resultado |
|---|---|---|---|---|
| `0x006A74FC` | `MEM_IMAGE`, `main.exe` | `PAGE_READONLY` | `20 54 52 6A` -> `0x6A525420` | VALIDATED: slot de datos que apunta a codigo RX de `Main.dll` |
| `0x0054CFBC` | `MEM_IMAGE`, `main.exe` | `PAGE_EXECUTE_READ` | `80 F9 80 75 0E` | VALIDATED: inmediato de `cmp cl, 80h` |
| `0x0054D10A` | `MEM_IMAGE`, `main.exe` | `PAGE_EXECUTE_READ` | `80 F9 80 75 B7` | VALIDATED: inmediato de `cmp cl, 80h` |
| `0x0062814C` | `MEM_IMAGE`, `main.exe` | `PAGE_EXECUTE_READ` | `E8 5F AB ED 69` | PARTIAL MATCH: `CALL rel32` a `0x6A502CB0` en `Main.dll` |
| `0x00548AB0` | `MEM_IMAGE`, `main.exe` | `PAGE_EXECUTE_READ` | `55 8B EC 81 EC 08 02 00 00` | PARTIAL MATCH: entrada de funcion x86 valida; nombre/ABI no ejecutados |

### Sitios Ctrl PvP

Los dos sitios contienen la misma secuencia estructural. El primero se
desensambla asi:

```text
0054CFAE  push 11h
0054CFB0  call dword ptr [006A74FCh]
0054CFB6  xor ecx, ecx
0054CFB8  mov cl, ah
0054CFBA  cmp cl, 80h
0054CFBD  jne 0054CFCD
```

El segundo repite el mismo patron en `0x0054D0FC` y usa
`cmp cl, 80h` en `0x0054D108`. Por tanto los bytes modificados por
EX101KOR (`0x0054CFBC` y `0x0054D10A`) son el inmediato `80h` de una
comparacion sobre `AH`, el byte alto del resultado de la llamada indirecta
con `VK_CONTROL` (`0x11`). Ambos estaban en `0x80` al entrar, equivalente al
estado OFF documentado por EX101KOR.

Esto demuestra la correspondencia estructural de los dos operandos PvP con
EX101KOR. Aun no autoriza modificar los bytes: falta una captura final tras
login/juego para descartar cambios de VMProtect y observar transiciones del
slot `KeysProc`.

### Estado de la observacion

El log recibido contiene solo la captura `ENTRYPROC`; termina despues de
`monitor started` y antes de `monitor finished`. No hay evidencia todavia de
cambios, o ausencia de cambios, entre `ENTRYPROC` e `INGAME`.

La siguiente compilacion del inspector deja siempre una segunda captura
`stage=INGAME final snapshot` tras 60 segundos y escribe dumps separados:

```text
runtime_ENTRYPROC_006A74FC.bin
runtime_ENTRYPROC_0054CFBC.bin
runtime_ENTRYPROC_0054D10A.bin
runtime_ENTRYPROC_0062814C.bin
runtime_ENTRYPROC_00548AB0.bin
runtime_INGAME_006A74FC.bin
runtime_INGAME_0054CFBC.bin
runtime_INGAME_0054D10A.bin
runtime_INGAME_0062814C.bin
runtime_INGAME_00548AB0.bin
```

Tambien registra `KeysProc slot changed: anterior -> nuevo` si el puntero
cambia durante el arranque. No se debe implementar AutoCtrl hasta revisar esa
segunda captura.
