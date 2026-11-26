<?php

require_once("modelo/datos.php");

class bienvenida extends datos
{
    public function consultarHorarios()
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $stmt = $co->query("SELECT idHorario, dia, horaApertura, horaCierre FROM horarios");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }
}

?>