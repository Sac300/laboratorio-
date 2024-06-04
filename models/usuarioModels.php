<?php
namespace App\models;

class Usuario {
    private $id;
    private $usuario;
    private $pwd;

    public function __construct($id, $usuario, $pwd) {
        $this->id = $id;
        $this->usuario = $usuario;
        $this->pwd = $pwd;
    }

    public function getId() {
        return $this->id;
    }

    public function getUsuario() {
        return $this->usuario;
    }

    public function getPwd() {
        return $this->pwd;
    }
}
?>

