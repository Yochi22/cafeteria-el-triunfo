<?php

require_once("modelo/datos.php");

class login extends datos
{
    private $username;
    private $password;

    function set_username($username) { $this->username = $username; }
    function set_password($password) { $this->password = $password; }

    function existe()
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $co->beginTransaction();
            $resultado = $co->prepare("
                SELECT usuarios.*, roles.rol
                FROM usuarios
                INNER JOIN roles ON usuarios.idRol = roles.idRol
                WHERE usuarios.username = :username
            ");
            $resultado->bindParam(':username', $this->username);
            $resultado->execute();
            $fila = $resultado->fetch(PDO::FETCH_ASSOC);
            $co->commit();
            if ($fila) {
                if (password_verify($this->password, $fila['password'])) {
                    return $fila;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } catch (Exception $e) {
            if ($co->inTransaction()) {
                $co->rollBack();
            }
            return false;
        }
    }
}

?>