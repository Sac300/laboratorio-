<?php

namespace App\controllers;

use App\controllers\ConexionDBController;
class articuloController extends ConexionDBController
{
    public function login()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_POST['username'], $_POST['password'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $username = $this->conex->real_escape_string($username);
            $password = $this->conex->real_escape_string($password);

            if ($this->validarUsuario($username, $password)) {
                $_SESSION['username'] = $username;

                header("Location: MenuViews.php");
                exit();
            } else {
                header("Location: index.php");
                $error_message = "Usuario o contraseña incorrectos";
               
            }
        } else {
            $error_message = "Por favor, ingrese su usuario y contraseña";
        }

        require_once('index.php');
    }

    private function validarUsuario($usuario, $pwd)
    {
        $sql = "SELECT id FROM usuarios WHERE usuario = ? AND pwd = ?";
        $stmt = $this->conex->prepare($sql);
        $stmt->bind_param("ss", $usuario, $pwd);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }
}

?>