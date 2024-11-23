<?php
include("../db/conexion.php");

// Obtener todos los productos
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $stmt = $conn->prepare("SELECT * FROM productos");
    $stmt->execute();
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($productos);
}

// Crear un producto
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"));
    $nombre = $data->nombre;
    $precio = $data->precio;
    $stock = $data->stock;

    $stmt = $conn->prepare("INSERT INTO productos (nombre, precio, stock) VALUES (?, ?, ?)");
    $stmt->execute([$nombre, $precio, $stock]);

    echo json_encode(["message" => "Producto creado exitosamente"]);
}

// Actualizar un producto
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    $data = json_decode(file_get_contents("php://input"));
    $id = $data->id;
    $stock = $data->stock;

    $stmt = $conn->prepare("UPDATE productos SET stock=? WHERE id=?");
    $stmt->execute([$stock, $id]);

    echo json_encode(["message" => "Producto actualizado exitosamente"]);
}
?>
