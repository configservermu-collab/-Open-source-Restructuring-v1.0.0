# Guía 7: crear packs de donación y créditos

La CMS separa la activación del módulo de donaciones de la configuración de los créditos. Antes de ofrecer un pack, prueba el flujo con una cuenta y un entorno de pago de pruebas.

## Activar donaciones

1. Entra al AdminCP.
2. Abre **Module Configuration > Donation**.
3. Activa el módulo.
4. Guarda con **Save Changes**.

El módulo correspondiente es `admincp/modules/mconfig/donation.php`.

## Crear la configuración de créditos

Para que un pack pueda acreditar saldo correctamente:

1. Abre **Credit Configurations**.
2. Pulsa **New Configuration**.
3. Escribe un título identificable.
4. Selecciona la base de datos correcta.
5. Indica la tabla de créditos.
6. Indica la columna que contiene los créditos.
7. Indica la columna del usuario.
8. Selecciona el identificador: User ID, username, email o personaje.
9. Decide si se debe comprobar que el personaje esté conectado.
10. Decide si la configuración aparece en **My Account**.
11. Guarda.

El módulo se encuentra en `admincp/modules/creditsconfigs.php`.

## Seguridad

No pruebes pagos reales durante la configuración. No publiques tokens, credenciales ni claves de proveedores. Verifica primero que el nombre de la tabla y las columnas coincidan con una base de pruebas.
