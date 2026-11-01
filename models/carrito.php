<?php
if (!defined('APP_INICIADA')) { http_response_code(404); exit; }
// Modelo de carrito: estado en sesión y reglas de cantidades/importes.
// Recibe datos validados; no lee GET/POST ni genera respuestas HTTP.
function carrito_cambiar($id, $accion) {
    if ($accion === 'eliminar') { unset($_SESSION['carrito'][$id]); return ''; }
    if (!producto_activo($id)) {
        unset($_SESSION['carrito'][$id]);
        return 'El producto ya no está disponible.';
    }
    $cantidad = $_SESSION['carrito'][$id] ?? 0;
    if ($accion === 'restar') {
        if ($cantidad > 1) $_SESSION['carrito'][$id]--;
        else return 'La cantidad mínima es 1. Usá Eliminar para quitar el producto.';
    } elseif ($cantidad < 99) {
        $_SESSION['carrito'][$id] = $cantidad + 1;
    } else return 'El máximo por producto es 99 unidades.';
    return $accion === 'agregar' ? 'Producto agregado al carrito.' : '';
}
function carrito_resumen() {
    $lineas = []; $validos = [];
    foreach (productos_del_carrito(array_keys($_SESSION['carrito'])) as $producto) {
        $producto['cantidad'] = $_SESSION['carrito'][$producto['id']];
        $producto['centavos'] = centavos($producto['precio']);
        $producto['subtotal'] = $producto['centavos'] * $producto['cantidad'];
        $lineas[] = $producto;
        $validos[$producto['id']] = $producto['cantidad'];
    }
    $retirados = count($validos) !== count($_SESSION['carrito']);
    $_SESSION['carrito'] = $validos;
    return ['lineas' => $lineas, 'total' => array_sum(array_column($lineas, 'subtotal')), 'retirados' => $retirados];
}

// Convertir DECIMAL de MySQL a centavos evita sumar importes con errores de coma flotante.
function centavos($precio) {
    $partes = explode('.', (string) $precio);
    return (int) $partes[0] * 100 + (int) str_pad($partes[1] ?? '', 2, '0');
}
