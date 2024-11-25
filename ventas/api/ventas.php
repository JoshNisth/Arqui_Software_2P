<?php
include("../db/conexion.php");

// Registrar una venta (POST)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"));
    $producto_id = $data->producto_id;
    $cantidad = $data->cantidad;
    $total = $data->total;

    // Insertar la venta en db_ventas
    $stmt = $conn->prepare("INSERT INTO ventas (producto_id, cantidad, total) VALUES (?, ?, ?)");
    $stmt->execute([$producto_id, $cantidad, $total]);

    // Actualizar el stock en db_ventas
    $stmtUpdate = $conn->prepare("UPDATE productos SET stock = stock - ? WHERE id = ?");
    $stmtUpdate->execute([$cantidad, $producto_id]);

    // Llamar a la API de Inventario para actualizar el stock
    $productoData = json_encode([
        'id' => $producto_id,
        'stock' => $cantidad  // El stock en Inventario se reduce debido a la venta
    ]);

    // Realización de la llamada cURL al microservicio de Inventario
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://localhost/Arqui_Software_2P/inventario/api/actualizar_stock.php');  // URL de la API de Inventario
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
    curl_setopt($ch, CURLOPT_POSTFIELDS, $productoData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
    ));
    $response = curl_exec($ch);
    curl_close($ch);

    echo json_encode(["message" => "Venta registrada, stock actualizado en Ventas e Inventario"]);
}
?>
