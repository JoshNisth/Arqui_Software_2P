<?php
include("../db/conexion.php");

// Obtener producto por ID
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT id, nombre, precio FROM productos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);
    $producto = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($producto) {
        echo json_encode($producto);
    } else {
        echo json_encode(["error" => "Producto no encontrado"]);
    }
    exit;
}

// Crear un producto en Ventas (POST)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    if (isset($data->id, $data->nombre, $data->precio, $data->stock)) {
        $id = $data->id;
        $nombre = $data->nombre;
        $precio = $data->precio;
        $stock = $data->stock;

        try {
            // Insertar el nuevo producto en la base de datos de Ventas
            $stmt = $conn->prepare("INSERT INTO productos (id, nombre, precio, stock) VALUES (?, ?, ?, ?)");
            $stmt->execute([$id, $nombre, $precio, $stock]);

            echo json_encode(["message" => "Producto agregado exitosamente en Ventas"]);
        } catch (Exception $e) {
            echo json_encode(["error" => "Error al agregar producto: " . $e->getMessage()]);
        }
    } else {
        echo json_encode(["error" => "Datos incompletos"]);
    }
}

// Actualizar un producto en Ventas (PUT)
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    $data = json_decode(file_get_contents("php://input"));

    if (isset($data->producto_id, $data->cantidad)) {
        $producto_id = $data->producto_id;
        $stock = $data->cantidad;  // Esto representa el nuevo stock del producto

        try {
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
                echo json_encode(["message" => "Producto no encontrado en Ventas"]);
            }
        } catch (Exception $e) {
            echo json_encode(["error" => "Error al actualizar producto: " . $e->getMessage()]);
        }
    } else {
        echo json_encode(["error" => "Datos incompletos"]);
    }
}

?>