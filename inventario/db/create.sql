-- Crear la base de datos para Inventario
CREATE DATABASE IF NOT EXISTS db_inventario;

-- Usar la base de datos db_inventario
USE db_inventario;

-- Crear la tabla productos en db_inventario
CREATE TABLE IF NOT EXISTS productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    stock INT NOT NULL
);

-- Insertar productos de ejemplo
INSERT INTO productos (nombre, precio, stock) VALUES
('Producto A', 10.50, 100),
('Producto B', 20.00, 50),
('Producto C', 15.30, 200);
