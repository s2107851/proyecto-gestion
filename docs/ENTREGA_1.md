# Entrega 1 — HU01 a HU04 · Cuentas y perfil

**Responsable previsto:** Santiago Beasley. La autoría de Git corresponde a quien realmente revise y realice el commit.

## Alcance implementado en esta entrega

- **HU01:** registro con nombre (máximo 100 caracteres), correo válido y único (máximo 190) y contraseña (de 8 a 72 bytes por compatibilidad con bcrypt); `password_hash`, nunca contraseña en claro; el rol es siempre `cliente`.
- **HU02:** inicio con `password_verify`, regeneración de ID de sesión y conservación del carrito invitado. Sesión: `id`, `nombre`, `email`, `rol` (sin hash).
- **HU03:** salir por POST+CSRF; se destruyen sesión y cookie de sesión; se vacía el carrito.
- **HU04:** perfil protegido que muestra nombre y correo de la sesión. Se conserva el historial de la entrega 3 y su filtro por usuario.
- Interfaz coherente con la web existente. No se alteran tablas ni se agregan paquetes.

## Cómo habilitar el primer administrador en XAMPP

1. Registrá una cuenta con el formulario normal desde `cuenta.php`.
2. Abrí phpMyAdmin, seleccioná `proyecto_gestion` y entrá a SQL.
3. Reemplazá el correo ficticio por el correo **exacto de esa cuenta** y ejecutá:

```sql
UPDATE usuarios SET rol = 'admin'
WHERE email = 'admin-ejemplo@correo.test' AND rol = 'cliente';
```

4. phpMyAdmin debe informar **1 fila afectada**. Comprobá:

```sql
SELECT id, nombre, email, rol FROM usuarios
WHERE email = 'admin-ejemplo@correo.test';
```

5. Salí de la sesión y volvé a entrar para cargar el nuevo rol. El público no puede registrarse como administrador.

**No editar el SQL del repositorio para crear administradores por defecto. No publicar contraseñas.**

## Validación manual a ejecutar antes del commit

| HU | Acción | Resultado esperado |
|---|---|---|
| 01 | Registrar cuenta válida | Se crea 1 fila con rol `cliente` y hash; abre perfil |
| 01 | Repetir correo / enviar `rol=admin` por POST | Correo duplicado rechazado; la cuenta nunca adquiere rol admin |
| 01 | Enviar campo vacío o contraseña de 7 bytes | Se rechaza sin insertar fila |
| 02 | Ingresar con correo y contraseña correctos | Redirección a perfil; carrito previo conservado |
| 02 | Contraseña incorrecta | Mensaje de error genérico; no inicia sesión |
| 02 | Abrir admin.php con usuario cliente | HTTP 403 |
| 03 | Cerrar sesión con botón Salir | No hay usuario ni carrito; perfil exige inicio |
| 03 | Solicitud GET a salir.php / POST sin CSRF | HTTP 405 / HTTP 403 |
| 04 | Crear dos cuentas e ingresar alternadamente | Cada perfil muestra sus datos; nunca hashes ni pedidos del otro |

**Importante:** estos son casos de prueba pendientes de ejecutar, no evidencia de que hayan pasado. Registrar fecha, entorno, resultado real, responsable y hash del commit en la documentación del equipo.

## Límites

No se implementa edición de perfil, recuperación de contraseña, verificación por correo, pagos online ni confirmación de pedidos. **HU08 todavía corresponde a André de Mora**. No modificar `views/carrito.php` ni `controllers/carrito.php` en esta entrega.
