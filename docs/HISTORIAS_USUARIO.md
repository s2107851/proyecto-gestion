# Historias de usuario

Reglas y estado: ver ALCANCE.md, PLAN_COMMITS.md y VALIDACION_BASE.md.

## HU01 · Registro de clientes

Como visitante quiero crear una cuenta para identificar mis pedidos.

**Criterios:** Nombre, correo único y contraseña de al menos ocho caracteres. Validación en PHP, hash de contraseña y rol cliente fijo.

**Validación prevista:** Registrar cuenta válida; rechazar correo duplicado, datos incompletos y rol admin enviado desde el navegador.

**Entrega:** 1. **Estado:** Pendiente.

## HU02 · Inicio de sesión

Como usuario quiero ingresar a mi cuenta para acceder a mis datos.

**Criterios:** Verificar correo y contraseña; regenerar sesión; conservar carrito invitado; guardar id, nombre, correo y rol.

**Validación prevista:** Probar credenciales válidas e inválidas, comprobar sesión y que el cliente no vea administración.

**Entrega:** 1. **Estado:** Pendiente.

## HU03 · Cierre de sesión

Como usuario quiero salir de mi cuenta para proteger mis datos.

**Criterios:** Cerrar por POST con CSRF; destruir sesión y carrito; impedir acceso posterior al perfil.

**Validación prevista:** Cerrar, volver al perfil e ingresar con otra cuenta: no deben quedar datos ni carrito del usuario anterior.

**Entrega:** 1. **Estado:** Pendiente.

## HU04 · Perfil de usuario

Como cliente quiero consultar mis datos registrados.

**Criterios:** Mostrar nombre y correo del usuario autenticado; no mostrar hashes ni datos ajenos. Perfil solo de consulta.

**Validación prevista:** Ingresar con dos cuentas y comprobar que cada una vea exclusivamente sus datos.

**Entrega:** 1. **Estado:** Pendiente.

## HU05 · Catálogo de productos

Como visitante quiero consultar productos y precios para elegir.

**Criterios:** Listar productos activos con nombre, descripción, categoría y precio por unidad; permitir búsqueda y filtro. Mostrar alternativa visual sin recursos externos.

**Validación prevista:** Ver los seis ejemplos, buscar por nombre, filtrar categoría, combinar filtros y comprobar el estado sin resultados.

**Entrega:** 2. **Estado:** Base funcional; revalidar en entrega 2.

## HU06 · Acciones del carrito

Como comprador quiero agregar, sumar, restar y eliminar artículos.

**Criterios:** Carrito en sesión PHP; cantidades enteras de 1 a 99; al restar desde 1 indicar que se use Eliminar; precios consultados en MySQL.

**Validación prevista:** Agregar dos productos; sumar, restar y eliminar; intentar acción inválida y petición sin CSRF; confirmar aislamiento entre sesiones.

**Entrega:** 2. **Estado:** Base funcional; revalidar en entrega 2.

## HU07 · Panel final de compra

Como comprador quiero ver cada artículo y el total antes de confirmar.

**Criterios:** Usar la misma pantalla del carrito. Mostrar precio unitario, cantidad, subtotal y total, con controles de edición. Indicar retiro y pago en el local.

**Validación prevista:** Comprobar que al cambiar cantidades o eliminar artículos se actualicen subtotales y total; verificar carrito vacío y selección conservada al navegar.

**Entrega:** 2. **Estado:** Base funcional; confirmación aún deshabilitada.

## HU08 · Confirmación del pedido

Como cliente quiero confirmar mi selección para que Mercopan reciba el pedido.

**Criterios:** Exigir sesión y carrito válido; releer productos y precios; si cambian respecto del resumen, pedir revisión. Guardar pedido y detalles en una transacción; usar token único; pedido pendiente y sin pagar.

**Validación prevista:** Confirmar y verificar base; repetir envío sin duplicar; simular cambio de precio, producto inactivo y fallo de guardado sin registros parciales. Vaciar carrito solo tras éxito.

**Entrega:** 2. **Estado:** Pendiente.

## HU09 · Historial personal

Como cliente quiero consultar mis pedidos desde el perfil.

**Criterios:** Mostrar solo pedidos del id autenticado, con detalle, fecha, total, estado y pago. No permitir ver pedidos ajenos.

**Validación prevista:** Crear pedidos de dos cuentas e intentar solicitar directamente un pedido ajeno; debe denegarse.

**Entrega:** 3. **Estado:** Pendiente.

## HU10 · Administración de productos

Como administrador quiero mantener el catálogo actualizado.

**Criterios:** Crear, editar y activar/desactivar productos por unidad. Validar campos, categoría y precio positivo. No borrar productos ni alterar precios históricos de pedidos.

**Validación prevista:** Cambiar precio y desactivar un producto comprado; verificar catálogo y conservación del detalle original. Rechazar cambios por cliente.

**Entrega:** 3. **Estado:** Pendiente.

## HU11 · Consulta de clientes

Como administrador quiero consultar clientes y sus pedidos.

**Criterios:** Listar y buscar clientes por nombre o correo; ver sus pedidos. Nunca mostrar contraseñas ni hashes.

**Validación prevista:** Buscar con y sin coincidencias, revisar pedidos y probar la ruta con usuario no administrador.

**Entrega:** 3. **Estado:** Pendiente.

## HU12 · Administración de pedidos y ventas

Como administrador quiero gestionar pedidos y registrar el cobro al retirar.

**Criterios:** Listar y filtrar por fecha/estado. Estados pendiente, listo, retirado y cancelado. Avanzar pendiente a listo y listo a retirado; cancelar pendiente o listo. Retirado implica pagado en el local; cancelado no cuenta como venta cobrada.

**Validación prevista:** Verificar filtros y detalle; probar cada transición permitida y rechazar regresiones o cambios sobre terminales. Comprobar total cobrado solo de retirados pagados.

**Entrega:** 3. **Estado:** Pendiente.
