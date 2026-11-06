<?php

require_once("modelo/datos.php");

class bienvenida extends datos
{
    public function consultarHorario()
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $stmt = $co->query("SELECT * FROM horario");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return array();
        }
    }
}

?>