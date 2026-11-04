<?php if (!defined('APP_INICIADA')) { http_response_code(404); exit; } ?>
<p class="etiqueta">PANEL INTERNO</p><h1>Administración</h1>
<nav class="pestanas" aria-label="Apartados">
    <a href="admin.php?seccion=productos" <?= $seccion === 'productos' ? 'aria-current="page"' : '' ?>>Productos</a>
    <a href="admin.php?seccion=clientes" <?= $seccion === 'clientes' ? 'aria-current="page"' : '' ?>>Clientes</a>
    <a href="admin.php?seccion=pedidos" <?= $seccion === 'pedidos' ? 'aria-current="page"' : '' ?>>Pedidos y cobros</a>
</nav>
<?php if ($error): ?><p class="aviso" role="alert"><?= e($error) ?></p><?php endif; ?>

<?php if ($seccion === 'productos'): ?>
<div class="dos-columnas admin-columnas">
<section class="panel"><h2><?= $productoEditar ? 'Editar producto' : 'Nuevo producto' ?></h2>
    <form action="admin.php?seccion=productos" method="post"><?php csrf(); ?>
        <input type="hidden" name="accion" value="guardar_producto">
        <input type="hidden" name="id" value="<?= (int) ($productoEditar['id'] ?? 0) ?>">
        <label>Nombre<input name="nombre" required maxlength="100" value="<?= e($productoEditar['nombre'] ?? '') ?>"></label>
        <label>Descripción<input name="descripcion" maxlength="255" value="<?= e($productoEditar['descripcion'] ?? '') ?>"></label>
        <label>Categoría<select name="categoria">
        <?php foreach (['Panes','Tortas','Postres'] as $c): ?>
            <option value="<?= $c ?>" <?= ($productoEditar['categoria'] ?? 'Panes') === $c ? 'selected' : '' ?>><?= $c ?></option>
        <?php endforeach; ?></select></label>
        <label>Precio por unidad ($)<input name="precio" type="number" min="0.01" max="99999999.99" step="0.01" required value="<?= e($productoEditar['precio'] ?? '') ?>"></label>
        <label class="casilla"><input type="checkbox" name="activo" value="1" <?= !$productoEditar || $productoEditar['activo'] ? 'checked' : '' ?>> Disponible</label>
        <button type="submit" class="boton ancho">Guardar</button>
        <?php if ($productoEditar): ?><p><a href="admin.php?seccion=productos">Cancelar edición</a></p><?php endif; ?>
    </form>
</section>
<section class="panel"><h2>Catálogo completo</h2>
    <?php if (!$productos): ?><p>Sin productos.</p><?php endif; ?>
    <?php foreach ($productos as $p): ?>
        <div class="fila admin-fila">
        <div><strong><?= e($p['nombre']) ?></strong><br><small><?= e($p['categoria']) ?> · <?= dinero(centavos($p['precio'])) ?> · <?= $p['activo'] ? 'Activo' : 'Inactivo' ?></small></div>
        <div class="admin-acciones">
            <a href="admin.php?seccion=productos&amp;editar=<?= (int) $p['id'] ?>">Editar</a>
            <form method="post" action="admin.php?seccion=productos"><?php csrf(); ?>
                <input type="hidden" name="accion" value="disponibilidad">
                <input type="hidden" name="id" value="<?= (int) $p['id'] ?>">
                <input type="hidden" name="activo" value="<?= $p['activo'] ? 0 : 1 ?>">
                <button type="submit" class="boton boton-claro"><?= $p['activo'] ? 'Desactivar' : 'Activar' ?></button>
            </form>
        </div></div>
    <?php endforeach; ?>
</section></div>
<?php elseif ($seccion === 'clientes'): ?>
<section class="panel"><h2>Clientes</h2>
    <form class="filtros" method="get" action="admin.php">
        <input type="hidden" name="seccion" value="clientes">
        <label>Nombre o correo<input name="buscar" maxlength="100" value="<?= e($busqueda) ?>"></label>
        <button type="submit" class="boton">Buscar</button>
    </form>
    <div class="tabla-scroll"><table class="tabla-admin"><thead><tr><th>Nombre</th><th>Correo</th><th>Pedidos</th><th></th></tr></thead><tbody>
        <?php foreach ($clientes as $c): ?><tr>
            <td><?= e($c['nombre']) ?></td><td><?= e($c['email']) ?></td><td><?= (int) $c['cantidad_pedidos'] ?></td>
            <td><a href="admin.php?seccion=clientes&amp;cliente_id=<?= (int) $c['id'] ?>">Ver pedidos</a></td>
        </tr><?php endforeach; ?>
    </tbody></table></div>
    <?php if (!$clientes): ?><p>No hay coincidencias.</p><?php endif; ?>
</section>
<?php if ($clienteElegido): ?>
<section class="panel bloque-admin"><h2>Pedidos de <?= e($clienteElegido['nombre']) ?></h2>
    <?php if (!$pedidos): ?><p>Sin pedidos registrados.</p><?php endif; ?>
    <?php foreach ($pedidos as $p): ?>
        <p>Pedido #<?= (int) $p['id'] ?> · <?= e($p['creado_en']) ?> · <?= e($p['estado']) ?> · <?= dinero(centavos($p['total'])) ?>
        · <a href="admin.php?seccion=pedidos&amp;pedido_id=<?= (int) $p['id'] ?>">Ver detalle</a></p>
    <?php endforeach; ?>
</section><?php endif; ?>
<?php else: ?>
<section class="panel"><h2>Pedidos</h2>
    <p>Total cobrado (retirados y pagados): <strong><?= dinero(centavos($totalCobrado)) ?></strong></p>
    <form class="filtros" method="get" action="admin.php">
        <input type="hidden" name="seccion" value="pedidos">
        <label>Estado<select name="estado"><option value="">Todos</option>
        <?php foreach (['pendiente','listo','retirado','cancelado'] as $v): ?>
            <option value="<?= $v ?>" <?= $estado === $v ? 'selected' : '' ?>><?= ucfirst($v) ?></option>
        <?php endforeach; ?></select></label>
        <label>Fecha<input type="date" name="fecha" value="<?= e($fecha) ?>"></label>
        <button type="submit" class="boton">Filtrar</button>
    </form>
    <div class="tabla-scroll"><table class="tabla-admin"><thead><tr><th>Pedido</th><th>Cliente</th><th>Total</th><th>Estado</th><th>Acciones</th></tr></thead><tbody>
    <?php foreach ($pedidos as $p): ?>
        <tr><td><a href="admin.php?seccion=pedidos&amp;pedido_id=<?= (int) $p['id'] ?>">#<?= (int) $p['id'] ?></a><br><small><?= e($p['creado_en']) ?></small></td>
        <td><?= e($p['cliente']) ?></td><td><?= dinero(centavos($p['total'])) ?></td>
        <td><?= e($p['estado']) ?> · <?= $p['pagado'] ? 'Pagado' : 'Sin pagar' ?></td>
        <td><div class="admin-acciones">
        <?php foreach (($p['estado'] === 'pendiente' ? ['listo','cancelado'] : ($p['estado'] === 'listo' ? ['retirado','cancelado'] : [])) as $nuevo): ?>
            <form method="post" action="admin.php?seccion=pedidos"><?php csrf(); ?>
                <input type="hidden" name="accion" value="estado_pedido">
                <input type="hidden" name="id" value="<?= (int) $p['id'] ?>">
                <input type="hidden" name="estado" value="<?= $nuevo ?>">
                <button type="submit" class="boton boton-claro"><?= $nuevo === 'retirado' ? 'Retirar y cobrar' : ucfirst($nuevo) ?></button>
            </form>
        <?php endforeach; ?>
        </div></td></tr>
    <?php endforeach; ?>
    </tbody></table></div>
    <?php if (!$pedidos): ?><p>No se encontraron pedidos.</p><?php endif; ?>
</section>
<?php if ($pedidoElegido): ?><section class="panel bloque-admin">
    <h2>Detalle del pedido #<?= (int) $pedidoElegido['id'] ?></h2>
    <p><?= e($pedidoElegido['cliente']) ?> · <?= e($pedidoElegido['email']) ?> · <?= e($pedidoElegido['creado_en']) ?> · <?= e($pedidoElegido['estado']) ?></p>
    <?php foreach ($detalleElegido as $d): ?><p><?= (int) $d['cantidad'] ?> × <?= e($d['nombre_producto']) ?> · <?= dinero(centavos($d['precio_unitario'])) ?> por unidad</p><?php endforeach; ?>
    <strong>Total histórico: <?= dinero(centavos($pedidoElegido['total'])) ?></strong>
</section><?php endif; ?>
<?php endif; ?>
