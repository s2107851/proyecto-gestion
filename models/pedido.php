<?php
if (!defined('APP_INICIADA')) { http_response_code(404); exit; }

function pedido_firma($resumen) {
    $articulos = [];
    foreach ($resumen['lineas'] as $linea) {
        $articulos[] = [$linea['id'], $linea['nombre'], $linea['centavos'], $linea['cantidad']];
    }
    return hash('sha256', serialize($articulos));
}

function pedido_guardar($usuarioId, $token, $firma) {
    $pdo = db();
    $pdo->beginTransaction();
    try {
        // Recupera un pedido ya guardado si se interrumpió la respuesta anterior.
        $consulta = $pdo->prepare('SELECT id, total FROM pedidos WHERE token_confirmacion = ? AND usuario_id = ?');
        $consulta->execute([$token, $usuarioId]);
        $anterior = $consulta->fetch();
        if ($anterior) {
            $pdo->commit();
            return ['id' => $anterior['id'], 'total' => centavos($anterior['total'])];
        }
        $resumen = carrito_resumen(true);
        if (!$resumen['lineas'] || $resumen['retirados'] || pedido_firma($resumen) !== $firma) {
            throw new DomainException('El carrito cambió. Revisá los productos, las cantidades y el total antes de confirmar.');
        }

        $total = $resumen['total'];
        $importe = sprintf('%d.%02d', intdiv($total, 100), $total % 100);
        $cabecera = $pdo->prepare('INSERT INTO pedidos (usuario_id, total, token_confirmacion) VALUES (?, ?, ?)');
        $cabecera->execute([$usuarioId, $importe, $token]);
        $id = $pdo->lastInsertId();
        $detalle = $pdo->prepare('INSERT INTO detalle_pedido
            (pedido_id, producto_id, nombre_producto, cantidad, precio_unitario) VALUES (?, ?, ?, ?, ?)');
        foreach ($resumen['lineas'] as $linea) {
            $detalle->execute([$id, $linea['id'], $linea['nombre'], $linea['cantidad'], $linea['precio']]);
        }
        $pdo->commit();
        return ['id' => $id, 'total' => $total];
    } catch (Throwable $ex) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        throw $ex;
    }
}
