<?php

require_once('modelo/datos.php');

class login extends datos
{
    private $username;
    private $password;

    function set_username($valor) { $this->username = $valor; }
    function set_password($valor) { $this->password = $valor; }

    function existe()
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();

        try {
            $resultado = $co->prepare("SELECT usuarios.*, roles.rol FROM usuarios INNER JOIN roles ON usuarios.idRol = roles.idRol WHERE usuarios.username = :username");

            $resultado->bindParam(':username', $this->username);
            $resultado->execute();
            $fila = $resultado->fetch(PDO::FETCH_ASSOC);

            if ($fila) {
                if (password_verify($this->password, $fila['password'])) {
                    $r['resultado'] = 'login';
                    $r['estatus'] = 'exito';
                    $r['mensaje'] = '¡Acceso concedido! Redireccionando...';
                    $r['url'] = 'index.php?pagina=principal';
                    $r['datos'] = $fila;
                } else {
                    $r['resultado'] = 'login';
                    $r['estatus'] = 'error';
                    $r['mensaje'] = 'Contraseña incorrecta.';
                }
            } else {
                $r['resultado'] = 'login';
                $r['estatus'] = 'error';
                $r['mensaje'] = 'El usuario no existe en el sistema.';
            }
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }

        return $r;
    }
}

?>