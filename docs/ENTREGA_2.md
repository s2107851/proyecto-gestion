# Entrega 2 · Compra y confirmación (HU05–HU08)

El catálogo y los controles de cantidad vienen de la base. Esta entrega conecta el resumen del carrito con la creación del pedido. Al confirmar, el sistema compara el contenido mostrado con los productos activos y precios actuales. Guarda el pedido y sus líneas en una transacción. El historial del cliente y el panel de administración leen ese mismo pedido.

## Pruebas para ejecutar en XAMPP antes del commit

Usar una base de prueba con el esquema del repositorio y una cuenta cliente.

| HU | Acción | Qué comprobar |
|---|---|---|
| 05 | Buscar un pan, combinar búsqueda con categoría y luego buscar un nombre inexistente. | Aparecen solo las coincidencias activas y un aviso cuando la lista queda vacía. |
| 06 | Agregar dos artículos, sumar, restar y eliminar. Abrir otra sesión privada. | El carrito respeta los límites de 1 a 99 y cada sesión tiene su propia selección. |
| 07 | Elegir dos unidades de un producto y una de otro. | Cada subtotal equivale al precio por la cantidad. El total suma las dos líneas y se actualiza al quitar una. |
| 08 | Ingresar como cliente y confirmar. Revisar perfil y administración. | Coinciden el número y el total. El pedido queda pendiente, sin pagar, con sus líneas y el carrito vacío. |
| 08 | Hacer doble clic o reenviar el mismo POST. | Solo aparece un pedido para ese token. |
| 08 | Con el resumen abierto, modificar el precio o desactivar un producto en otra sesión. Confirmar desde la primera. | Se pide revisar el carrito antes de guardar. |
| 08 | Cambiar una cantidad en otra pestaña y confirmar desde la anterior. | El formulario anterior no crea ningún pedido. |
| 08 | Abrir el carrito vacío o como invitado. Probar acceso GET a confirmar.php. | Solo un cliente con artículos puede confirmar. La entrada GET devuelve 405. |
| 08 | En una copia de prueba, provocar un error de inserción de detalle y confirmar. | No queda cabecera ni detalle parcial. Al corregir el fallo se puede reintentar con la selección conservada. |

Anotar los resultados observados antes de marcar las historias como hechas.
