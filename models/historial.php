<?php
if (!defined('APP_INICIADA')) { http_response_code(404); exit; }
function historial_del_usuario($usuarioId) {
    // No se aceptan identificadores de usuario del cliente HTTP.
    $q = db()->prepare('SELECT p.id, p.creado_en, p.estado, p.pagado, p.total,
        d.nombre_producto, d.cantidad, d.precio_unitario
        FROM pedidos p LEFT JOIN detalle_pedido d ON d.pedido_id = p.id
        WHERE p.usuario_id = ? ORDER BY p.creado_en DESC, p.id DESC, d.id');
    $q->execute([$usuarioId]); $salida = [];
    foreach ($q->fetchAll() as $fila) {
        $id = (int) $fila['id'];
        if (!isset($salida[$id])) {
            $salida[$id] = ['id' => $id, 'fecha' => $fila['creado_en'], 'estado' => $fila['estado'],
                'pagado' => (bool) $fila['pagado'], 'total' => $fila['total'], 'detalle' => []];
        }
        if ($fila['nombre_producto'] !== null) {
            $salida[$id]['detalle'][] = [
                'nombre' => $fila['nombre_producto'], 'cantidad' => (int) $fila['cantidad'],
                'precio' => $fila['precio_unitario']
            ];
        }
    }
    return array_values($salida);
}
