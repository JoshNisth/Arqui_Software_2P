<?php
include("../db/conexion.php");

// Permitir solicitudes desde otros orígenes (para evitar problemas de CORS)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Obtener todos los productos (GET)
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $stmt = $conn->prepare("SELECT * FROM productos");
    $stmt->execute();
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($productos);
}

// Crear un producto (POST)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"));
    $nombre = $data->nombre;
    $precio = $data->precio;
    $stock = $data->stock;

    // Insertar el nuevo producto en la base de datos de Inventario
    $stmt = $conn->prepare("INSERT INTO productos (nombre, precio, stock) VALUES (?, ?, ?)");
    $stmt->execute([$nombre, $precio, $stock]);

    // Obtener el ID del producto recién creado en Inventario
    $producto_id = $conn->lastInsertId();

    // Ahora agregar el producto a la tabla productos en Ventas (duplicada)
    $productoData = json_encode([
        'id' => $producto_id,
        'nombre' => $nombre,
        'precio' => $precio,
        'stock' => $stock
    ]);

    // Realizar la llamada cURL al microservicio de Ventas para agregar el producto
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://localhost:8081/api/productos.php'); // Actualizada para puerto 8081 (Ventas)
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS, $productoData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
    ));
    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo json_encode(["error" => "Error en cURL: " . curl_error($ch)]);
    } else {
        echo json_encode(["message" => "Producto creado exitosamente en Inventario y agregado a Ventas"]);
    }

    curl_close($ch);
}

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

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://localhost:8081/api/productos.php'); // Actualizada para puerto 8081 (Ventas)
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
    curl_setopt($ch, CURLOPT_POSTFIELDS, $productoData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
    ));
    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo json_encode(["error" => "Error en cURL: " . curl_error($ch)]);
    } else {
        echo json_encode(["message" => "Producto actualizado en Inventario y Ventas"]);
    }

    curl_close($ch);
}
?>
