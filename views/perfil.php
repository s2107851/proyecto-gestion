<?php if (!defined('APP_INICIADA')) { http_response_code(404); exit; } ?>
<p class="etiqueta">MI CUENTA</p><h1>Mi perfil</h1>
<section class="panel"><h2>Mis datos</h2>
<p><strong>Nombre:</strong> <?= e($usuarioActual['nombre']) ?></p>
<p><strong>Correo:</strong> <?= e($usuarioActual['email']) ?></p></section>
<section class="panel bloque-admin"><h2>Mis pedidos</h2>
<?php if ($errorHistorial): ?><p class="aviso">No se pudo consultar el historial.</p>
<?php elseif (!$pedidos): ?><p>Todavía no tenés pedidos.</p>
<?php else: foreach ($pedidos as $p): ?>
<article class="pedido-personal">
    <h3>Pedido #<?= (int) $p['id'] ?></h3>
    <p><?= e($p['fecha']) ?> · <?= e(ucfirst($p['estado'])) ?> · <?= $p['pagado'] ? 'Pagado' : 'Pago pendiente en el local' ?></p>
    <ul><?php foreach ($p['detalle'] as $d): ?>
        <li><?= (int) $d['cantidad'] ?> × <?= e($d['nombre']) ?> — <?= dinero(centavos($d['precio'])) ?> / unidad</li>
    <?php endforeach; ?></ul>
    <p><strong>Total: <?= dinero(centavos($p['total'])) ?></strong></p>
</article>
<?php endforeach; endif; ?>
</section>
