<?php

namespace App\Models;

class Articulo
{
    private $id;
    private $nombre;
    private $precio;

    public function __construct($id, $nombre, $precio)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->precio = $precio;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function getPrecio()
    {
        return $this->precio;
    }
}
?>
