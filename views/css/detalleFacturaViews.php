<?php
require '../Controllers/FacturaControllers.php';
require '../Controllers/conexionDBControllers.php';

use App\Controllers\FacturaController;
use App\Controllers\ConexionDBController;

if (isset($_GET['referencia'])) {
    
    $conexion = new ConexionDBController(); 
    $facturaController = new FacturaController($conexion);

    
    $referencia = $_GET['referencia'];

    
    $factura = $facturaController->obtenerFacturaPorReferencia($referencia);


    $detalles = $facturaController->obtenerDetallesFactura($referencia);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Factura - Tienda deportiva</title>
    <link rel="stylesheet" href="css/stylesDetalleFacturas.css"> 
</head>
<body>
    <div class="container">
        <h2>Detalle de Factura</h2>
        <div>
            <?php if(isset($factura)): ?>
                <h3>Número de referencia: <?php echo htmlspecialchars($factura['referencia']); ?></h3>
                <p>Fecha de compra: <?php echo htmlspecialchars($factura['fecha']); ?></p>
                <p>Estado de la factura: <?php echo htmlspecialchars($factura['estado']); ?></p>
                <h3>Información del Cliente</h3>
                <p>Nombre Completo: <?php echo isset($factura['nombreCompleto']) ? htmlspecialchars($factura['nombreCompleto']) : ''; ?></p>
                <p>Tipo de Documento: <?php echo isset($factura['tipoDocumento']) ? htmlspecialchars($factura['tipoDocumento']) : ''; ?></p>
                <p>Número de Documento: <?php echo isset($factura['numeroDocumento']) ? htmlspecialchars($factura['numeroDocumento']) : ''; ?></p>
                <p>Teléfono: <?php echo isset($factura['telefono']) ? htmlspecialchars($factura['telefono']) : ''; ?></p>
                <p>Email: <?php echo isset($factura['email']) ? htmlspecialchars($factura['email']) : ''; ?></p>
                <h3>Lista de Productos</h3>
                <?php if (!empty($detalles)): ?>
                    <table class="detalles-table">
                        <thead>
                            <tr>
                                <th>Nombre del Producto</th>
                                <th>Precio Unitario</th>
                                <th>Cantidad</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($detalles as $detalle): ?>
                                <tr>
                                    <td><?php echo isset($detalle['nombreProducto']) ? htmlspecialchars($detalle['nombreProducto']) : ''; ?></td>
                                    <td><?php echo isset($detalle['precioUnitario']) ? htmlspecialchars($detalle['precioUnitario']) : ''; ?></td>
                                    <td><?php echo isset($detalle['cantidad']) ? htmlspecialchars($detalle['cantidad']) : ''; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No hay detalles de factura disponibles.</p>
                <?php endif; ?>
                <h3>Descuento y Total</h3>
                <p>Descuento: <?php echo isset($factura['descuento']) ? htmlspecialchars($factura['descuento']) . '%' : '0%'; ?></p>
                <p>Total a pagar: <?php echo isset($factura['total']) ? htmlspecialchars($factura['total']) : ''; ?></p>
            <?php else: ?>
                <p>Detalle de factura no encontrado.</p>
            <?php endif; ?>
        </div>
        <a href="menuViews.php" class="boton" >Volver al Menú</a>
        <a href="facturasPreviasViews.php" class="boton">Volver a Facturas Previas</a>
    </div>
</body>
</html>
