<?php if (!defined('APP_INICIADA')) { http_response_code(404); exit; } ?>
<p class="etiqueta">TU CUENTA EN MERCOPAN</p>
<h1>Un lugar para tus pedidos</h1>
<p class="muted">Iniciá sesión o creá tu cuenta para consultar tus pedidos. El pago se hace al retirar en el local.</p>
<div class="dos-columnas">
    <section class="panel">
        <h2>Iniciar sesión</h2>
        <?php if ($errorIngreso): ?><p class="aviso" role="alert"><?= e($errorIngreso) ?></p><?php endif; ?>
        <form action="cuenta.php" method="post">
            <?php csrf(); ?>
            <input type="hidden" name="accion" value="ingreso">
            <label>Correo electrónico
                <input type="email" name="email" autocomplete="email" maxlength="190" required value="<?= e($emailIngreso) ?>">
            </label>
            <label>Contraseña
                <input type="password" name="password" autocomplete="current-password" required>
            </label>
            <button class="boton ancho" type="submit">Ingresar</button>
        </form>
    </section>
    <section class="panel">
        <h2>Crear cuenta</h2>
        <?php if ($errorRegistro): ?><p class="aviso" role="alert"><?= e($errorRegistro) ?></p><?php endif; ?>
        <form action="cuenta.php" method="post">
            <?php csrf(); ?>
            <input type="hidden" name="accion" value="registro">
            <label>Nombre completo
                <input type="text" name="nombre" autocomplete="name" maxlength="100" required value="<?= e($nombreRegistro) ?>">
            </label>
            <label>Correo electrónico
                <input type="email" name="email" autocomplete="email" maxlength="190" required value="<?= e($emailRegistro) ?>">
            </label>
            <label>Contraseña (mínimo 8 caracteres)
                <input type="password" name="password" autocomplete="new-password" minlength="8" maxlength="72" required>
            </label>
            <button class="boton ancho" type="submit">Registrarme</button>
        </form>
    </section>
</div>
