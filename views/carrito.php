<?php if (!defined('APP_INICIADA')) { http_response_code(404); exit; }
?>
<div class="seccion-titulo"><div><p class="etiqueta">TU SELECCIÓN</p><h1>Mi carrito</h1><p class="muted">Revisá cada artículo antes de confirmar.</p></div><a href="index.php">← Seguir eligiendo</a></div>
<?php if ($error): ?><p class="vacio">No se pudo consultar el carrito. Revisá la conexión a MySQL; tu selección sigue guardada en esta sesión.</p>
<?php elseif (!$lineas): ?><div class="vacio"><h2>Tu carrito está vacío</h2><p>Elegí algo rico para empezar.</p><a class="boton" href="index.php">Explorar catálogo</a></div>
<?php else: ?>
<div class="compra"><section class="lista-carrito" aria-label="Productos seleccionados">
<?php foreach ($lineas as $linea): ?>
    <article class="linea"><div><span class="etiqueta"><?= e($linea['categoria']) ?></span><h2><?= e($linea['nombre']) ?></h2><p><?= dinero($linea['centavos']) ?> por unidad</p></div>
        <form action="carrito_accion.php" method="post" class="cantidad"><?php csrf(); ?><input type="hidden" name="id" value="<?= $linea['id'] ?>">
            <button name="accion" value="restar" aria-label="Restar una unidad de <?= e($linea['nombre']) ?>" <?= $linea['cantidad'] === 1 ? 'disabled' : '' ?>>−</button>
            <span aria-label="Cantidad"><?= $linea['cantidad'] ?></span>
            <button name="accion" value="sumar" aria-label="Sumar una unidad de <?= e($linea['nombre']) ?>" <?= $linea['cantidad'] >= 99 ? 'disabled' : '' ?>>+</button>
            <button name="accion" value="eliminar" class="quitar" aria-label="Eliminar <?= e($linea['nombre']) ?>">Eliminar</button>
        </form><strong class="subtotal"><?= dinero($linea['subtotal']) ?></strong>
    </article>
<?php endforeach; ?>
</section><aside class="resumen"><p class="etiqueta">RESUMEN DEL PEDIDO</p><h2>Todo listo para revisar</h2><div class="fila"><span>Unidades</span><strong><?= array_sum(array_column($lineas, 'cantidad')) ?></strong></div><div class="fila total"><span>Total</span><strong><?= dinero($total) ?></strong></div><p><strong>Retiro en el local</strong><br>Pagás al retirar. Sin envíos.</p>
<?php if ($token): ?>
<form method="post" action="confirmar.php"><?php csrf(); ?>
    <input type="hidden" name="token" value="<?= e($token) ?>">
    <button class="boton ancho">Confirmar pedido</button>
</form>
<?php elseif (!$usuarioActual): ?>
<a class="boton ancho" href="cuenta.php">Ingresar para confirmar</a>
<?php else: ?>
<p>Para hacer un pedido, ingresá con una cuenta de cliente.</p>
<?php endif; ?>
</aside></div>
<?php endif; ?>

