# AutoCtrl

DLL Win32 x86 para registrar un doble toque de Ctrl y mantener un estado local.

## Uso

1. Compilar `AutoCtrl.sln` en `Release|Win32`.
2. Registrar `AutoCtrl.dll` en el mismo sistema de plugins que usa el cliente.
3. Abrir el cliente y pulsar `Ctrl`, soltarlo y pulsarlo de nuevo dentro de 300 ms.
4. El log de depuracion registra `toggled ON`.
5. Repetir el doble toque para desactivar; el log registra `toggled OFF`.

El DLL consulta el estado fisico de `VK_CONTROL` desde un worker liviano y alterna un estado local al detectar un doble Ctrl. No modifica memoria del proceso anfitrion, no ejecuta funciones internas del cliente, no instala hooks, no usa `SendInput`, no intercepta el mouse ni toca el servidor.

## Validaciones de inicio

Antes de iniciar el worker, el DLL valida que el `Main.dll` cargado coincida con el hash autorizado configurado en `CustomerBinding.h`. No se realiza ninguna solicitud de licencia ni validación contra un servidor externo.

## Auditoria original

La auditoria de la source esta en [docs/AUDIT.md](docs/AUDIT.md). La matriz de pruebas esta en [docs/TEST-MATRIX.md](docs/TEST-MATRIX.md).

## Limites

El DLL no modifica `CAttack::CheckPlayerTarget`, no fuerza dano, no altera la consulta de `VK_CONTROL` del cliente y no autoriza PvP en el servidor. El doble toque solo cambia un estado local observable en el log.
