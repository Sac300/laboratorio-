<?php

namespace App\controllers;

use App\controllers\ConexionDBController;

class FacturasPreviasController extends ConexionDBController
{
    public function obtenerFacturasCliente($idCliente)
    {
        $sql = "SELECT * FROM facturas WHERE idCliente = $idCliente";
        $result = $this->execSQL($sql);
        $facturas = [];

        while ($row = $result->fetch_assoc()) {
            $facturas[] = $row;
        }

        return $facturas;
    }

    public function obtenerDetallesFactura($referenciaFactura)
    {
        $sql = "SELECT * FROM detalleFacturas WHERE referenciaFactura = '$referenciaFactura'";
        $result = $this->execSQL($sql);
        $detalles = [];

        while ($row = $result->fetch_assoc()) {
            $detalles[] = $row;
        }

        return $detalles;
    }
}
?>



