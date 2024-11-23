<?php
include("../db/conexion.php");

// Registrar una venta
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"));
    $producto_id = $data->producto_id;
    $cantidad = $data->cantidad;
    $total = $data->total;

    // Insertar la venta en db_ventas
    $stmt = $conn->prepare("INSERT INTO ventas (producto_id, cantidad, total) VALUES (?, ?, ?)");
    $stmt->execute([$producto_id, $cantidad, $total]);

    // Actualizar el stock en db_inventario usando la API del microservicio de Inventario
    $productoData = json_encode([
        'id' => $producto_id,
        'stock' => -$cantidad
    ]);

    // Actualización del stock en db_inventario a través de la API
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://localhost/Arqui_Software_2P/inventario/api/productos.php');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
    curl_setopt($ch, CURLOPT_POSTFIELDS, $productoData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
    ));
    $response = curl_exec($ch);
    curl_close($ch);

    echo json_encode(["message" => "Venta registrada y stock actualizado"]);
}
?>
