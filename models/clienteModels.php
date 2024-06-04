<?php
namespace App\models;

class Cliente {
    private $id;
    private $nombreCompleto;
    private $tipoDocumento;
    private $numeroDocumento;
    private $email;
    private $telefono;

    public function __construct($id, $nombreCompleto, $tipoDocumento, $numeroDocumento, $email, $telefono) {
        $this->id = $id;
        $this->nombreCompleto = $nombreCompleto;
        $this->tipoDocumento = $tipoDocumento;
        $this->numeroDocumento = $numeroDocumento;
        $this->email = $email;
        $this->telefono = $telefono;
    }

    public function getId() {
        return $this->id;
    }

    public function getNombreCompleto() {
        return $this->nombreCompleto;
    }

    public function getTipoDocumento() {
        return $this->tipoDocumento;
    }

    public function getNumeroDocumento() {
        return $this->numeroDocumento;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getTelefono() {
        return $this->telefono;
    }
}
?>

