<?php
if (!defined('APP_INICIADA')) { http_response_code(404); exit; }
function catalogo_mostrar() {
    $productos = []; $error = false;
    try { $productos = productos_activos(); }
    catch (PDOException $ex) { error_log($ex->getMessage()); $error = true; http_response_code(503); }
    vista('catalogo', ['titulo' => 'Catálogo', 'productos' => $productos, 'error' => $error]);
}
