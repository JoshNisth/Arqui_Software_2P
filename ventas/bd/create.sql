-- Crear la base de datos para las ventas
CREATE DATABASE IF NOT EXISTS db_ventas;

-- Usar la base de datos de ventas
USE db_ventas;

-- Crear la tabla ventas
CREATE TABLE IF NOT EXISTS ventas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    producto_id INT NOT NULL,
    cantidad INT NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (producto_id) REFERENCES db_inventario.productos(id) ON DELETE CASCADE
);

-- Insertar datos de ejemplo en la tabla ventas
INSERT INTO ventas (producto_id, cantidad, total) VALUES
(1, 2, 21.00),
(2, 1, 20.00),
(3, 5, 76.50);
