<?php
if (!defined('APP_INICIADA')) { http_response_code(404); exit; }
function carrito_mostrar() {
    $resumen = ['lineas' => [], 'total' => 0]; $error = false;
    try {
        $resumen = carrito_resumen();
        if ($resumen['retirados']) mensaje('Se retiró del carrito un producto que ya no está disponible.');
    } catch (PDOException $ex) { error_log($ex->getMessage()); $error = true; http_response_code(503); }
    $token = null;
    if ($resumen['lineas'] && (usuario()['rol'] ?? '') === 'cliente') {
        $firma = pedido_firma($resumen);
        $revision = $_SESSION['revision_pedido'] ?? [];
        if (($revision['firma'] ?? '') !== $firma) {
            $revision = ['firma' => $firma, 'token' => bin2hex(random_bytes(32))];
        }
        $_SESSION['revision_pedido'] = $revision;
        $token = $revision['token'];
    } else {
        unset($_SESSION['revision_pedido']);
    }
    vista('carrito', ['titulo' => 'Mi carrito', 'lineas' => $resumen['lineas'], 'total' => $resumen['total'], 'error' => $error, 'token' => $token]);
}
function carrito_actualizar() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); header('Allow: POST'); exit('Usá los botones del carrito.'); }
    verificar_csrf();
    $id = filter_var($_POST['id'] ?? '', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    $accion = $_POST['accion'] ?? '';
    if (!$id || !in_array($accion, ['agregar', 'sumar', 'restar', 'eliminar'], true)) {
        http_response_code(400); exit('Producto o acción inválidos.');
    }
    try {
        $aviso = carrito_cambiar($id, $accion);
        if ($aviso) mensaje($aviso);
    } catch (PDOException $ex) {
        error_log($ex->getMessage()); mensaje('No se pudo actualizar el carrito. Intentá de nuevo.'); ir('carrito.php');
    }
    ir($accion === 'agregar' ? 'index.php#catalogo' : 'carrito.php');
}
