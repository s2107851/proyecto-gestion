<?php
if (!defined('APP_INICIADA')) { http_response_code(404); exit; }
function cuenta_mostrar() {
    // ENTREGA 1 · HU01-HU03: validar POST aquí y delegar usuarios a models/usuario.php.
    vista('cuenta', ['titulo' => 'Mi cuenta']);
}
function perfil_mostrar() {
    exigir_usuario();
    // ENTREGA 1 · HU04: pasar los datos de usuario a la vista.
    // ENTREGA 3 · HU09: consultar pedidos propios mediante el modelo de pedidos.
    vista('perfil', ['titulo' => 'Mi perfil']);
}
