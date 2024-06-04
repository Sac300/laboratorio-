<?php
require '../Controllers/ConexionDBControllers.php';
require '../Controllers/FacturaControllers.php';

use App\Controllers\ConexionDBController;
use App\Controllers\FacturaController;

$facturas = [];
$detalles = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conexion = new ConexionDBController();
    $facturaController = new FacturaController($conexion);
    $numeroDocumento = $_POST['numeroDocumento'];
    $tipoDocumento = $_POST['tipoDocumento'];

    $facturas = $facturaController->obtenerFacturasPorCliente($numeroDocumento, $tipoDocumento);
}

if (isset($_GET['referencia'])) {
    $conexion = new ConexionDBController();
    $facturaController = new FacturaController($conexion);
    $referencia = $_GET['referencia'];
    $detalles = $facturaController->obtenerDetallesFactura($referencia);
    
    header("Location: detalleFacturaViews.php?referencia=$referencia");
    exit();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facturas Previas - Tienda deportiva</title>
    <link rel="stylesheet" href="css/stylesFacturasPrevias.css">
</head>
<body>
    <div class="container">
        <h1>Facturas Previas</h1>
        <form method="post" action="" class="form">
            <h2>Buscar Facturas</h2>
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
            <input type="submit" value="Buscar" class="btn-buscar">
            <a href="menuViews.php" class="btn-regresar">Regresar al Menú</a>
        </form>

        <?php if (!empty($facturas)): ?>
            <h2>Facturas Encontradas</h2>
            <table class="facturas-table">
                <thead>
                    <tr>
                        <th>Referencia</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Descuento</th>
                        <th>Detalles</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($facturas as $factura): ?>
                        <tr>
                            <td><?= isset($factura['referencia']) ? $factura['referencia'] : '' ?></td>
                            <td><?= isset($factura['fecha']) ? $factura['fecha'] : '' ?></td>
                            <td><?= isset($factura['estado']) ? $factura['estado'] : '' ?></td>
                            <td><?= isset($factura['descuento']) ? $factura['descuento'] : '' ?></td>
                            <td><a href="?referencia=<?= isset($factura['referencia']) ? $factura['referencia'] : '' ?>">Ver Detalles</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
        
        
    </div>
</body>
</html>
