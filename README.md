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

## Archivos principales

| Archivo | Responsabilidad |
|---|---|
| `index.php` | Consulta productos y dibuja el catálogo |
| `carrito.php` | Panel final con líneas, cantidades y total |
| `carrito_accion.php` | Recibe POST y modifica la sesión |
| `cuenta.php` | Pantalla preparada para registro e ingreso |
| `perfil.php` | Punto de trabajo para perfil e historial |
| `admin.php` | Punto de trabajo protegido para administración |
| `includes/app.php` | Sesión, conexión PDO, CSRF y funciones comunes |
| `includes/header.php`, `footer.php` | Estructura compartida de las páginas |
| `assets/estilos.css`, `app.js` | Diseño adaptable y filtros de catálogo |
| `assets/sitio.json` | Datos de presentación y categorías |
| `sql/proyecto_gestion.sql` | Cuatro tablas y datos de ejemplo |

El HTML está en las páginas PHP. JSON se usa realmente para cargar la configuración de presentación; no duplica las tablas. Los botones del carrito funcionan sin JavaScript; JS solo filtra productos. Se usan ilustraciones de emoji para no depender de imágenes externas.

## Desarrollo en tres commits del equipo

Ver [plan de commits](docs/PLAN_COMMITS.md) y [historias de usuario](docs/HISTORIAS_USUARIO.md). Se preserva el historial existente: dos commits iniciales y un commit de preparación de esta base. Luego habrá **tres commits de implementación**, uno por integrante. Por tanto, el historial previsto tendrá **seis commits en total**, si no se agregan otros.

No se crean los tres commits ahora: cada integrante debe aplicar su parte, probarla, revisarla y confirmarla desde su cuenta. Los comentarios `ENTREGA` señalan los puntos de trabajo. No hay una versión terminada oculta detrás de opciones o banderas.

## Demo de la base

Abrir catálogo → buscar «torta» → agregar dos productos → abrir Mi carrito → sumar, restar y eliminar → comprobar total. Se puede mostrar Mi cuenta para explicar el trabajo pendiente. Los accesos directos a perfil y administración exigen sesión; se habilitarán con la entrega 1 y la entrega 3.

## Qué explicar en la defensa

1. El navegador envía formularios POST a PHP.
2. PHP valida la acción y conserva `id → cantidad` en la sesión.
3. PDO consulta el precio desde MySQL; el cliente no decide cuánto cuesta un artículo.
4. PHP calcula con centavos enteros y muestra los importes en pesos.
5. El SQL relaciona usuarios, pedidos, detalles y productos.

La simplicidad consiste en pocas pantallas y funciones compartidas, no en quitar validaciones. El servidor verifica el token CSRF, usa consultas preparadas y escapa el texto al mostrarlo.
