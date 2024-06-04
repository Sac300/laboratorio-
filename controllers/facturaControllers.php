<?php

namespace App\Controllers;

use Exception;

class FacturaController
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    private function obtenerIdCliente($cliente)
    {
        $sql = "SELECT id FROM clientes WHERE numeroDocumento = ? AND tipoDocumento = ?";
        $stmt = $this->conexion->getConnection()->prepare($sql);

        if ($stmt === false) {
            throw new Exception('Error al preparar la consulta del cliente: ' . $this->conexion->getConnection()->error);
        }

        $stmt->bind_param("ss", $cliente['numeroDocumento'], $cliente['tipoDocumento']);
        $stmt->execute();
        $result = $stmt->get_result();
        $clienteData = $result->fetch_assoc();
        $stmt->close();

        if ($clienteData) {
            return $clienteData['id'];
        } else {
            $sqlInsert = "INSERT INTO clientes (nombreCompleto, tipoDocumento, numeroDocumento, email, telefono) VALUES (?, ?, ?, ?, ?)";
            $stmtInsert = $this->conexion->getConnection()->prepare($sqlInsert);

            if ($stmtInsert === false) {
                throw new Exception('Error al preparar la inserción del cliente: ' . $this->conexion->getConnection()->error);
            }

            $stmtInsert->bind_param("sssss", $cliente['nombreCompleto'], $cliente['tipoDocumento'], $cliente['numeroDocumento'], $cliente['email'], $cliente['telefono']);
            if ($stmtInsert->execute() === false) {
                throw new Exception('Error al insertar el cliente: ' . $stmtInsert->error);
            }

            $idCliente = $stmtInsert->insert_id;
            $stmtInsert->close();
            return $idCliente;
        }
    }

    public function crearFactura($cliente, $productos)
    {
        foreach ($productos as $producto) {
            if (!is_array($producto) || !isset($producto['cantidad']) || !isset($producto['precio']) || !isset($producto['id'])) {
                throw new Exception('Error: cada producto debe ser un array con las claves "cantidad", "precio" y "id".');
            }

            $sqlArticulo = "SELECT id FROM articulos WHERE id = ?";
            $stmtArticulo = $this->conexion->getConnection()->prepare($sqlArticulo);
            if ($stmtArticulo === false) {
                throw new Exception('Error al preparar la consulta del artículo: ' . $this->conexion->getConnection()->error);
            }
            $stmtArticulo->bind_param("i", $producto['id']);
            $stmtArticulo->execute();
            $resultArticulo = $stmtArticulo->get_result();
            if ($resultArticulo->num_rows === 0) {
                throw new Exception('Error: el artículo con id ' . $producto['id'] . ' no existe.');
            }
            $stmtArticulo->close();
        }

        $idCliente = $this->obtenerIdCliente($cliente);

        $referencia = uniqid('REF');

        $total = 0;
        foreach ($productos as $producto) {
            $total += $producto['precio'] * $producto['cantidad'];
        }

        $descuento = '0';
        if ($total > 200000) {
            $descuento = '10';
        } elseif ($total > 100000) {
            $descuento = '5';
        }

        $sqlFactura = "INSERT INTO facturas (referencia, fecha, idCliente, estado, descuento) VALUES (?, NOW(), ?, 'Pagada', ?)";
        $stmtFactura = $this->conexion->getConnection()->prepare($sqlFactura);

        if ($stmtFactura === false) {
            throw new Exception('Error al preparar la inserción de la factura: ' . $this->conexion->getConnection()->error);
        }

        $stmtFactura->bind_param("sis", $referencia, $idCliente, $descuento);

        if ($stmtFactura->execute() === false) {
            throw new Exception('Error al insertar la factura: ' . $stmtFactura->error);
        }

        $sqlDetalle = "INSERT INTO detalleFacturas (cantidad, precioUnitario, idArticulo, referenciaFactura) VALUES (?, ?, ?, ?)";
        $stmtDetalle = $this->conexion->getConnection()->prepare($sqlDetalle);

        if ($stmtDetalle === false) {
            throw new Exception('Error al preparar la inserción del detalle de la factura: ' . $this->conexion->getConnection()->error);
        }

        foreach ($productos as $producto) {
            $stmtDetalle->bind_param("iids", $producto['cantidad'], $producto['precio'], $producto['id'], $referencia);
            if ($stmtDetalle->execute() === false) {
                throw new Exception('Error al insertar el detalle de la factura: ' . $stmtDetalle->error);
            }
        }

        $stmtFactura->close();
        $stmtDetalle->close();

        return $referencia;
    }

    public function obtenerFacturasPorCliente($numeroDocumento, $tipoDocumento)
    {
        $sql = "SELECT * FROM facturas WHERE idCliente IN (SELECT id FROM clientes WHERE numeroDocumento = ? AND tipoDocumento = ?)";
        $stmt = $this->conexion->getConnection()->prepare($sql);

        if ($stmt === false) {
            throw new Exception('Error al preparar la consulta de facturas por cliente: ' . $this->conexion->getConnection()->error);
        }

        $stmt->bind_param("ss", $numeroDocumento, $tipoDocumento);
        $stmt->execute();
        $result = $stmt->get_result();
        $facturas = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $facturas;
    }

    public function obtenerDetallesFactura($referencia)
    {
    $sql = "SELECT d.*, a.nombre AS nombreProducto FROM detalleFacturas d
            INNER JOIN articulos a ON d.idArticulo = a.id
            WHERE d.referenciaFactura = ?";
    $stmt = $this->conexion->getConnection()->prepare($sql);

    if ($stmt === false) {
        throw new Exception('Error al preparar la consulta de detalles de factura: ' . $this->conexion->getConnection()->error);
    }

    $stmt->bind_param("s", $referencia);
    $stmt->execute();
    $result = $stmt->get_result();
    $detalles = $result->fetch_all(MYSQLI_ASSOC);
    return $detalles;
    }

    public function obtenerFacturaPorReferencia($referencia)
    {
    $sql = "SELECT f.*, c.nombreCompleto, c.tipoDocumento, c.numeroDocumento, c.telefono, c.email, SUM(d.cantidad * d.precioUnitario) AS total, 
            GROUP_CONCAT(a.nombre SEPARATOR ', ') AS productos
            FROM facturas f 
            INNER JOIN clientes c ON f.idCliente = c.id 
            LEFT JOIN detalleFacturas d ON f.referencia = d.referenciaFactura 
            LEFT JOIN articulos a ON d.idArticulo = a.id
            WHERE f.referencia = ?
            GROUP BY f.referencia";
    $stmt = $this->conexion->getConnection()->prepare($sql);

    if ($stmt === false) {
        throw new Exception('Error al preparar la consulta de la factura por referencia: ' . $this->conexion->getConnection()->error);
    }

    $stmt->bind_param("s", $referencia);
    $stmt->execute();
    $result = $stmt->get_result();
    $factura = $result->fetch_assoc();
    $stmt->close();

    return $factura;
}
   
   
}










