# Entrega 3 — HU09 a HU12

Responsable asignado en el plan: Santiago Erramouspe. Código incorporado desde la cuenta s2107851.

- HU09: historial propio obtenido por ID de sesión; fecha, artículos, cantidad, precio histórico, total y estado.
- HU10: alta/edición, validación y activación/desactivación de productos. No se eliminan productos con pedidos.
- HU11: listado/búsqueda de clientes y consulta de pedidos sin exponer contraseñas.
- HU12: filtros, detalle, cambio de estado controlado (pendiente → listo → retirado; cancelar desde pendiente/listo); pago solamente al retirar; total cobrado solo de retirados pagados.

La estructura sigue separando modelos, controladores y vistas; la base SQL es la misma, sin migraciones.

## Dependencias y verificaciones

El código de esta entrega se puede inspeccionar en el repositorio, pero no se da por aprobado sin pruebas reales. Las cuentas (HU01–04) son tarea de la entrega 1 y la confirmación (HU08) de la entrega 2. Después de integrarlas, probar en XAMPP: un cliente no accede a admin; dos clientes no mezclan historial; crear/editar/desactivar; precios históricos inmutables; búsqueda; filtros; transiciones válidas e inválidas; CSRF inválido; total cobrado sin cancelados.

Al completar las pruebas, anotar fecha, entorno, esperado/obtenido, responsable y hash de los commits. No se inventa evidencia de pruebas ni se atribuyen commits a terceros.
