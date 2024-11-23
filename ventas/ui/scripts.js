document.getElementById('ventaForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const producto_id = document.getElementById('producto_id').value;
    const cantidad = document.getElementById('cantidad').value;
    const total = document.getElementById('total').value;

    const venta = {
        producto_id: producto_id,
        cantidad: cantidad,
        total: total
    };

    fetch('http://localhost/ventas/api/ventas.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(venta)
    })
    .then(response => response.json())
    .then(data => {
        if (data.message) {
            alert(data.message);
        } else if (data.error) {
            alert('Error: ' + data.error);
        }
    })
    .catch(error => console.error('Error:', error));
});
