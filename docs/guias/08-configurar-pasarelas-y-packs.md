# Guía 8: configurar la pasarela y revisar los packs

Esta guía complementa la configuración de créditos. Un pack solo debe habilitarse cuando la pasarela, la acreditación y el registro de la transacción hayan sido probados en un entorno aislado.

## Pasos generales

1. Activa **Donation** en la configuración de módulos.
2. Configura la forma de acreditar créditos en **Credit Configurations**.
3. Configura la pasarela disponible, por ejemplo Mercado Pago o PayPal.
4. Define los importes y la cantidad de créditos en el panel que corresponda.
5. Revisa las URLs de retorno y notificación según la documentación del proveedor.
6. Realiza una prueba controlada.
7. Comprueba la transacción y el saldo acreditado.

## Antes de publicar un pack

- El importe debe coincidir con el proveedor.
- La moneda debe estar clara.
- El nombre del pack debe explicar qué recibe el usuario.
- No se deben acreditar créditos más de una vez por la misma operación.
- La cuenta receptora debe ser la cuenta de pruebas autorizada.

La integración de pagos puede modificar saldos y escribir transacciones. Por eso no debe validarse ejecutando callbacks desde la consola ni contra una base real sin autorización.
