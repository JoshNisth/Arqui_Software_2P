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
    $cantidad = $data->cantidad;

    // Verificar si el producto ya existe en la tabla de ventas
    $stmt = $conn->prepare("SELECT * FROM ventas WHERE producto_id = ?");
    $stmt->execute([$producto_id]);
    $venta = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($venta) {
        // Si el producto ya está registrado en ventas, actualizamos la cantidad
        $stmtUpdate = $conn->prepare("UPDATE ventas SET cantidad = ? WHERE producto_id = ?");
        $stmtUpdate->execute([$cantidad, $producto_id]);
        echo json_encode(["message" => "Stock de ventas actualizado"]);
    } else {
        // Si no existe, se puede insertar una nueva entrada (opcional)
        $stmtInsert = $conn->prepare("INSERT INTO ventas (producto_id, cantidad) VALUES (?, ?)");
        $stmtInsert->execute([$producto_id, $cantidad]);
        echo json_encode(["message" => "Producto agregado a ventas"]);
    }
}
?>
