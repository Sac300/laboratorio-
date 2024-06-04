<?php

namespace App\Controllers;
require '../Models/articuloModels.php';
use App\Models\Articulo;
use App\Controllers\ConexionDBController;

class ArticuloController
{
    private $conexion;

    public function __construct(ConexionDBController $conexion)
    {
        $this->conexion = $conexion;
    }

    public function obtenerArticulo($id)
    {
        $sqlArticulo = "SELECT * FROM articulos WHERE id = $id";
        $resultado = $this->conexion->execSQL($sqlArticulo);
        
        if ($resultado->num_rows > 0) {
            $articulo = $resultado->fetch_assoc();
            return new Articulo($articulo['id'], $articulo['nombre'], $articulo['precio']);
        } else {
            return null;
        }
    }

    public function obtenerArticulos()
    {
        $sqlArticulo = "SELECT * FROM articulos";
        $resultado = $this->conexion->execSQL($sqlArticulo);
        $articulos = [];
        
        while ($articulo = $resultado->fetch_assoc()) {
            $modelo = new Articulo($articulo['id'], $articulo['nombre'], $articulo['precio']);
            array_push($articulos, $modelo);
        }
        
        return $articulos;
    }
}


$id = 1;
$conexion = new ConexionDBController();
$articuloController = new ArticuloController($conexion); 
$articulo = $articuloController->obtenerArticulo($id);

if ($articulo) {
    echo "ID: " . $articulo->getId() . "<br>";
    echo "Nombre: " . $articulo->getNombre() . "<br>";
    echo "Precio: " . $articulo->getPrecio() . "<br>";
} else {
    echo "Artículo no encontrado.";
}
?>