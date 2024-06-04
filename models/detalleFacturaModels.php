<?php
namespace App\models;

class DetalleFactura {
    private $id;
    private $cantidad;
    private $precioUnitario;
    private $idArticulo;
    private $referenciaFactura;

    public function __construct($id, $cantidad, $precioUnitario, $idArticulo, $referenciaFactura) {
        $this->id = $id;
        $this->cantidad = $cantidad;
        $this->precioUnitario = $precioUnitario;
        $this->idArticulo = $idArticulo;
        $this->referenciaFactura = $referenciaFactura;
    }

    public function getId() {
        return $this->id;
    }

    public function getCantidad() {
        return $this->cantidad;
    }

    public function getPrecioUnitario() {
        return $this->precioUnitario;
    }

    public function getIdArticulo() {
        return $this->idArticulo;
    }

    public function getReferenciaFactura() {
        return $this->referenciaFactura;
    }
}



?>

