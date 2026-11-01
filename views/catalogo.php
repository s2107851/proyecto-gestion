<?php if (!defined('APP_INICIADA')) { http_response_code(404); exit; }
?>
<section class="hero">
    <div><p class="etiqueta">HECHO PARA COMPARTIR</p><h1>Tu próxima mesa,<br>empieza acá.</h1><p>Elegí tus panes, tortas y postres.<br>Prepará tu pedido y retiralo en Mercopan.</p><a class="boton" href="#catalogo">Ver productos <span aria-hidden="true">↓</span></a></div>
    <div class="hero-arte" aria-hidden="true"><span class="anillo"></span><span class="pan">🥖</span><span class="nota">Siempre hay algo rico<br>para llevar.</span></div>
</section>
<section id="catalogo">
    <div class="seccion-titulo"><div><p class="etiqueta">ELEGÍ TUS FAVORITOS</p><h2>Nuestro catálogo</h2></div><span class="pill">Venta por unidad</span></div>
    <p class="muted">Productos y precios de ejemplo para la demostración.</p>
    <div class="filtros" hidden id="filtros">
        <label>Buscar producto<input type="search" id="buscar" placeholder="Por ejemplo: torta"></label>
        <label>Categoría<select id="categoria"><option value="">Todas las categorías</option><?php foreach ($sitio['categorias'] as $categoria): ?><option><?= e($categoria) ?></option><?php endforeach; ?></select></label>
    </div>
    <?php if ($error): ?><div class="vacio"><h3>No pudimos cargar el catálogo</h3><p>Comprobá que MySQL esté encendido y que hayas importado sql/proyecto_gestion.sql. Revisá includes/config.php.</p></div>
    <?php elseif (!$productos): ?><p class="vacio">No hay productos disponibles por el momento.</p><?php endif; ?>
    <div class="productos">
    <?php foreach ($productos as $producto): ?>
        <article class="producto" data-nombre="<?= e($producto['nombre']) ?>" data-categoria="<?= e($producto['categoria']) ?>">
            <div class="producto-arte <?= $producto['categoria'] === 'Tortas' ? 'rosa' : ($producto['categoria'] === 'Postres' ? 'verde' : '') ?>" aria-hidden="true"><?= ['Panes' => '🥖', 'Tortas' => '🍰', 'Postres' => '🍮'][$producto['categoria']] ?? '🍞' ?></div>
            <div class="producto-info"><p class="etiqueta"><?= e($producto['categoria']) ?></p><h3><?= e($producto['nombre']) ?></h3><p class="descripcion"><?= e($producto['descripcion']) ?></p><p class="precio"><?= dinero(centavos($producto['precio'])) ?><small> / unidad</small></p>
            <form action="carrito_accion.php" method="post"><?php csrf(); ?><input type="hidden" name="id" value="<?= $producto['id'] ?>"><input type="hidden" name="accion" value="agregar"><button class="boton ancho" type="submit">Agregar al carrito <span aria-hidden="true">+</span></button></form></div>
        </article>
    <?php endforeach; ?>
    </div><p id="sin-resultados" class="vacio" hidden>No encontramos productos con esa búsqueda.</p>
</section>

