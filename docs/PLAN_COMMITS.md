# Tres entregas de implementación

## Conteo del historial

Los dos commits anteriores se conservan: `2a48192` (creación) y `6c71732` (alcance). Esta base agrega un tercer commit de preparación, sin atribuirlo a los compañeros. **Implementación del equipo: 0 de 3 entregas realizadas.** Con las tres entregas habrá seis commits totales, salvo cambios adicionales. No se reescribe el historial para aparentar otro conteo.

## Entrega 1 · Santiago Beasley

Rol confirmado: desarrollador y Scrum Master. Alcance: **HU01, HU02, HU03 y HU04**.

- Completar `cuenta.php`: registro e inicio de sesión con formularios POST y CSRF.
- Validar nombre, correo único y contraseña de al menos ocho caracteres en PHP.
- Usar `password_hash` y `password_verify`; el registro siempre crea `cliente`.
- Guardar en `$_SESSION['usuario']` únicamente id, nombre, email y rol; regenerar identificador de sesión.
- Implementar `salir.php` por POST, destruir sesión y vaciar carrito al salir. Al ingresar, conservar el carrito invitado para que se pueda terminar la compra.
- Completar datos en `perfil.php`; agregar el botón de cerrar sesión en la cabecera.
- Documentar cómo asignar el rol admin a una cuenta ya registrada desde phpMyAdmin, utilizando su correo y verificando que se actualizó una sola cuenta.
- Probar cuenta válida, correo duplicado, contraseña errónea, usuario normal sin acceso admin, cierre y cambio entre cuentas.

Mensaje propuesto: `feat: HU01-HU04 registro, sesion y perfil de clientes`

## Entrega 2 · André de Mora

Responsabilidad propuesta: desarrollador de interfaz y experiencia de compra. Alcance: **HU05, HU06, HU07 y HU08**. Las tres primeras tienen una base funcional; esta entrega las integra y revalida junto con la confirmación.

- Revisar catálogo, filtros y controles existentes. No reemplazar el carrito de sesión por otro en JavaScript.
- Cambiar el botón deshabilitado por un formulario POST hacia `confirmar.php` con CSRF y un token único de confirmación.
- Exigir cuenta cliente, carrito no vacío y productos activos. Releer los precios desde MySQL.
- Conservar en sesión el resumen mostrado; si cambian productos, precios o cantidades antes de confirmar, volver al carrito para revisar el nuevo resumen.
- Insertar pedido y detalles en una transacción. Conservar nombre y precio unitario históricos.
- Usar `token_confirmacion` único para impedir pedidos duplicados por doble envío.
- Vaciar el carrito solo después de confirmar la transacción. Mostrar número, total, retiro y pago en el local.
- Probar doble clic, carrito vacío, precio cambiado, producto desactivado y fallo de guardado sin datos parciales.

Mensaje propuesto: `feat: HU05-HU08 catalogo, carrito y confirmacion de pedidos`

## Entrega 3 · Santiago Erramouspe

Responsabilidad propuesta: desarrollador de administración e integración de base de datos. Alcance: **HU09, HU10, HU11 y HU12**.

- Completar `admin.php` con tres apartados: productos, clientes y pedidos; verificar admin en cada GET y POST.
- Productos: crear, editar precio/descripción/categoría y activar/desactivar. No borrar productos referenciados en pedidos.
- Clientes: listar y buscar por nombre/correo, consultar pedidos; nunca mostrar hashes.
- Pedidos: ver detalle, filtrar por fecha/estado y pasar de pendiente a listo y retirado, o cancelar desde pendiente/listo. Registrar el pago al retirar. No contar cancelados como ventas cobradas.
- Completar historial propio dentro de `perfil.php`, filtrando por el id de la sesión.
- Probar aislamiento entre dos clientes, acceso directo sin permisos, conservación de precios históricos y estados inválidos.
- Completar la evidencia de pruebas de las doce HU y preparar la demo conjunta.

Mensaje propuesto: `feat: HU09-HU12 historial y administracion de Mercopan`

## Cómo ejecutar cada entrega

Trabajar en orden 1 → 2 → 3 para evitar dependencias y conflictos. Antes de empezar cada parte:

```bash
git pull --ff-only
git status
```

Después de implementar y probar, cada integrante revisa los cambios y los confirma desde su propia cuenta configurada:

```bash
git diff
git add <archivos-de-su-entrega>
git commit -m "mensaje correspondiente a su entrega"
git push
git log --oneline --reverse
```

`<archivos-de-su-entrega>` es un marcador que debe sustituirse por los nombres reales. No copiarlo literalmente. No incluir contraseñas, configuración local, capturas con datos personales ni bases reales. No cambiar la autoría de otra persona.

## Evidencia por entrega

Registrar HU, escenario, resultado esperado, resultado obtenido, fecha, responsable, hash real del commit y evidencia. El estado «hecha» exige pruebas aprobadas. El documento inicial diferencia lo funcional de la base y lo pendiente; cada integrante actualizará sus resultados junto con el código.
