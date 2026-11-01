<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($titulo ?? 'Catálogo') ?> · <?= e($sitio['nombre']) ?></title>
    <link rel="stylesheet" href="assets/estilos.css">
    <script src="assets/app.js" defer></script>
</head>
<body>
<header class="cabecera">
    <a class="marca" href="index.php"><span class="sello" aria-hidden="true">M</span><span>MERCOPAN<small>Panadería y repostería</small></span></a>
    <nav aria-label="Navegación principal">
        <a href="index.php" <?= basename($_SERVER['PHP_SELF']) === 'index.php' ? 'aria-current="page"' : '' ?>>Catálogo</a>
        <a href="carrito.php" <?= basename($_SERVER['PHP_SELF']) === 'carrito.php' ? 'aria-current="page"' : '' ?>>Mi carrito <span class="contador"><?= array_sum($_SESSION['carrito']) ?></span></a>
        <?php if (usuario()): ?>
            <a href="perfil.php">Mi perfil</a>
            <?php if (usuario()['rol'] === 'admin'): ?><a href="admin.php">Administrar</a><?php endif; ?>
        <?php else: ?><a href="cuenta.php">Mi cuenta</a><?php endif; ?>
    </nav>
</header>
<main>
<?php if (!empty($_SESSION['mensaje'])): ?><p class="aviso" role="status"><?= e($_SESSION['mensaje']) ?></p><?php unset($_SESSION['mensaje']); endif; ?>
