# Validación de la base

Fecha: 31 de octubre de 2026. Responsable de la comprobación automatizada: asistente de desarrollo. No se atribuyen estas pruebas a los integrantes del equipo.

## Pruebas ejecutadas

Entorno temporal: PHP 8.3.6 con PDO MySQL y MariaDB 10.11.14. Se levantaron servidor y base aislados con datos ficticios. Las peticiones HTTP utilizaron sesiones independientes.

| Comprobación | Resultado observado |
|---|---|
| Sintaxis de los diez archivos PHP | Sin errores |
| Importar SQL dos veces | Cuatro tablas; seis productos, sin duplicados |
| Catálogo | Respuesta HTTP 200 y seis productos |
| Agregar pan de campo y pan de molde; sumar un pan de campo | Total $390,00 |
| Restar un pan de campo | Total $270,00 |
| Eliminar pan de molde | Total $120,00 |
| Restar desde una unidad | Conserva una unidad |
| Sumar más de 99 veces | Se limita a 99 unidades; total $11.880,00 para el pan de campo |
| Enviar precio 0,01 y cantidad negativa desde el navegador | El servidor ignora esos valores y usa precio de MySQL y sus propias cantidades |
| Consultar con una segunda sesión | Carrito vacío, sin datos de la primera |
| POST sin token CSRF | HTTP 403 |
| Acción desconocida o identificador malformado | HTTP 400 |
| GET a la ruta que modifica el carrito | HTTP 405 |
| Acceder a perfil y administración sin sesión | Redirección a la pantalla de cuenta |
| Desactivar un producto presente en el carrito | Se elimina del detalle y se informa al usuario |
| Nombre de producto con etiquetas HTML | Se muestra escapado, sin ejecutar el contenido |
| Pedidos tras probar el carrito | Cero pedidos, como corresponde a esta versión incompleta |

## Límites de esta validación

La prueba visual automatizada no pudo ejecutarse porque el entorno no disponía del navegador requerido. No se certifican aquí el aspecto en distintos navegadores ni los filtros de JavaScript mediante interacción real. La integración HTTP del catálogo y el carrito sí fue probada. La instalación en el XAMPP del equipo aún debe verificarse.

Las funciones de cuentas, confirmación de pedidos, historial y administración todavía no están implementadas. Sus pruebas de aceptación figuran como previstas en HISTORIAS_USUARIO.md, no como aprobadas.

## Comprobación manual antes de la defensa

1. Importar el SQL en una base nueva de XAMPP y abrir el proyecto mediante localhost.
2. Ver seis productos y buscar «torta»: deben quedar dos.
3. Seleccionar Panes manteniendo «torta»: debe mostrarse el mensaje sin resultados.
4. Limpiar búsqueda y filtro, agregar productos y comprobar sumar, restar y eliminar.
5. Revisar nombre, precio por unidad, subtotal, total, retiro y pago en local.
6. Repetir con una ventana angosta y verificar que botones y textos sean legibles.
7. Comprobar que la versión base no permite registrar una cuenta ni confirmar un pedido.
8. Tras cada entrega, ejecutar las pruebas de sus historias y registrar responsable, fecha, evidencia y hash real.

La evidencia corresponde al código del commit que incorpora este archivo. Los tres commits de implementación del equipo siguen pendientes.
