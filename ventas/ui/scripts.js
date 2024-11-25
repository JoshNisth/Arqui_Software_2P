// Escuchar el evento cuando se introduce un producto ID
document.getElementById('producto_id').addEventListener('input', function () {
    const producto_id = this.value;

    if (producto_id) {
        fetch(`http://localhost/Arqui_Software_2P/ventas/api/productos.php?id=${producto_id}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    Swal.fire('Error', data.error, 'error');
                    limpiarCamposProducto();
                } else {
                    document.getElementById('nombre_producto').value = data.nombre || '';
                    document.getElementById('precio_producto').value = data.precio || '';
                }
            })
            .catch(error => {
                console.error('Error al obtener el producto:', error);
                limpiarCamposProducto();
            });
    } else {
        limpiarCamposProducto();
    }
});

// Escuchar el evento cuando se introduce una cantidad para calcular el total
document.getElementById('cantidad').addEventListener('input', function () {
    const cantidad = parseFloat(this.value);
    const precio = parseFloat(document.getElementById('precio_producto').value);

    if (!isNaN(cantidad) && !isNaN(precio)) {
        document.getElementById('total').value = (cantidad * precio).toFixed(2);
    } else {
        document.getElementById('total').value = '';
    }
});

// Manejar el evento submit para registrar la venta
document.getElementById('ventaForm').addEventListener('submit', function (event) {
    event.preventDefault();

    const producto_id = document.getElementById('producto_id').value;
    const cantidad = document.getElementById('cantidad').value;
    const total = document.getElementById('total').value;

    // Validar que los campos necesarios estén completos
    if (!producto_id || !cantidad || !total) {
        Swal.fire('Error', 'Todos los campos son obligatorios', 'error');
        return;
    }

    const venta = {
        producto_id: producto_id,
        cantidad: cantidad,
        total: total
    };

    Swal.fire({
        title: '¿Registrar Venta?',
        text: `Producto ID: ${producto_id}, Cantidad: ${cantidad}, Total: ${total}`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, registrar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('http://localhost/Arqui_Software_2P/ventas/api/ventas.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(venta)
            })
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        Swal.fire('Éxito', data.message, 'success');
                        limpiarCamposProducto();
                    } else if (data.error) {
                        Swal.fire('Error', data.error, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error', 'Ocurrió un error al registrar la venta', 'error');
                });
        }
    });
});

// Función para limpiar los campos del formulario
function limpiarCamposProducto() {
    document.getElementById('nombre_producto').value = '';
    document.getElementById('precio_producto').value = '';
    document.getElementById('total').value = '';
    document.getElementById('cantidad').value = '';
}
