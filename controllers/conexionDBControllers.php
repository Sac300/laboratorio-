<?php

namespace App\Controllers;

class ConexionDBController
{
    private $host = 'localhost';
    private $user = 'root'; 
    private $pwd = ''; 
    private $dataBase = 'facturacion_tienda_db';
    protected $conex;

    public function __construct()
    {
        $this->conex = new \mysqli($this->host, $this->user, $this->pwd, $this->dataBase);

        if ($this->conex->connect_error) {
            die('Error en la conexión a la base de datos: ' . $this->conex->connect_error);
        } else {
        }
    }

    public function execSQL($sql)
    {
        $result = $this->conex->query($sql);
        
        if (!$result) {
            echo 'Error en la ejecución de la consulta: ' . $this->conex->error;
            return false;
        }
        
        return $result;
    }

    public function getConnection()
    {
        return $this->conex;
    }

    public function close()
    {
        $this->conex->close();
    }
}
