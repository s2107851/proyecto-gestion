<?php
if (!defined('APP_INICIADA')) { http_response_code(404); exit; }
// Modelo de administración: únicamente consultas y reglas de persistencia.
function admin_productos() {
    return db()->query('SELECT id, nombre, descripcion, categoria, precio, activo FROM productos ORDER BY categoria, nombre')->fetchAll();
}
function admin_producto($id) {
    $q = db()->prepare('SELECT id, nombre, descripcion, categoria, precio, activo FROM productos WHERE id = ?');
    $q->execute([$id]);
    return $q->fetch();
}
function admin_guardar_producto($id, $nombre, $descripcion, $categoria, $precio, $activo) {
    if ($id === 0) {
        $q = db()->prepare('INSERT INTO productos (nombre, descripcion, categoria, precio, activo) VALUES (?, ?, ?, ?, ?)');
        $q->execute([$nombre, $descripcion, $categoria, $precio, $activo]);
    } else {
        $q = db()->prepare('UPDATE productos SET nombre = ?, descripcion = ?, categoria = ?, precio = ?, activo = ? WHERE id = ?');
        $q->execute([$nombre, $descripcion, $categoria, $precio, $activo, $id]);
    }
}
function admin_cambiar_disponibilidad($id, $activo) {
    $q = db()->prepare('UPDATE productos SET activo = ? WHERE id = ?');
    $q->execute([$activo, $id]);
    return $q->rowCount();
}
function admin_clientes($busqueda) {
    $q = db()->prepare("SELECT u.id, u.nombre, u.email, COUNT(p.id) AS cantidad_pedidos
        FROM usuarios u LEFT JOIN pedidos p ON p.usuario_id = u.id
        WHERE u.rol = 'cliente' AND (u.nombre LIKE ? OR u.email LIKE ?)
        GROUP BY u.id, u.nombre, u.email ORDER BY u.nombre LIMIT 100");
    $q->execute(['%' . $busqueda . '%', '%' . $busqueda . '%']);
    return $q->fetchAll();
}
function admin_cliente($id) {
    $q = db()->prepare("SELECT id, nombre, email FROM usuarios WHERE id = ? AND rol = 'cliente'");
    $q->execute([$id]); return $q->fetch();
}
function admin_pedidos($estado = '', $fecha = '', $clienteId = null) {
    $sql = 'SELECT p.id, p.usuario_id, p.total, p.estado, p.pagado, p.creado_en,
                   u.nombre AS cliente, u.email
            FROM pedidos p JOIN usuarios u ON u.id = p.usuario_id WHERE 1=1';
    $args = [];
    if ($estado !== '') { $sql .= ' AND p.estado = ?'; $args[] = $estado; }
    if ($fecha !== '') { $sql .= ' AND DATE(p.creado_en) = ?'; $args[] = $fecha; }
    if ($clienteId !== null) { $sql .= ' AND p.usuario_id = ?'; $args[] = $clienteId; }
    $sql .= ' ORDER BY p.creado_en DESC, p.id DESC LIMIT 100';
    $q = db()->prepare($sql); $q->execute($args); return $q->fetchAll();
}
function admin_pedido($id) {
    $q = db()->prepare('SELECT p.id, p.total, p.estado, p.pagado, p.creado_en, u.nombre AS cliente, u.email
        FROM pedidos p JOIN usuarios u ON u.id = p.usuario_id WHERE p.id = ?');
    $q->execute([$id]); return $q->fetch();
}
function admin_detalle($id) {
    $q = db()->prepare('SELECT nombre_producto, cantidad, precio_unitario FROM detalle_pedido WHERE pedido_id = ? ORDER BY id');
    $q->execute([$id]); return $q->fetchAll();
}
function admin_total_cobrado() {
    return db()->query("SELECT COALESCE(SUM(total), 0) FROM pedidos WHERE estado = 'retirado' AND pagado = 1")->fetchColumn();
}
function admin_cambiar_estado($id, $destino) {
    // UPDATE condicional protege de regresiones y dos administradores actualizando al mismo tiempo.
    $condiciones = [
        'listo' => "estado = 'pendiente'",
        'retirado' => "estado = 'listo'",
        'cancelado' => "estado IN ('pendiente','listo')"
    ];
    if (!isset($condiciones[$destino])) return false;
    $q = db()->prepare('UPDATE pedidos SET estado = ?, pagado = ? WHERE id = ? AND pagado = 0 AND ' . $condiciones[$destino]);
    $q->execute([$destino, $destino === 'retirado' ? 1 : 0, $id]);
    return $q->rowCount() === 1;
}
