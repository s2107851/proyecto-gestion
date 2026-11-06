<?php
if (!defined('APP_INICIADA')) { http_response_code(404); exit; }
// Modelo: acceso a los productos de MySQL. No genera HTML ni redirecciones.
function productos_activos() {
    return db()->query('SELECT * FROM productos WHERE activo = 1 ORDER BY categoria, nombre')->fetchAll();
}
function producto_activo($id) {
    $consulta = db()->prepare('SELECT id FROM productos WHERE id = ? AND activo = 1');
    $consulta->execute([$id]);
    return (bool) $consulta->fetch();
}
function productos_del_carrito($ids, $bloquear = false) {
    if (!$ids) return [];
    $marcas = implode(',', array_fill(0, count($ids), '?'));
    $bloqueo = $bloquear ? ' FOR UPDATE' : '';
    $consulta = db()->prepare("SELECT * FROM productos WHERE activo = 1 AND id IN ($marcas) ORDER BY id$bloqueo");
    $consulta->execute($ids);
    return $consulta->fetchAll();
}
