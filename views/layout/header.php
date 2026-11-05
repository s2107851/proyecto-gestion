<?php if (!defined('APP_INICIADA')) { http_response_code(404); exit; } ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($titulo ?? 'Catálogo') ?> · <?= e($sitio['nombre']) ?></title>
    <link rel="stylesheet" href="assets/estilos.css">
    <link rel="stylesheet" href="assets/cuenta.css">
    <script src="assets/app.js" defer></script>
</head>
<body>
<header class="cabecera">
    <a class="marca" href="index.php"><span class="sello" aria-hidden="true">M</span><span>MERCOPAN<small>Panadería y repostería</small></span></a>
    <nav aria-label="Navegación principal">
        <a href="index.php" <?= basename($_SERVER['PHP_SELF']) === 'index.php' ? 'aria-current="page"' : '' ?>>Catálogo</a>
        <a href="carrito.php" <?= basename($_SERVER['PHP_SELF']) === 'carrito.php' ? 'aria-current="page"' : '' ?>>Mi carrito <span class="contador"><?= $unidadesCarrito ?></span></a>
        <?php if ($usuarioActual): ?>
            <a href="perfil.php" <?= basename($_SERVER['PHP_SELF']) === 'perfil.php' ? 'aria-current="page"' : '' ?>>Mi perfil</a>
            <?php if ($usuarioActual['rol'] === 'admin'): ?><a href="admin.php" <?= basename($_SERVER['PHP_SELF']) === 'admin.php' ? 'aria-current="page"' : '' ?>>Administrar</a><?php endif; ?>
            <form action="salir.php" method="post" class="form-salir">
                <?php csrf(); ?>
                <button type="submit">Salir</button>
            </form>
        <?php else: ?>
            <a href="cuenta.php" <?= basename($_SERVER['PHP_SELF']) === 'cuenta.php' ? 'aria-current="page"' : '' ?>>Mi cuenta</a>
        <?php endif; ?>
    </nav>
</header>
<main>
<?php if ($aviso): ?><p class="aviso" role="status"><?= e($aviso) ?></p><?php endif; ?>
