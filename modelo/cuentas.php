<?php

require_once('modelo/datos.php');

class cuentas extends datos
{
    // Atributos
    private $nombreBanco;
    private $cedulaTitular;
    private $tlfCuenta;
    private $tipoCuenta;
    private $numCuenta;

    // Funciones SET
    function set_nombreBanco($valor) { $this->nombreBanco = $valor; }
    function set_cedulaTitular($valor) { $this->cedulaTitular = $valor; }
    function set_tlfCuenta($valor) { $this->tlfCuenta = $valor; }
    function set_tipoCuenta($valor) { $this->tipoCuenta = $valor; }
    function set_numCuenta($valor) { $this->numCuenta = $valor; }

    // Funciones GET
    function get_nombreBanco() { return $this->nombreBanco; }
    function get_cedulaTitular() { return $this->cedulaTitular; }
    function get_tlfCuenta() { return $this->tlfCuenta; }
    function get_tipoCuenta() { return $this->tipoCuenta; }
    function get_numCuenta() { return $this->numCuenta; }

    // Función para verificar si la Cuenta ya está registrada
    function existe($numCuenta)
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $resultado = $co->prepare("SELECT * FROM cuentas WHERE numCuenta = :numCuenta");
            $resultado->bindParam(':numCuenta', $numCuenta);
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

    // Función para Registrar Cuenta
    function incluir()
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();

        if (!$this->existe($this->numCuenta)) {
            try {
                $inc = $co->prepare("INSERT INTO cuentas(nombreBanco, cedulaTitular, tlfCuenta, tipoCuenta, numCuenta)
                VALUES (:nombreBanco, :cedulaTitular, :tlfCuenta, :tipoCuenta, :numCuenta)");

                $inc->bindParam(':nombreBanco', $this->nombreBanco);
                $inc->bindParam(':cedulaTitular', $this->cedulaTitular);
                $inc->bindParam(':tlfCuenta', $this->tlfCuenta);
                $inc->bindParam(':tipoCuenta', $this->tipoCuenta);
                $inc->bindParam(':numCuenta', $this->numCuenta);
                $inc->execute();

                $r['resultado'] = 'incluir';
                $r['mensaje'] = 'Cuenta registrada con éxito.';
            } catch (Exception $e) {
                $r['resultado'] = 'error';
                $r['mensaje'] = $e->getMessage();
            }
        } else {
            $r['resultado'] = 'incluir';
            $r['mensaje'] = 'Ya existe el número de cuenta a registrar.';
        }

        return $r;
    }

    // Función para Consultar Cuentas
    function consultar()
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $resultado = $co->query("SELECT nombreBanco, cedulaTitular, tlfCuenta, tipoCuenta, numCuenta FROM cuentas");
            return $resultado->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return array();
        }
    }


    // Función para Modificar Cuenta
    function modificar()
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();

        if ($this->existe($this->numCuenta)) {
            try {
                $mod = $co->prepare("UPDATE cuentas
                    SET nombreBanco = :nombreBanco, cedulaTitular = :cedulaTitular, tlfCuenta = :tlfCuenta, tipoCuenta = :tipoCuenta 
                    WHERE numCuenta = :numCuenta");

                $mod->bindParam(':numCuenta', $this->numCuenta);
                $mod->bindParam(':nombreBanco', $this->nombreBanco);
                $mod->bindParam(':cedulaTitular', $this->cedulaTitular);
                $mod->bindParam(':tlfCuenta', $this->tlfCuenta);
                $mod->bindParam(':tipoCuenta', $this->tipoCuenta);
                $mod->execute();

                $r['resultado'] = 'modificar';
                $r['mensaje'] = 'Cuenta modificada con éxito.';
            } catch (Exception $e) {
                $r['resultado'] = 'error';
                $r['mensaje'] = $e->getMessage();
            }
        } else {
            $r['resultado'] = 'modificar';
            $r['mensaje'] = 'El número de cuenta no existe.';
        }

        return $r;
    }

    // Función para Eliminar Cuenta
    function eliminar()
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();

        if ($this->existe($this->numCuenta)) {
            try {
                $eli = $co->prepare("DELETE FROM cuentas WHERE numCuenta = :numCuenta");
                $eli->bindParam(':numCuenta', $this->numCuenta);
                $eli->execute();

                $r['resultado'] = 'eliminar';
                $r['mensaje'] = 'Cuenta eliminada con éxito.';
            } catch (Exception $e) {
                $r['resultado'] = 'error';
                $r['mensaje'] = 'No se puede eliminar la cuenta porque está asociada a un registro.';
            }
        } else {
            $r['resultado'] = 'eliminar';
            $r['mensaje'] = 'El número de cuenta no existe.';
        }

        return $r;
    }
}

?>