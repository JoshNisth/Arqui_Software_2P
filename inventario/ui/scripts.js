// Obtener productos y cargarlos en la tabla
fetch('http://localhost/inventario/api/productos.php')
    .then(response => response.json())
    .then(data => {
        const tableBody = document.querySelector('#productos tbody');
        data.forEach(producto => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${producto.id}</td>
                <td>${producto.nombre}</td>
                <td>${producto.precio}</td>
                <td>${producto.stock}</td>
                <td><button onclick="actualizarStock(${producto.id}, ${producto.stock})">Actualizar Stock</button></td>
            `;
            tableBody.appendChild(row);
        });
    });

// Función para actualizar el stock (Ejemplo)
function actualizarStock(id, stock) {
    const nuevoStock = prompt("Nuevo stock:", stock);
    if (nuevoStock !== null) {
        fetch('http://localhost/inventario/api/productos.php', {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id, stock: nuevoStock })
        })
        .then(response => response.json())
        .then(data => alert(data.message));
    }
}
