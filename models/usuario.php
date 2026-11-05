<?php
if (!defined('APP_INICIADA')) { http_response_code(404); exit; }

// HU01-HU04: el modelo consulta y guarda usuarios; no lee POST ni genera HTML.
function usuario_buscar_por_email($email) {
    $consulta = db()->prepare('SELECT id, nombre, email, password_hash, rol FROM usuarios WHERE email = ? LIMIT 1');
    $consulta->execute([$email]);
    return $consulta->fetch();
}

function usuario_registrar($nombre, $email, $passwordHash) {
    // El rol siempre lo decide el servidor: nadie puede registrarse como administrador.
    $consulta = db()->prepare("INSERT INTO usuarios (nombre, email, password_hash, rol) VALUES (?, ?, ?, 'cliente')");
    $consulta->execute([$nombre, $email, $passwordHash]);
    return (int) db()->lastInsertId();
}
