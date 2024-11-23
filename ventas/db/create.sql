-- Crear la base de datos para Ventas
CREATE DATABASE IF NOT EXISTS db_ventas;

-- Usar la base de datos db_ventas
USE db_ventas;

-- Crear la tabla ventas en db_ventas
CREATE TABLE IF NOT EXISTS ventas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    producto_id INT NOT NULL,
    cantidad INT NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Crear la tabla productos en db_ventas (duplicada en esta BD)
CREATE TABLE IF NOT EXISTS productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    stock INT NOT NULL
);

-- Insertar productos de ejemplo en db_ventas
INSERT INTO productos (nombre, precio, stock) VALUES
('Producto A', 10.50, 100),
('Producto B', 20.00, 50),
('Producto C', 15.30, 200);
