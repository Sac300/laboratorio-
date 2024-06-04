<?php
namespace App\models;

class Factura {
    private $referencia;
    private $fecha;
    private $idCliente;
    private $estado;
    private $descuento;

    public function __construct($referencia, $fecha, $idCliente, $estado, $descuento) {
        $this->referencia = $referencia;
        $this->fecha = $fecha;
        $this->idCliente = $idCliente;
        $this->estado = $estado;
        $this->descuento = $descuento;
    }

    public function getReferencia() {
        return $this->referencia;
    }

    public function getFecha() {
        return $this->fecha;
    }

    public function getIdCliente() {
        return $this->idCliente;
    }

    public function getEstado() {
        return $this->estado;
    }

    public function getDescuento() {
        return $this->descuento;
    }
}
?>
