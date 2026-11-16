<?php

require_once("modelo/datos.php");

class login extends datos
{
    // Atributos
    private $username;
    private $password;

    // Setters
    function set_username($valor) { $this->username = $valor; }
    function set_password($valor) { $this->password = $valor; }

    // Función para verificar si el usuario y contraseña son correctos
    function existe()
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();

        try {
            // Preparar la consulta para buscar el usuario y su rol
            $resultado = $co->prepare("
                SELECT usuarios.*, roles.rol
                FROM usuarios
                INNER JOIN roles ON usuarios.idRol = roles.idRol
                WHERE usuarios.username = :username
            ");

            $resultado->bindParam(':username', $this->username);
            $resultado->execute();
            $fila = $resultado->fetch(PDO::FETCH_ASSOC);

            // Validar si el usuario existe y la contraseña coincide
            if ($fila) {
                if (password_verify($this->password, $fila['password'])) {
                    $r['resultado'] = 'existe';
                    $r['mensaje'] = 'Acceso concedido.';
                    $r['datos'] = $fila; // Retorna los datos del usuario y rol
                } else {
                    $r['resultado'] = 'error';
                    $r['mensaje'] = 'Contraseña incorrecta.';
                }
            } else {
                $r['resultado'] = 'error';
                $r['mensaje'] = 'El usuario no existe.';
            }
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }

        return $r;
    }
}

?>