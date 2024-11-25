<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

include("../db/conexion.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"));
    $producto_id = $data->producto_id ?? null;
    $cantidad = $data->cantidad ?? null;

    if (!$producto_id || !$cantidad || $cantidad <= 0) {
        echo json_encode(["error" => "Datos inválidos. Verifique el producto y la cantidad."]);
        exit;
    }

    try {
        // Iniciar transacción en la base de datos de Ventas
        $conn->beginTransaction();

        // Verificar stock en la base de datos de Ventas
        $stmt = $conn->prepare("SELECT stock FROM productos WHERE id = ?");
        $stmt->execute([$producto_id]);
        $producto = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$producto) {
            throw new Exception("Producto no encontrado en Ventas.");
        }

        if ($producto['stock'] < $cantidad) {
            throw new Exception("Stock insuficiente en Ventas.");
        }

        // Actualizar el stock en Ventas
        $nuevo_stock_ventas = $producto['stock'] - $cantidad;
        $stmt = $conn->prepare("UPDATE productos SET stock = ? WHERE id = ?");
        $stmt->execute([$nuevo_stock_ventas, $producto_id]);

        // Registrar la venta
        $stmt = $conn->prepare("INSERT INTO ventas (producto_id, cantidad) VALUES (?, ?)");
        $stmt->execute([$producto_id, $cantidad]);

        // Llamar al microservicio de Inventario para actualizar el stock
        $ventaData = json_encode(['id' => $producto_id, 'stock' => $nuevo_stock_ventas]);
        $ch = curl_init('http://localhost/Arqui_Software_2P/inventario/api/actualizar_stock.php');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $ventaData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code !== 200) {
            throw new Exception("Error al actualizar el stock en Inventario.");
        }

        // Confirmar transacción
        $conn->commit();
        echo json_encode(["message" => "Venta registrada y stock actualizado correctamente."]);
    } catch (Exception $e) {
        $conn->rollBack();
        echo json_encode(["error" => $e->getMessage()]);
    }
}
?>
