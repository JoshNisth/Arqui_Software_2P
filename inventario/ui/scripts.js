// Mostrar el formulario para agregar un producto
function mostrarFormulario() {
    document.getElementById('formulario').style.display = 'block';
}

// Función para agregar un producto
function agregarProducto() {
    const nombre = document.getElementById('nombre').value;
    const precio = document.getElementById('precio').value;
    const stock = document.getElementById('stock').value;

    // Validar que los campos no estén vacíos
    if (nombre && precio && stock) {
        // Enviar el producto al backend para guardarlo en la base de datos
        fetch('http://localhost/Arqui_Software_2P/inventario/api/productos.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ nombre, precio, stock })
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            if (data.message === "Producto creado exitosamente") {
                // Recargar la lista de productos
                cargarProductos();
            }
        })
        .catch(error => console.error('Error al agregar el producto:', error));
    } else {
        alert("Todos los campos son obligatorios.");
    }
}

// Función para cargar los productos en la tabla
function cargarProductos() {
    fetch('http://localhost/Arqui_Software_2P/inventario/api/productos.php')
        .then(response => response.json())
        .then(data => {
            const tableBody = document.querySelector('#productos tbody');
            tableBody.innerHTML = ''; // Limpiar la tabla antes de agregar los productos

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
        })
        .catch(error => console.error('Error al cargar los productos:', error));
}

// Cargar los productos al cargar la página
window.onload = cargarProductos;


function actualizarStock(id, stock) {
    const nuevoStock = prompt("Nuevo stock:", stock);
    if (nuevoStock !== null) {
        // 1. Actualizar el stock en el microservicio de Inventario
        fetch('http://localhost/Arqui_Software_2P/inventario/api/productos.php', {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id, stock: nuevoStock })
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);

            // 2. Si la actualización en Inventario es exitosa, actualizar en Ventas
            actualizarStockEnVentas(id, nuevoStock);
        })
        .catch(error => console.error('Error en Inventario:', error));
    }
}

// Función para actualizar el stock en el microservicio de Ventas
function actualizarStockEnVentas(id, stock) {
    console.log("Se supone que actualiza ventas descomentar cuando ya este ventas api")
    /*
    fetch('http://localhost/Arqui_Software_2P/ventas/api/actualizar_ventas.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: id, stock: stock })
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
    })
    .catch(error => console.error('Error en Ventas:', error));*/
}
