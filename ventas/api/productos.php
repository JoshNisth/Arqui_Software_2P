<?php
include("../db/conexion.php");

// Crear un producto en Ventas (POST)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"));
    $nombre = $data->nombre;
    $precio = $data->precio;
    $stock = $data->stock;

    // Insertar el nuevo producto en la base de datos de Ventas
    $stmt = $conn->prepare("INSERT INTO productos (nombre, precio, stock) VALUES (?, ?, ?)");
    $stmt->execute([$nombre, $precio, $stock]);

    echo json_encode(["message" => "Producto agregado exitosamente en Ventas"]);
}

// Actualizar un producto en Ventas (PUT)
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    $data = json_decode(file_get_contents("php://input"));
    $producto_id = $data->producto_id;
    $stock = $data->cantidad;  // Esto representa el nuevo stock del producto

    // Verificar si el producto ya existe en la tabla de productos en Ventas
    $stmt = $conn->prepare("SELECT * FROM productos WHERE id = ?");
    $stmt->execute([$producto_id]);
    $producto = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($producto) {
        // Si el producto existe en la tabla de productos, actualizamos el stock
        $stmtUpdate = $conn->prepare("UPDATE productos SET stock = ? WHERE id = ?");
        $stmtUpdate->execute([$stock, $producto_id]);
        echo json_encode(["message" => "Stock de producto en Ventas actualizado"]);
    } else {
        // Si no existe, se puede insertar una nueva entrada (esto sería un error si es solo actualización)
        echo json_encode(["message" => "Producto no encontrado en Ventas"]);
    }
}
?>
