function registrarVenta() {
    const productoId = parseInt(document.getElementById('producto_id').value);
    const cantidad = parseInt(document.getElementById('cantidad').value);

    if (!productoId || !cantidad || cantidad <= 0) {
        alert("Datos inválidos. Verifique el producto y la cantidad.");
        return;
    }

    fetch('http://localhost/Arqui_Software_2P/ventas/api/ventas.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ producto_id: productoId, cantidad: cantidad })
    })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert("Error: " + data.error);
            } else {
                alert(data.message);
                cargarProductos(); // Actualizar la lista de productos
            }
        })
        .catch(error => console.error('Error al registrar la venta:', error));
}
