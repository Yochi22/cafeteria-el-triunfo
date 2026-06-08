<?php
class datos {
    private $ip         = "localhost";
    private $bd         = "cafeteria_el_triunfo";
    private $usuario    = "root";
    private $contrasena = "";

    function conecta() {
        
        $pdo = new PDO(
            "mysql:host=" . $this->ip . ";port=3307;dbname=" . $this->bd,
            $this->usuario,
            $this->contrasena
        );
        $pdo->exec("set names utf8");
        return $pdo;
    }
}
?>