# Proyecto Gestión · Mercopan

Único producto de ZangaTech. Aplicación escolar simple en **HTML, CSS, JavaScript, JSON, PHP y MySQL/MariaDB**, sin frameworks, Composer ni npm.

## Estado real de esta entrega

**Base funcional a medio desarrollar.** El catálogo y el carrito funcionan. Registro, inicio/cierre de sesión, perfil, confirmación, historial y administración quedan para los tres commits de implementación del equipo. Los formularios pendientes están deshabilitados; no simulan una compra ni un ingreso exitosos.

- Venta **por unidad** (cantidades enteras de 1 a 99).
- **Retiro y pago en el local**, sin envíos ni cobros en línea.
- Catálogo con productos y precios **ficticios de demostración**.
- Carrito en sesión PHP: sumar, restar, eliminar y ver precio unitario, subtotal y total.
- No se controla stock numérico: un producto puede estar activo o inactivo.
- No hay cuentas ni contraseñas precargadas.

## Instalar en XAMPP

1. Copiar la carpeta a `C:\xampp\htdocs\proyecto-gestion`.
2. Iniciar **Apache** y **MySQL**.
3. Abrir `http://localhost/phpmyadmin` e importar `sql/proyecto_gestion.sql`. El script crea la base **proyecto_gestion**; no modifica la base del proyecto anterior.
4. Abrir `http://localhost/proyecto-gestion/`. No abrir los PHP con doble clic ni con Live Server.
5. Si MySQL usa otra contraseña o puerto, crear `includes/config.local.php` tomando este ejemplo y reemplazando los valores necesarios:

```php
<?php
return ['host' => '127.0.0.1', 'port' => '3306', 'database' => 'proyecto_gestion',
        'user' => 'root', 'password' => ''];
```

Requisito: PHP 8.1 o superior con `pdo_mysql`, MySQL 8 o MariaDB 10.4 o superior. La cuenta root sin clave del ejemplo se usa únicamente en una instalación local de XAMPP. La configuración privada está excluida de Git.

El SQL se importa antes de probar el catálogo. Reimportarlo sobre este mismo esquema no borra pedidos ni duplica los seis productos. `CREATE TABLE IF NOT EXISTS` no actualiza esquemas antiguos distintos: esta es una base nueva.

## Estructura Modelo–Vista–Controlador (MVC)

MVC sencillo con funciones PHP, sin framework, clases obligatorias ni reglas de reescritura de Apache.

| Carpeta / archivo | Responsabilidad |
|---|---|
| `models/producto.php` | Consultas de productos mediante PDO |
| `models/carrito.php` | Cantidades, productos disponibles, subtotales y total |
| `controllers/catalogo.php` | Solicita productos al modelo y selecciona la vista |
| `controllers/carrito.php` | Valida POST/CSRF, llama al modelo y muestra/redirige |
| `controllers/cuenta.php`, `admin.php` | Acceso protegido y puntos pendientes de las entregas |
| `views/*.php` | HTML y presentación de los datos recibidos, sin SQL |
| `views/layout/header.php`, `footer.php` | Cabecera y pie compartidos |
| `includes/app.php` | Arranque, sesión, seguridad y renderizado de vistas |
| `includes/conexion.php`, `config.php` | Conexión y configuración de MySQL |
| PHP de la raíz | Entradas públicas pequeñas que delegan en controladores |
| `assets/` | CSS, JavaScript y JSON de presentación |
| `sql/proyecto_gestion.sql` | Las mismas cuatro tablas y datos de ejemplo |

Ejemplo: `carrito_accion.php` → `carrito_actualizar()` → `carrito_cambiar()` → redirección a `carrito.php` → `carrito_mostrar()` → `views/carrito.php`.

Las vistas no consultan la base de datos; los controladores no contienen SQL ni dibujan HTML. Los modelos no procesan formularios ni redirigen. El carrito conserva su estado en sesión; la persistencia de productos está en MySQL. Los archivos internos tienen protección de acceso directo.

El HTML está en las vistas PHP. JSON configura la presentación y no duplica las tablas. Los botones del carrito funcionan sin JavaScript; JS filtra productos. Se mantienen las ilustraciones de emoji.

Ver [guía MVC para la defensa](docs/ARQUITECTURA_MVC.md).

## Desarrollo en tres commits del equipo

Ver [plan de commits](docs/PLAN_COMMITS.md) y [historias de usuario](docs/HISTORIAS_USUARIO.md). Se preserva el historial existente: dos commits iniciales, un commit de preparación de la base y un commit de reorganización MVC. Luego habrá **tres commits de implementación**, uno por integrante. Por tanto, el historial previsto tendrá **siete commits en total**, si no se agregan otros.

No se crean los tres commits ahora: cada integrante debe aplicar su parte, probarla, revisarla y confirmarla desde su cuenta. Los comentarios `ENTREGA` señalan los puntos de trabajo. No hay una versión terminada oculta detrás de opciones o banderas.

## Demo de la base

Abrir catálogo → buscar «torta» → agregar dos productos → abrir Mi carrito → sumar, restar y eliminar → comprobar total. Se puede mostrar Mi cuenta para explicar el trabajo pendiente. Los accesos directos a perfil y administración exigen sesión; se habilitarán con la entrega 1 y la entrega 3.

## Qué explicar en la defensa

1. El navegador envía formularios POST a PHP.
2. El controlador valida la acción y pide al modelo cambiar `id → cantidad` en la sesión.
3. PDO consulta el precio desde MySQL; el cliente no decide cuánto cuesta un artículo.
4. El modelo calcula en centavos; la vista presenta los importes en pesos.
5. El SQL relaciona usuarios, pedidos, detalles y productos.

La simplicidad consiste en pocas pantallas y funciones compartidas, no en quitar validaciones. El servidor verifica el token CSRF, usa consultas preparadas y escapa el texto al mostrarlo.
