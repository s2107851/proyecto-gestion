# MVC de Proyecto Gestión

## Qué significa

- **Modelo:** obtiene productos de MySQL y aplica las reglas del carrito (1–99 unidades, precios del servidor y cálculos en centavos).
- **Vista:** presenta los datos mediante HTML; puede usar bucles y condiciones PHP para mostrar filas y mensajes. No ejecuta SQL.
- **Controlador:** recibe la solicitud, valida método, CSRF e identificadores, llama al modelo y elige una vista o redirección.

MVC no requiere un framework ni programación orientada a objetos. Esta base usa funciones con nombres explícitos para facilitar su defensa. Cada PHP público contiene solo el arranque y la llamada a una acción: no hace falta configurar un router ni `mod_rewrite`.

## Ejemplo para explicar oralmente

«Cuando el usuario agrega un pan, el formulario envía su identificador a `carrito_accion.php`. Esa entrada llama a `carrito_actualizar()`, que verifica el token y la acción. El modelo `carrito_cambiar()` consulta si el producto está activo y actualiza su cantidad en la sesión. Después el controlador redirige al catálogo. Al abrir el carrito, otro controlador obtiene el resumen del modelo y lo pasa a la vista para mostrar cada artículo y el total».

## Dónde trabajar

| Entrega | Modelos | Controladores | Vistas |
|---|---|---|---|
| 1 · Cuentas | Crear `usuario.php` | Completar `cuenta.php` | `cuenta.php`, `perfil.php`, cabecera |
| 2 · Compra | `producto.php`, `carrito.php`; crear `pedido.php` | `catalogo.php`, `carrito.php`; confirmación | `catalogo.php`, `carrito.php`; comprobante |
| 3 · Administración | Ampliar productos, usuarios y pedidos | `admin.php`, historial en `cuenta.php` | `admin.php`, historial en `perfil.php` |

Los nombres de modelos futuros son tareas pendientes, no funcionalidades ya implementadas.

## Qué se mantiene

Catálogo, filtros y carrito funcionales; registro, sesión, perfil, confirmación, historial y administración pendientes. Mismos productos, precios, CSS y URLs. No cambia `sql/proyecto_gestion.sql`: no hay migración, nueva base ni pérdida de datos.

Los directorios internos no se abren como páginas. Apache aplica sus `.htaccess`; modelos, controladores y vistas también rechazan ejecuciones directas con una guarda PHP.

## Actualizar una instalación existente

Descargar o traer la nueva versión completa. Conservar `includes/config.local.php` si existe. Si se copia manualmente sobre una carpeta vieja, quitar los antiguos `includes/header.php` y `includes/footer.php`; ahora viven en `views/layout/`. No tocar la base ni volver a importar SQL por este cambio. Abrir la misma URL de localhost.
