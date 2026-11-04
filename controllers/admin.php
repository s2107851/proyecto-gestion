<?php
if (!defined('APP_INICIADA')) { http_response_code(404); exit; }
function admin_mostrar() {
    exigir_admin(); // También protege todas las solicitudes POST.
    $seccion = (string) ($_GET['seccion'] ?? 'productos');
    if (!in_array($seccion, ['productos','clientes','pedidos'], true)) $seccion = 'productos';
    $error = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        verificar_csrf();
        try {
            $accion = $_POST['accion'] ?? '';
            if ($accion === 'guardar_producto') {
                $id = filter_var($_POST['id'] ?? '0', FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]);
                $nombre = trim((string) ($_POST['nombre'] ?? ''));
                $descripcion = trim((string) ($_POST['descripcion'] ?? ''));
                $categoria = (string) ($_POST['categoria'] ?? '');
                $precio = str_replace(',', '.', trim((string) ($_POST['precio'] ?? '')));
                $activo = isset($_POST['activo']) ? 1 : 0;
                if ($id === false || ($id > 0 && !admin_producto($id)) ||
                    $nombre === '' || mb_strlen($nombre) > 100 || mb_strlen($descripcion) > 255 ||
                    !in_array($categoria, ['Panes','Tortas','Postres'], true) ||
                    !preg_match('/^\d{1,8}(?:\.\d{1,2})?$/', $precio) || (float) $precio <= 0) {
                    $error = 'Revisá nombre, descripción, categoría y precio positivo (máximo dos decimales).';
                } else {
                    admin_guardar_producto($id, $nombre, $descripcion, $categoria, $precio, $activo);
                    mensaje('Producto guardado.'); ir('admin.php?seccion=productos');
                }
            } elseif ($accion === 'disponibilidad') {
                $id = filter_var($_POST['id'] ?? '', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
                $activo = $_POST['activo'] ?? '';
                if (!$id || !in_array($activo, ['0','1'], true) || !admin_producto($id)) {
                    $error = 'Producto inválido.';
                } else {
                    admin_cambiar_disponibilidad($id, (int) $activo);
                    mensaje('Disponibilidad actualizada.'); ir('admin.php?seccion=productos');
                }
            } elseif ($accion === 'estado_pedido') {
                $id = filter_var($_POST['id'] ?? '', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
                $estadoNuevo = (string) ($_POST['estado'] ?? '');
                if (!$id || !admin_cambiar_estado($id, $estadoNuevo)) {
                    $error = 'Cambio de estado no permitido o pedido inexistente.';
                } else {
                    mensaje('Estado actualizado.'); ir('admin.php?seccion=pedidos');
                }
            } else { http_response_code(400); $error = 'Acción desconocida.'; }
        } catch (PDOException $ex) {
            error_log($ex->getMessage()); http_response_code(503); $error = 'No fue posible guardar los cambios.';
        }
    } elseif ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        http_response_code(405); header('Allow: GET, POST'); exit('Método no permitido.');
    }
    $productos = []; $clientes = []; $pedidos = []; $productoEditar = null;
    $clienteElegido = null; $pedidoElegido = null; $detalleElegido = []; $totalCobrado = '0.00';
    $busqueda = substr(trim((string) ($_GET['buscar'] ?? '')), 0, 100);
    $estado = (string) ($_GET['estado'] ?? '');
    if (!in_array($estado, ['', 'pendiente', 'listo', 'retirado', 'cancelado'], true)) $estado = '';
    $fecha = (string) ($_GET['fecha'] ?? '');
    $fechaParseada = DateTimeImmutable::createFromFormat('!Y-m-d', $fecha);
    if ($fecha !== '' && (!$fechaParseada || $fechaParseada->format('Y-m-d') !== $fecha)) $fecha = '';
    try {
        if ($seccion === 'productos') {
            $productos = admin_productos();
            $editarId = filter_var($_GET['editar'] ?? '', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
            if ($editarId) $productoEditar = admin_producto($editarId);
        } elseif ($seccion === 'clientes') {
            $clientes = admin_clientes($busqueda);
            $clienteId = filter_var($_GET['cliente_id'] ?? '', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
            if ($clienteId) {
                $clienteElegido = admin_cliente($clienteId);
                if ($clienteElegido) $pedidos = admin_pedidos('', '', $clienteId);
            }
        } else {
            $pedidos = admin_pedidos($estado, $fecha);
            $totalCobrado = admin_total_cobrado();
            $pedidoId = filter_var($_GET['pedido_id'] ?? '', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
            if ($pedidoId) {
                $pedidoElegido = admin_pedido($pedidoId);
                if ($pedidoElegido) $detalleElegido = admin_detalle($pedidoId);
            }
        }
    } catch (PDOException $ex) {
        error_log($ex->getMessage()); http_response_code(503); $error = 'No se pudieron consultar los datos. Revisá MySQL.';
    }
    vista('admin', [
        'titulo'=>'Administración', 'seccion'=>$seccion, 'error'=>$error, 'productos'=>$productos,
        'clientes'=>$clientes, 'pedidos'=>$pedidos, 'productoEditar'=>$productoEditar,
        'clienteElegido'=>$clienteElegido, 'pedidoElegido'=>$pedidoElegido,
        'detalleElegido'=>$detalleElegido, 'totalCobrado'=>$totalCobrado,
        'busqueda'=>$busqueda, 'estado'=>$estado, 'fecha'=>$fecha
    ]);
}
