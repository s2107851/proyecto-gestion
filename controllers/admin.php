<?php
if (!defined('APP_INICIADA')) { http_response_code(404); exit; }
function admin_mostrar() {
    exigir_admin();
    // ENTREGA 3 · HU10-HU12: validar permisos/acciones y llamar a los modelos.
    vista('admin', ['titulo' => 'Administración']);
}
