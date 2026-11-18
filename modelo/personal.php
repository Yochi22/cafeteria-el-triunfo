<?php

require_once('modelo/datos.php');

class personal extends datos
{
    // Atributos 
    private $cedulaPer;
    private $nombrePer;
    private $apellidoPer;

    // Funciones SET
    function set_cedulaPer($valor) { $this->cedulaPer = $valor; }
    function set_nombrePer($valor) { $this->nombrePer = $valor; }
    function set_apellidoPer($valor) { $this->apellidoPer = $valor; }

    // Funciones GET
    function get_cedulaPer() { return $this->cedulaPer; }
    function get_nombrePer() { return $this->nombrePer; }
    function get_apellidoPer() { return $this->apellidoPer; }

    // Función Existe verificar si el personal ya está registrado
    function existe($cedulaPer)
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $resultado = $co->prepare("SELECT * FROM personal WHERE cedulaPer = :cedulaPer");
            $resultado->bindParam(':cedulaPer', $cedulaPer);
            $resultado->execute();

            $fila = $resultado->fetchAll(PDO::FETCH_BOTH);

            if ($fila) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    // Función Incluir
    function incluir()
    {
        $r = array();
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if (!$this->existe($this->cedulaPer)) {
            try {
                $inc = $co->prepare("INSERT INTO personal(cedulaPer, nombrePer, apellidoPer) VALUES (:cedulaPer, :nombrePer, :apellidoPer)");
                $inc->bindParam(':cedulaPer', $this->cedulaPer);
                $inc->bindParam(':nombrePer', $this->nombrePer);
                $inc->bindParam(':apellidoPer', $this->apellidoPer);
                $inc->execute();

                $r['resultado'] = 'incluir';
                $r['mensaje'] = 'Empleado incluido.';
            } catch (Exception $e) {
                $r['resultado'] = 'error';
                $r['mensaje'] = $e->getMessage();
            }
        } else {
            $r['resultado'] = 'incluir';
            $r['mensaje'] = 'Ya existe un empleado con esa cédula.';
        }

        return $r;
    }

    // Función Consultar
    function consultar()
    {
        $r = array();
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $resultado = $co->query("SELECT * FROM personal");
            return $resultado->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return $r;
        }
    }

    // Función Modificar
    function modificar()
    {
        $r = array();
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if ($this->existe($this->cedulaPer)) {
            try {
                $mod = $co->prepare("UPDATE personal SET nombrePer = :nombrePer, apellidoPer = :apellidoPer WHERE cedulaPer = :cedulaPer");
                $mod->bindParam(':cedulaPer', $this->cedulaPer);
                $mod->bindParam(':nombrePer', $this->nombrePer);
                $mod->bindParam(':apellidoPer', $this->apellidoPer);
                $mod->execute();

                $r['resultado'] = 'modificar';
                $r['mensaje'] = 'Empleado modificado';
            } catch (Exception $e) {
                $r['resultado'] = 'error';
                $r['mensaje'] = $e->getMessage();
            }
        } else {
            $r['resultado'] = 'modificar';
            $r['mensaje'] = 'La cédula no existe.';
        }

        return $r;
    }

    // Función Eliminar
    function eliminar()
    {
        $r = array();
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if ($this->existe($this->cedulaPer)) {
            try {
                $eli = $co->prepare("DELETE FROM personal WHERE cedulaPer = :cedulaPer");
                $eli->bindParam(':cedulaPer', $this->cedulaPer);
                $eli->execute();

                $r['resultado'] = 'eliminar';
                $r['mensaje'] = 'Empleado eliminado.';
            } catch (Exception $e) {
                $r['resultado'] = 'error';
                $r['mensaje'] = $e->getMessage();
            }
        } else {
            $r['resultado'] = 'eliminar';
            $r['mensaje'] = 'La cédula no existe.';
        }

        return $r;
    }
}

?>