<?php
require __DIR__ . '/includes/app.php';
$titulo = 'Mi cuenta';
// ENTREGA 1 · HU01-HU04: validar POST, registrar con password_hash e iniciar sesión.
require __DIR__ . '/includes/header.php';
?>
<p class="etiqueta">TU CUENTA EN MERCOPAN</p><h1>Un lugar para tus pedidos</h1>
<p class="aviso">Pantallas preparadas. El registro y el inicio de sesión se implementarán en la entrega 1.</p>
<div class="dos-columnas">
<section class="panel"><h2>Iniciar sesión</h2><form><fieldset disabled><label>Correo electrónico<input type="email" autocomplete="email"></label><label>Contraseña<input type="password" autocomplete="current-password"></label><button class="boton ancho">Ingresar</button></fieldset></form></section>
<section class="panel"><h2>Crear cuenta</h2><form><fieldset disabled><label>Nombre completo<input type="text" autocomplete="name"></label><label>Correo electrónico<input type="email" autocomplete="email"></label><label>Contraseña<input type="password" autocomplete="new-password"></label><button class="boton ancho">Registrarme</button></fieldset></form></section>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
