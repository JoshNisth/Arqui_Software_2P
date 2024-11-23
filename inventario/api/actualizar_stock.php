<?php
include("../db/conexion.php");

// Actualizar un producto (PUT)
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    $data = json_decode(file_get_contents("php://input"));
    $id = $data->id;
    $stock = $data->stock;

    // Actualizar el stock del producto en la base de datos de Inventario
    $stmt = $conn->prepare("UPDATE productos SET stock=? WHERE id=?");
    $stmt->execute([$stock, $id]);

    // Llamar a la API de Ventas para actualizar el stock en la base de datos de Ventas
    $productoData = json_encode([
        'producto_id' => $id,
        'cantidad' => $stock
    ]);


    echo json_encode(["message" => "Producto actualizado en Inventario"]);
}
?>
