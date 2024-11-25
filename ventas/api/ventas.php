<?php
include("../db/conexion.php");

// Registrar una venta (POST)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"));
    $producto_id = $data->producto_id;
    $cantidad = $data->cantidad;
    $total = $data->total;

    try {
        // Iniciar transacción
        $conn->beginTransaction();

        // Insertar la venta en db_ventas
        $stmt = $conn->prepare("INSERT INTO ventas (producto_id, cantidad, total) VALUES (?, ?, ?)");
        $stmt->execute([$producto_id, $cantidad, $total]);

        // Actualizar el stock en db_ventas
        $stmtUpdate = $conn->prepare("UPDATE productos SET stock = stock - ? WHERE id = ?");
        $stmtUpdate->execute([$cantidad, $producto_id]);

        // Preparar datos para enviar al microservicio de Inventario
        $productoData = json_encode([
            'id' => $producto_id,
            'stock' => $cantidad // El stock en Inventario se reduce debido a la venta
        ]);

        // Realizar la llamada cURL al microservicio de Inventario
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://localhost:8080/api/actualizar_stock.php'); // URL del Inventario en puerto 8080
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $productoData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
        ));

        // Ejecutar cURL y capturar respuesta
        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new Exception("Error en cURL: " . curl_error($ch));
        }

        // Cerrar cURL
        curl_close($ch);

        // Validar respuesta del microservicio de Inventario
        $responseDecoded = json_decode($response, true);
        if (isset($responseDecoded['error'])) {
            throw new Exception("Error desde Inventario: " . $responseDecoded['error']);
        }

        // Confirmar la transacción
        $conn->commit();

        // Respuesta exitosa
        echo json_encode(["message" => "Venta registrada, stock actualizado en Ventas e Inventario"]);

    } catch (Exception $e) {
        // Revertir transacción en caso de error
        $conn->rollBack();
        echo json_encode(["error" => $e->getMessage()]);
    }
}
?>