<?php
// Funciones compartidas: conexión, sesión, salida segura y protección de formularios.
require __DIR__ . '/config.php';
session_name('proyecto_gestion');
session_start(['cookie_httponly' => true, 'cookie_samesite' => 'Lax', 'use_strict_mode' => true]);
header('Cache-Control: no-store');
$_SESSION['carrito'] = $_SESSION['carrito'] ?? [];
$_SESSION['csrf'] = $_SESSION['csrf'] ?? bin2hex(random_bytes(32));
$sitio = json_decode(file_get_contents(__DIR__ . '/../assets/sitio.json'), true);

function db() {
    global $config;
    static $pdo;
    if (!$pdo) {
        $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']};charset=utf8mb4";
        $pdo = new PDO($dsn, $config['user'], $config['password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    }
    return $pdo;
}
function e($texto) { return htmlspecialchars((string) $texto, ENT_QUOTES, 'UTF-8'); }
function dinero($centavos) { return '$ ' . number_format($centavos / 100, 2, ',', '.'); }
// Convertir DECIMAL de MySQL a centavos evita sumar importes con errores de coma flotante.
function centavos($precio) {
    $partes = explode('.', (string) $precio);
    return (int) $partes[0] * 100 + (int) str_pad($partes[1] ?? '', 2, '0');
}
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
function detalle_carrito() {
    if (!$_SESSION['carrito']) return [];
    $ids = array_keys($_SESSION['carrito']);
    $marcas = implode(',', array_fill(0, count($ids), '?'));
    $consulta = db()->prepare("SELECT * FROM productos WHERE activo = 1 AND id IN ($marcas) ORDER BY id");
    $consulta->execute($ids);
    $lineas = [];
    $validos = [];
    foreach ($consulta as $producto) {
        $producto['cantidad'] = $_SESSION['carrito'][$producto['id']];
        $producto['centavos'] = centavos($producto['precio']);
        $producto['subtotal'] = $producto['centavos'] * $producto['cantidad'];
        $lineas[] = $producto;
        $validos[$producto['id']] = $producto['cantidad'];
    }
    if (count($validos) !== count($_SESSION['carrito'])) mensaje('Se retiró del carrito un producto que ya no está disponible.');
    $_SESSION['carrito'] = $validos;
    return $lineas;
}
