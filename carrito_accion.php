<?php
require __DIR__ . '/includes/app.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); header('Allow: POST'); exit('Usá los botones del carrito.'); }
verificar_csrf();
$id = filter_var($_POST['id'] ?? '', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
$accion = $_POST['accion'] ?? '';
if (!$id || !in_array($accion, ['agregar', 'sumar', 'restar', 'eliminar'], true)) {
    http_response_code(400); exit('Producto o acción inválidos.');
}
// Quitar funciona aunque el producto haya sido desactivado o falle la base.
if ($accion === 'eliminar') { unset($_SESSION['carrito'][$id]); ir('carrito.php'); }
try {
    $consulta = db()->prepare('SELECT id FROM productos WHERE id = ? AND activo = 1');
    $consulta->execute([$id]);
    if (!$consulta->fetch()) { unset($_SESSION['carrito'][$id]); mensaje('El producto ya no está disponible.'); ir('carrito.php'); }
} catch (PDOException $ex) {
    error_log($ex->getMessage()); mensaje('No se pudo actualizar el carrito. Intentá de nuevo.'); ir('carrito.php');
}
$cantidad = $_SESSION['carrito'][$id] ?? 0;
if ($accion === 'restar') {
    if ($cantidad > 1) $_SESSION['carrito'][$id]--;
    else mensaje('La cantidad mínima es 1. Usá Eliminar para quitar el producto.');
} elseif ($cantidad < 99) {
    $_SESSION['carrito'][$id] = $cantidad + 1;
} else mensaje('El máximo por producto es 99 unidades.');
if ($accion === 'agregar') { mensaje('Producto agregado al carrito.'); ir('index.php#catalogo'); }
ir('carrito.php');
