<?php
if (!defined('APP_INICIADA')) { http_response_code(404); exit; }

function confirmar_pedido() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Allow: POST'); http_response_code(405); exit;
    }
    verificar_csrf();
    exigir_usuario();
    if (usuario()['rol'] !== 'cliente') { http_response_code(403); exit; }
    $token = $_POST['token'] ?? null;
    $revision = $_SESSION['revision_pedido'] ?? null;
    if (!$revision || !is_string($token) || !hash_equals($revision['token'], $token)) {
        mensaje('Esta confirmación ya no está vigente. Revisá el carrito o consultá tus pedidos.');
        ir('carrito.php');
    }

    try {
        $pedido = pedido_guardar(usuario()['id'], $token, $revision['firma']);
    } catch (DomainException $ex) {
        unset($_SESSION['revision_pedido']);
        mensaje($ex->getMessage());
        ir('carrito.php');
    } catch (PDOException $ex) {
        error_log($ex->getMessage());
        mensaje('No pudimos confirmar el pedido. Tu carrito está guardado. Volvé a intentarlo.');
        ir('carrito.php');
    }
    unset($_SESSION['revision_pedido']);
    $_SESSION['carrito'] = [];
    mensaje('Pedido #' . $pedido['id'] . ' confirmado por ' . dinero($pedido['total']) . '. Retirás y pagás en el local.');
    ir('perfil.php');
}
