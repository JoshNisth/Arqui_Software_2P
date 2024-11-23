fetch('../api/productos.php')
    .then(response => response.json())
    .then(data => {
        const productosDiv = document.getElementById('productos');
        data.forEach(producto => {
            const div = document.createElement('div');
            div.textContent = `${producto.nombre} - Precio: $${producto.precio} - Stock: ${producto.stock}`;
            productosDiv.appendChild(div);
        });
    });
