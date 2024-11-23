-- Crear la base de datos para el inventario
CREATE DATABASE IF NOT EXISTS db_inventario;

-- Usar la base de datos de inventario
USE db_inventario;

-- Crear la tabla productos
CREATE TABLE IF NOT EXISTS productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    stock INT NOT NULL
);

-- Insertar datos de ejemplo en la tabla productos
INSERT INTO productos (nombre, precio, stock) VALUES
('Producto A', 10.50, 100),
('Producto B', 20.00, 50),
('Producto C', 15.30, 200);
