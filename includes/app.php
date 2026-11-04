<?php
define('APP_INICIADA', true);
// Inicio de la aplicación y utilidades HTTP/presentación compartidas.
require __DIR__ . '/config.php';
session_name('proyecto_gestion');
session_start(['cookie_httponly' => true, 'cookie_samesite' => 'Lax', 'use_strict_mode' => true]);
header('Cache-Control: no-store');
$_SESSION['carrito'] = $_SESSION['carrito'] ?? [];
$_SESSION['csrf'] = $_SESSION['csrf'] ?? bin2hex(random_bytes(32));
$sitio = json_decode(file_get_contents(__DIR__ . '/../assets/sitio.json'), true);

function e($texto) { return htmlspecialchars((string) $texto, ENT_QUOTES, 'UTF-8'); }
function dinero($centavos) { return '$ ' . number_format($centavos / 100, 2, ',', '.'); }
function ir($pagina) { header('Location: ' . $pagina, true, 303); exit; }
function csrf() { echo '<input type="hidden" name="csrf" value="' . e($_SESSION['csrf']) . '">'; }
function verificar_csrf() {
    if (!is_string($_POST['csrf'] ?? null) || !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
        http_response_code(403); exit('Solicitud inválida. Volvé a la página y probá nuevamente.');
    }
}
function mensaje($texto) { $_SESSION['mensaje'] = $texto; }
function usuario() { return $_SESSION['usuario'] ?? null; }
function exigir_usuario() { if (!usuario()) { mensaje('Ingresá a tu cuenta para continuar.'); ir('cuenta.php'); } }
function exigir_admin() {
    exigir_usuario();
    if (usuario()['rol'] !== 'admin') { http_response_code(403); exit('Acceso reservado a administración.'); }
}

// Carga explícita: pocas piezas, sin autoloader ni framework.
require_once __DIR__ . '/conexion.php';
require_once __DIR__ . '/../models/producto.php';
require_once __DIR__ . '/../models/carrito.php';
require_once __DIR__ . '/../models/historial.php';
require_once __DIR__ . '/../models/administracion.php';
require_once __DIR__ . '/../controllers/catalogo.php';
require_once __DIR__ . '/../controllers/carrito.php';
require_once __DIR__ . '/../controllers/cuenta.php';
require_once __DIR__ . '/../controllers/admin.php';

function vista($nombre, $datos = []) {
    global $sitio;
    // Los nombres de vista se escriben en los controladores, nunca vienen del usuario.
    extract($datos, EXTR_SKIP);
    $usuarioActual = usuario();
    $unidadesCarrito = array_sum($_SESSION['carrito']);
    $aviso = $_SESSION['mensaje'] ?? '';
    unset($_SESSION['mensaje']);
    require __DIR__ . '/../views/layout/header.php';
    require __DIR__ . '/../views/' . $nombre . '.php';
    require __DIR__ . '/../views/layout/footer.php';
}
