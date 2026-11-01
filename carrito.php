<?php
require __DIR__ . '/includes/app.php';
$titulo = 'Mi carrito'; $error = false;
try { $lineas = detalle_carrito(); }
catch (PDOException $ex) { error_log($ex->getMessage()); $lineas = []; $error = true; http_response_code(503); }
$total = array_sum(array_column($lineas, 'subtotal'));
require __DIR__ . '/includes/header.php';
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
</section><aside class="resumen"><p class="etiqueta">RESUMEN DEL PEDIDO</p><h2>Todo listo para revisar</h2><div class="fila"><span>Unidades</span><strong><?= array_sum(array_column($lineas, 'cantidad')) ?></strong></div><div class="fila total"><span>Total</span><strong><?= dinero($total) ?></strong></div><p><strong>Retiro en el local</strong><br>Pagás al retirar. Sin envíos.</p><button class="boton ancho" disabled>Confirmar pedido</button><p class="muted">La confirmación se implementará en la entrega 2. Esta versión todavía no registra pedidos.</p></aside></div>
<?php endif; ?>
<?php require __DIR__ . '/includes/footer.php'; ?>
