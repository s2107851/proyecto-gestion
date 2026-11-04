<?php
if (!defined('APP_INICIADA')) { http_response_code(404); exit; }
function cuenta_mostrar() {
    // ENTREGA 1 · HU01-HU03: validar POST aquí y delegar usuarios a models/usuario.php.
    vista('cuenta', ['titulo' => 'Mi cuenta']);
}
function perfil_mostrar() {
    exigir_usuario();
    $pedidos = []; $errorHistorial = false;
    try { $pedidos = historial_del_usuario((int) usuario()['id']); }
    catch (PDOException $ex) { error_log($ex->getMessage()); http_response_code(503); $errorHistorial = true; }
    // ENTREGA 1 · HU04: esta vista ya muestra nombre y correo desde la sesión.
    // Al implementar cuentas, conservar este historial y no consultar pedidos en las vistas.
    vista('perfil', ['titulo' => 'Mi perfil', 'pedidos' => $pedidos, 'errorHistorial' => $errorHistorial]);
}
