# Validación de la reorganización MVC

Comprobación realizada por el asistente de desarrollo sobre la base reorganizada; no se atribuye a los integrantes del equipo. PHP 8.3.6 con PDO MySQL y MariaDB 10.11.14, usando una base temporal aislada.

## Resultados

- Sintaxis de los 22 archivos PHP versionados: sin errores. También se comprobó la configuración local temporal de pruebas, excluida del repositorio.
- Importación del SQL dos veces: cuatro tablas, seis productos, sin duplicados.
- Catálogo: HTTP 200 y seis artículos.
- Carrito: agregar, sumar, restar y eliminar, con totales comprobados de $390, $270 y $120.
- Límite inferior de una unidad y superior de 99: correctos.
- Precio y cantidad arbitrarios enviados por el cliente: ignorados; se usan los datos del servidor.
- Dos sesiones independientes: sin mezcla de carritos.
- CSRF ausente: HTTP 403; acción o identificador inválido: HTTP 400; GET para modificar: HTTP 405.
- Perfil y administración sin sesión: redirigen a cuenta.
- Producto inactivo: retirado del carrito y aviso visible.
- Nombre con etiquetas HTML: salida escapada.
- Acceso directo a vista de catálogo, cabecera, controlador del carrito y modelo de producto: HTTP 404 mediante sus guardas PHP.
- Cuenta: HTTP 200; navegación de catálogo conserva aria-current.
- Pedidos generados: cero, porque la confirmación sigue pendiente.
- Vistas y controladores: sin SQL ni llamadas directas a la conexión PDO.

La configuración temporal y los procesos de prueba fueron retirados al terminar. El SQL y los archivos CSS, JS y JSON no requieren cambios por MVC.

## Límites

Pruebas de integración HTTP y sintaxis; no se ejecutó una nueva revisión visual de navegador ni una instalación en el XAMPP de los integrantes. Se conservó el HTML de las pantallas. Las funciones aún pendientes no se presentan como probadas. Los pasos manuales de `VALIDACION_BASE.md` siguen aplicando.
