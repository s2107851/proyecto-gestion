<?php
if (!defined('APP_INICIADA')) { http_response_code(404); exit; }

// Leer exclusivamente campos de texto: un POST manipulado también puede mandar arreglos.
function campo_cuenta($nombre) {
    return is_string($_POST[$nombre] ?? null) ? trim($_POST[$nombre]) : '';
}

function cuenta_iniciar_sesion($fila) {
    // Regenerar el ID evita mantener el identificador de sesión anterior al ingreso.
    session_regenerate_id(true);
    $_SESSION['usuario'] = [
        'id' => (int) $fila['id'],
        'nombre' => $fila['nombre'],
        'email' => $fila['email'],
        'rol' => $fila['rol'],
    ];
    // El carrito invitado se mantiene; se renueva el CSRF tras cambiar identidad.
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

function cuenta_mostrar() {
    if (usuario()) ir('perfil.php');

    if ($_SERVER['REQUEST_METHOD'] !== 'GET' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Allow: GET, POST'); http_response_code(405); exit('Método no permitido.');
    }
    $errorRegistro = '';
    $errorIngreso = '';
    $nombreRegistro = '';
    $emailRegistro = '';
    $emailIngreso = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        verificar_csrf();
        $accion = campo_cuenta('accion');

        if ($accion === 'registro') {
            $nombreRegistro = campo_cuenta('nombre');
            $emailRegistro = strtolower(campo_cuenta('email'));
            // La contraseña no se recorta ni se devuelve nunca en el formulario.
            $password = is_string($_POST['password'] ?? null) ? $_POST['password'] : '';

            if ($nombreRegistro === '' || mb_strlen($nombreRegistro) > 100) {
                $errorRegistro = 'Ingresá un nombre de hasta 100 caracteres.';
            } elseif (strlen($emailRegistro) > 190 || !filter_var($emailRegistro, FILTER_VALIDATE_EMAIL)) {
                $errorRegistro = 'Ingresá un correo electrónico válido.';
            } elseif (strlen($password) < 8 || strlen($password) > 72) {
                $errorRegistro = 'La contraseña debe tener entre 8 y 72 caracteres/bytes.';
            } else {
                try {
                    if (usuario_buscar_por_email($emailRegistro)) {
                        $errorRegistro = 'Ese correo ya está registrado.';
                    } else {
                        $hash = password_hash($password, PASSWORD_DEFAULT);
                        $id = usuario_registrar($nombreRegistro, $emailRegistro, $hash);
                        cuenta_iniciar_sesion([
                            'id' => $id, 'nombre' => $nombreRegistro,
                            'email' => $emailRegistro, 'rol' => 'cliente',
                        ]);
                        mensaje('Cuenta creada correctamente.');
                        ir('perfil.php');
                    }
                } catch (PDOException $ex) {
                    if ($ex->getCode() === '23000') {
                        $errorRegistro = 'Ese correo ya está registrado.';
                    } else {
                        error_log($ex->getMessage());
                        http_response_code(503);
                        $errorRegistro = 'No se pudo crear la cuenta. Revisá MySQL e intentá de nuevo.';
                    }
                }
            }
        } elseif ($accion === 'ingreso') {
            $emailIngreso = strtolower(campo_cuenta('email'));
            $password = is_string($_POST['password'] ?? null) ? $_POST['password'] : '';
            if (strlen($emailIngreso) > 190 || !filter_var($emailIngreso, FILTER_VALIDATE_EMAIL) || $password === '') {
                $errorIngreso = 'Ingresá un correo y una contraseña válidos.';
            } else {
                try {
                    $fila = usuario_buscar_por_email($emailIngreso);
                    if (!$fila || !password_verify($password, $fila['password_hash'])) {
                        $errorIngreso = 'Correo o contraseña incorrectos.';
                    } else {
                        cuenta_iniciar_sesion($fila);
                        mensaje('Bienvenido/a a Mercopan.');
                        ir('perfil.php');
                    }
                } catch (PDOException $ex) {
                    error_log($ex->getMessage());
                    http_response_code(503);
                    $errorIngreso = 'No se pudo iniciar sesión. Revisá MySQL e intentá de nuevo.';
                }
            }
        } else {
            http_response_code(400);
            $errorIngreso = 'Acción desconocida.';
        }
    }

    vista('cuenta', [
        'titulo' => 'Mi cuenta',
        'errorRegistro' => $errorRegistro,
        'errorIngreso' => $errorIngreso,
        'nombreRegistro' => $nombreRegistro,
        'emailRegistro' => $emailRegistro,
        'emailIngreso' => $emailIngreso,
    ]);
}

function cuenta_salir() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Allow: POST'); http_response_code(405); exit('Usá el botón Salir.');
    }
    verificar_csrf();

    // Se destruye la sesión completa, incluido carrito, usuario y token CSRF.
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $parametros = session_get_cookie_params();
        setcookie(session_name(), '', [
            'expires' => time() - 42000,
            'path' => $parametros['path'],
            'domain' => $parametros['domain'],
            'secure' => $parametros['secure'],
            'httponly' => $parametros['httponly'],
            'samesite' => $parametros['samesite'] ?? 'Lax',
        ]);
    }
    session_destroy();
    ir('cuenta.php');
}

function perfil_mostrar() {
    exigir_usuario();
    $pedidos = []; $errorHistorial = false;
    try { $pedidos = historial_del_usuario((int) usuario()['id']); }
    catch (PDOException $ex) { error_log($ex->getMessage()); http_response_code(503); $errorHistorial = true; }
    // HU04: nombre/correo se leen de la sesión. Se conserva HU09 de la entrega 3.
    vista('perfil', ['titulo' => 'Mi perfil', 'pedidos' => $pedidos, 'errorHistorial' => $errorHistorial]);
}
