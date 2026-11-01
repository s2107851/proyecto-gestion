# Alcance confirmado

Proyecto Gestión es el único producto de ZangaTech y gestiona ventas y clientes para Mercopan.

Venta por unidad, retiro y pago en el local. El panel final es el propio carrito: cada artículo, su precio unitario, cantidad, botones de sumar/restar/eliminar, subtotal y total. Confirmar generará un pedido pendiente y no un pago.

Dos roles: cliente y administrador. Un mismo inicio de sesión; el administrador verá el enlace Administrar. El alta pública siempre creará clientes. El primer administrador se asignará localmente a una cuenta existente durante la entrega 1, sin registro público de administradores.

Se mantienen doce historias para conservar su numeración. HU06 describe las acciones del carrito; HU07, el resumen de esa misma pantalla. No son dos carritos ni dos pantallas distintas.

Se incluye consulta de historial personal como parte del perfil, con implementación en la entrega 3. No se implementan edición de perfil, recuperación de contraseña, stock numérico, pagos en línea, envíos, producción, OCR ni facturación fiscal. Productos y precios iniciales son ejemplos.

Código procedural PHP, PDO, sesiones, HTML, CSS y JavaScript sencillo. JSON para datos de presentación. Cuatro tablas: usuarios, productos, pedidos y detalle_pedido. Categoría como campo de productos, sin tabla adicional.

Estados propuestos del pedido: pendiente → listo → retirado; se podrá cancelar desde pendiente o listo. Un pedido retirado se marca pagado en el local; cancelados no cuentan como ventas cobradas. El panel distinguirá pedidos recibidos de ventas efectivamente retiradas y pagadas.
