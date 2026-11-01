-- Proyecto Gestión · Esquema inicial para MySQL / MariaDB (XAMPP).
-- Crear una base nueva. No elimina bases ni tablas existentes.
CREATE DATABASE IF NOT EXISTS proyecto_gestion CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE proyecto_gestion;
CREATE TABLE IF NOT EXISTS usuarios (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(190) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    rol ENUM('cliente','admin') NOT NULL DEFAULT 'cliente',
    creado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS productos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion VARCHAR(255) NOT NULL,
    categoria ENUM('Panes','Tortas','Postres') NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    activo TINYINT(1) NOT NULL DEFAULT 1,
    CHECK (precio > 0)
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS pedidos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT UNSIGNED NOT NULL,
    total DECIMAL(12,2) NOT NULL,
    estado ENUM('pendiente','listo','retirado','cancelado') NOT NULL DEFAULT 'pendiente',
    pagado TINYINT(1) NOT NULL DEFAULT 0,
    token_confirmacion CHAR(64) NOT NULL UNIQUE,
    creado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    CHECK (total > 0)
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS detalle_pedido (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT UNSIGNED NOT NULL,
    producto_id INT UNSIGNED NOT NULL,
    nombre_producto VARCHAR(100) NOT NULL,
    cantidad SMALLINT UNSIGNED NOT NULL,
    precio_unitario DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id),
    FOREIGN KEY (producto_id) REFERENCES productos(id),
    CHECK (cantidad BETWEEN 1 AND 99),
    CHECK (precio_unitario > 0)
) ENGINE=InnoDB;
-- Datos ficticios exclusivamente para la demo. No son precios reales de Mercopan.
-- Solo se insertan si el catálogo está vacío. Reimportar no duplica los productos.
INSERT INTO productos (nombre,descripcion,categoria,precio)
SELECT nombre,descripcion,categoria,precio FROM (
 SELECT 'Pan de campo' nombre,'Una pieza de pan para compartir en la mesa.' descripcion,'Panes' categoria,120.00 precio
 UNION ALL SELECT 'Pan de molde','Una pieza, ideal para tostadas y sándwiches.','Panes',150.00
 UNION ALL SELECT 'Torta de chocolate','Torta entera con cobertura de chocolate.','Tortas',890.00
 UNION ALL SELECT 'Torta de vainilla','Torta entera de vainilla y dulce de leche.','Tortas',820.00
 UNION ALL SELECT 'Budín de limón','Un budín entero con glaseado de limón.','Postres',280.00
 UNION ALL SELECT 'Tarta de manzana','Una tarta entera de manzana.','Postres',450.00
) AS ejemplos WHERE NOT EXISTS (SELECT 1 FROM productos);
-- No hay usuarios ni contraseñas precargados. Se implementan en la entrega 1.
