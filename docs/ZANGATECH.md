ZangaTech y Proyecto Gestión

Presentación de la empresa y su único producto · Versión 2 · 31 de octubre de 2026

Somos ZangaTech, un equipo de tres desarrolladores integrado por Santiago Beasley, André de Mora y Santiago Erramouspe. Nuestro único producto es Proyecto Gestión, una aplicación web de ventas y clientes para Mercopan. Buscamos que sea práctica para el usuario y que su funcionamiento pueda explicarse con claridad.

Nuestro equipo

Santiago Beasley es desarrollador y Scrum Master. Además de desarrollar, coordina las tareas, ayuda a resolver dificultades y acompaña el seguimiento de las entregas.

Proponemos que André de Mora se concentre en la interfaz y la experiencia de compra, y que Santiago Erramouspe se concentre en administración e integración de la base de datos. Son responsabilidades de trabajo sugeridas; los tres participan en desarrollo, revisión y pruebas. No se atribuyen cargos empresariales adicionales.

Misión propuesta

Desarrollar una herramienta sencilla que permita a Mercopan organizar sus pedidos y consultar la información de sus clientes, ofreciendo a los compradores un catálogo claro y un carrito fácil de usar.

Visión propuesta

Consolidar Proyecto Gestión como una solución confiable para la gestión comercial de Mercopan, mejorándola según la experiencia de sus usuarios sin incorporar complejidad innecesaria.

Organización del trabajo

Dividiremos la implementación en tres entregas: cuentas y perfil; catálogo, carrito y confirmación; historial y administración. Cada integrante revisará y probará su parte antes de registrarla en GitHub. Las historias de usuario permiten relacionar cada cambio con una necesidad concreta y comprobar cuándo está terminado.

El equipo cuenta con una base de catálogo y carrito para comenzar. El repositorio distingue los commits de preparación de los tres commits de implementación previstos. No se considera realizado el trabajo pendiente ni se cambia la autoría de los commits de otras personas.

Nuestro único producto

Proyecto Gestión permite elegir panes, tortas y postres vendidos por unidad. El comprador consulta el catálogo y prepara su carrito. En esa misma pantalla ve cada artículo, suma o resta unidades, elimina lo que no quiere y revisa el total. Al finalizar, confirmará un pedido para retirar y pagar en el local de Mercopan.

Necesidad que atiende

El producto reúne catálogo, clientes y pedidos en una misma base de datos. Su objetivo es facilitar la consulta de compras y evitar errores al registrar cantidades e importes. Se trata de objetivos del producto; no se atribuyen mejoras comerciales ya medidas.

Funciones para Mercopan

El administrador utilizará el mismo acceso que los clientes. Su rol habilitará un enlace de administración para gestionar productos, consultar clientes y revisar pedidos. Podrá actualizar estados y registrar el pago al retirar. El historial conservará los nombres y precios que tenía cada artículo al comprar, aunque luego cambie el catálogo.

Desarrollo sencillo

Usamos HTML, CSS, JavaScript, JSON y PHP, con MySQL o MariaDB y un archivo SQL de instalación. La base se llama proyecto_gestion y contiene cuatro tablas. Evitamos frameworks y herramientas de compilación: el proyecto se ejecuta localmente con XAMPP.

La sencillez se refleja en pocas pantallas, funciones compartidas y operaciones fáciles de seguir. Las validaciones del servidor y los permisos siguen siendo necesarios. El carrito se conserva en la sesión y los precios se obtienen de la base; JavaScript solo ayuda a filtrar el catálogo.

Alcance y estado actual

La modalidad es venta por unidad, retiro y pago en el local. No se incluyen envíos ni cobros en línea. Para esta versión se propone activar o desactivar productos sin llevar stock numérico. Los productos iniciales son datos de demostración.

La base permite probar catálogo y carrito. Cuentas, confirmación, historial y administración se completarán en las tres entregas. La versión final se validará mediante una compra completa y controles de acceso. La misión, visión y responsabilidades complementarias quedan como propuestas para revisión del equipo.