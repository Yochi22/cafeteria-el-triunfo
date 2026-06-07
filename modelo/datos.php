<?php
class datos {
    private $ip         = "localhost";
    private $bd         = "prueba";
    private $usuario    = "root";
    private $contrasena = "";

    function conecta() {
        $pdo = new PDO(
            "mysql:host=" . $this->ip . ";dbname=" . $this->bd,
            $this->usuario,
            $this->contrasena
        );
        $pdo->exec("set names utf8");
        return $pdo;
    }
}
?>