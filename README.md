# Arqui_Software_2P

Se desarrolló un sistema de gestión de inventarios y ventas utilizando una arquitectura de microservicios. La funcionalidad principal incluye la gestión independiente de productos en un microservicio de Inventario, mientras que las operaciones de registro de ventas y consulta de productos se gestionan desde un microservicio de Ventas. Ambos servicios cuentan con su propia base de datos y realizan comunicación desacoplada mediante consultas independientes, asegurando que los sistemas puedan funcionar sin dependencias directas.

## Microservicio de Inventario
El microservicio de Inventario permite realizar operaciones CRUD sobre los productos. Estas operaciones incluyen la creación, actualización  y listado de productos disponibles en la base de datos de Inventario. Cada producto tiene un nombre, un precio y un stock que representa la cantidad disponible. Este microservicio es responsable del control del stock real y la disponibilidad de los productos. Se conecta únicamente a su propia base de datos y expone una interfaz para la gestión administrativa del inventario.

## Microservicio de Ventas
El microservicio de Ventas gestiona el registro de las ventas realizadas. Al registrar una venta, verifica la disponibilidad de productos y calcula el total de la transacción. Además, mantiene una tabla independiente de productos en su propia base de datos, que se sincroniza parcialmente con los datos del microservicio de Inventario. Este microservicio permite registrar la cantidad vendida, actualizar los registros de ventas y descontar la cantidad vendida del stock en Inventario, asegurando la coherencia en ambas bases de datos.

## Herramientas Utilizadas
- HTML: Utilizado para estructurar las interfaces de usuario de ambos microservicios. Se diseñaron formularios y vistas para la gestión del inventario y el registro de ventas.
- CSS: Empleado para estilizar las páginas web, asegurando una presentación visual adecuada y mejorando la experiencia de usuario.
- PHP: Utilizado como lenguaje de programación del backend para implementar la lógica de los microservicios. Permite conectar y manipular las bases de datos, así como manejar las interacciones de los formularios.
- MySQL: Utilizado como sistema de gestión de bases de datos. Se crearon dos bases de datos independientes (una para Inventario y otra para Ventas), asegurando la separación y el desacoplamiento entre los microservicios.

## Autores:
- [AndyLaffertt](https://github.com/AndyLaffertt)
- [Ansc0307](https://github.com/Ansc0307)
- [DAGod](https://github.com/inaDAGod)
- [JoshNisth](https://github.com/JoshNisth)


  
