<?php

require_once('modelo/datos.php');

class cuentas extends datos
{
    // Atributos
    private $precioTasa;
    private $fechaTasa;

    // Funciones SET
    function set_precioTasa($valor) { $this->precioTasa = $valor; }
    function set_fechaTasa($valor) { $this->fechaTasa = $valor; }

    // Funciones GET
    function get_precioTasa() { return $this->precioTasa; }
    function get_fechaTasa() { return $this->fechaTasa; }


    // Función para ver si la Tasa ya existe
    function existe($fechaTasa)
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $resultado = $co->prepare("SELECT * FROM tasadiaria WHERE fechaTasa = :fechaTasa");
            $resultado->bindParam(':fechaTasa', $fechaTasa);
            $resultado->execute();
            $fila = $resultado->fetchAll(PDO::FETCH_BOTH);

            if ($fila) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }

    // Función para guardar Tasa
    function incluir()
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try {
            $fecha = date('Y-m-d');
                    if ($this->existe($fecha)) {
                    $r['resultado'] = 'error';
                    $r['mensaje'] = '¡La tasa del día de hoy ya está registrada!';
                    return $r; 
                }
            
            $inc = $co->prepare("INSERT INTO tasadiaria(precioTasa, fechaTasa) 
            VALUES (:precioTasa, :fecha)");

            $inc->bindParam(':precioTasa', $this->precioTasa);
            $inc->bindParam(':fecha', $fecha);
            $inc->execute();

                $r['resultado'] = 'incluir';
                $r['mensaje'] = 'Cuenta registrada con éxito.';
            } catch (Exception $e) {
                $r['resultado'] = 'error';
                $r['mensaje'] = $e->getMessage();
            }

        return $r;
    }
}

    ?>