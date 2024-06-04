<?php
use App\Controllers\ConexionDBController;
use App\Controllers\ArticuloController;
use App\Controllers\FacturaController;

require '../Controllers/conexionDBControllers.php';
require '../Controllers/articuloControllers.php';
require '../Controllers/facturaControllers.php';

$conexion = new ConexionDBController();
$articuloController = new ArticuloController($conexion);
$facturaController = new FacturaController($conexion);

$articulos = $articuloController->obtenerArticulos();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cliente = [
        'nombreCompleto' => $_POST['nombreCompleto'],
        'tipoDocumento' => $_POST['tipoDocumento'],
        'numeroDocumento' => $_POST['numeroDocumento'],
        'email' => $_POST['email'],
        'telefono' => $_POST['telefono']
    ];

    $productos = [];
    foreach ($_POST['articulos'] as $articulo) {
        $idArticulo = $articulo['idArticulo'];
        if (isset($articulos[$idArticulo], $articulo['cantidad'], $articulo['precioUnitario'])) {
            $productos[] = [
                'id' => $idArticulo,
                'cantidad' => $articulo['cantidad'],
                'precio' => $articulo['precioUnitario']
            ];
        }
    }

    $idFactura = $facturaController->crearFactura($cliente, $productos);

    if ($idFactura) {
        echo "La factura se ha creado correctamente con el ID: $idFactura";
    } else {
        echo "Error al crear la factura. Por favor, inténtalo de nuevo.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generar Factura - Tienda deportiva</title>
    <link rel="stylesheet" href="css/stylesFactura.css"> 
</head>
<body>
    <div class="container">
        <h1>Generar Factura</h1>
        <form method="post" action="" class="form">
            <h2>Información del Cliente</h2>
            <div class="form-group">
                <label for="nombreCompleto">Nombre Completo:</label>
                <input type="text" id="nombreCompleto" name="nombreCompleto" required>
            </div>
            <div class="form-group">
                <label for="tipoDocumento">Tipo de Documento:</label>
                <select id="tipoDocumento" name="tipoDocumento" required>
                    <option value="CC">Cédula de Ciudadanía</option>
                    <option value="TI">Tarjeta de Identidad</option>
                    <option value="CE">Cédula de Extranjería</option>
                </select>
            </div>
            <div class="form-group">
                <label for="numeroDocumento">Número de Documento:</label>
                <input type="text" id="numeroDocumento" name="numeroDocumento" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="telefono">Teléfono:</label>
                <input type="text" id="telefono" name="telefono" required>
            </div>
            <h2>Productos</h2>
            <div id="productos" class="productos">
                <div class="producto">
                    <label for="idArticulo">ID Artículo:</label>
                    <select id="articulo1" name="articulos[1][idArticulo]" onchange="updatePrice(this)" required>
                        <option value="">Seleccione un artículo</option>
                        <?php foreach($articulos as $articulo): ?>
                            <option value="<?= $articulo->getId() ?>" data-precio="<?= $articulo->getPrecio() ?>"><?= $articulo->getNombre() ?></option>
                        <?php endforeach; ?>
                    </select>
                    <label for="cantidad"><br><br>Cantidad:</label>
                    <input type="number" name="articulos[1][cantidad]" required>
                    <label for="precio">Precio Unitario:</label>
                    <input type="hidden" id="precioUnitario1" name="articulos[1][precioUnitario]">
                    <span id="precioUnitarioDisplay1">Seleccione un artículo</span>
                </div>
            </div>
            <button type="button" onclick="agregarProducto()" class="btn-agregar">Agregar Producto</button>
            <br><br>
            <input type="submit" value="Generar Factura" class="btn-generar">
        </form>
        <br>
        <a href="MenuViews.php" class="btn-menu">Volver al Menú</a>
    </div>

    <script>
        let contador = 2;

        function agregarProducto() {
            const productosDiv = document.getElementById('productos');
            const nuevoProducto = document.createElement('div');
            nuevoProducto.classList.add('producto');
            nuevoProducto.innerHTML = `
                <label for="idArticulo">ID Artículo:</label>
                <select id="articulo${contador}" name="articulos[${contador}][idArticulo]" onchange="updatePrice(this)" required>
                    <option value="">Seleccione un artículo</option>
                    <?php foreach($articulos as $articulo): ?>
                        <option value="<?= $articulo->getId() ?>" data-precio="<?= $articulo->getPrecio() ?>"><?= $articulo->getNombre() ?></option>
                    <?php endforeach; ?>
                </select>
                <label for="cantidad"><br><br>Cantidad:</label>
                <input type="number" name="articulos[${contador}][cantidad]" required>
                <label for="precio">Precio Unitario:</label>
                <input type="hidden" id="precioUnitario${contador}" name="articulos[${contador}][precioUnitario]">
                <span id="precioUnitarioDisplay${contador}">Seleccione un artículo</span>
            `;
            productosDiv.appendChild(nuevoProducto);
            contador++;
        }

        function updatePrice(selectElement) {
            const precio = selectElement.options[selectElement.selectedIndex].getAttribute('data-precio');
            const id = selectElement.id.replace('articulo', '');
            document.getElementById(`precioUnitario${id}`).value = precio;
            document.getElementById(`precioUnitarioDisplay${id}`).innerText = precio;
        }
    </script>
</body>
</html>
