<?php

require_once('modelo/datos.php');

class clientes extends datos
{
    // Atributos
    private $cedulaCli;
    private $nombreCli;
    private $apellidoCli;
    private $tlfCli;

    // Funciones SET
    function set_cedulaCli($valor) { $this->cedulaCli = $valor; }
    function set_nombreCli($valor) { $this->nombreCli = $valor; }
    function set_apellidoCli($valor) { $this->apellidoCli = $valor; }
    function set_tlfCli($valor) { $this->tlfCli = $valor; }

    // Funciones GET
    function get_cedulaCli() { return $this->cedulaCli; }
    function get_nombreCli() { return $this->nombreCli; }
    function get_apellidoCli() { return $this->apellidoCli; }
    function get_tlfCli() { return $this->tlfCli; }

    // Función para verificar si el Cliente ya está registrado
    function existe($cedulaCli)
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $resultado = $co->prepare("SELECT * FROM clientes WHERE cedulaCli = :cedulaCli");
            $resultado->bindParam(':cedulaCli', $cedulaCli);
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

    // Función para Registrar Cliente
    function incluir()
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();

        if (!$this->existe($this->cedulaCli)) {
            try {
                $inc = $co->prepare("INSERT INTO clientes(cedulaCli, nombreCli, apellidoCli, tlfCli)
                VALUES (:cedulaCli, :nombreCli, :apellidoCli, :tlfCli)");

                $inc->bindParam(':cedulaCli', $this->cedulaCli);
                $inc->bindParam(':nombreCli', $this->nombreCli);
                $inc->bindParam(':apellidoCli', $this->apellidoCli);
                $inc->bindParam(':tlfCli', $this->tlfCli);
                $inc->execute();

                $r['resultado'] = 'incluir';
                $r['mensaje'] = 'Cliente registrado con éxito.';
            } catch (Exception $e) {
                $r['resultado'] = 'error';
                $r['mensaje'] = $e->getMessage();
            }
        } else {
            $r['resultado'] = 'incluir';
            $r['mensaje'] = 'Ya existe la cédula a registrar.';
        }

        return $r;
    }

    // Función para Consultar Clientes
    function consultar()
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $resultado = $co->query("SELECT cedulaCli, nombreCli, apellidoCli, tlfCli FROM clientes");
            return $resultado->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return array();
        }
    }

    // Función para Modificar Cliente
    function modificar()
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();

        if ($this->existe($this->cedulaCli)) {
            try {
                $mod = $co->prepare("UPDATE clientes
                    SET nombreCli = :nombreCli, apellidoCli = :apellidoCli, tlfCli = :tlfCli 
                    WHERE cedulaCli = :cedulaCli");

                $mod->bindParam(':cedulaCli', $this->cedulaCli);
                $mod->bindParam(':nombreCli', $this->nombreCli);
                $mod->bindParam(':apellidoCli', $this->apellidoCli);
                $mod->bindParam(':tlfCli', $this->tlfCli);
                $mod->execute();

                $r['resultado'] = 'modificar';
                $r['mensaje'] = 'Cliente modificado con éxito.';
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

    // Función para Eliminar Cliente
    function eliminar()
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();

        if ($this->existe($this->cedulaCli)) {
            try {
                $eli = $co->prepare("DELETE FROM clientes WHERE cedulaCli = :cedulaCli");
                $eli->bindParam(':cedulaCli', $this->cedulaCli);
                $eli->execute();

                $r['resultado'] = 'eliminar';
                $r['mensaje'] = 'Cliente eliminado con éxito.';
            } catch (Exception $e) {
                $r['resultado'] = 'error';
                $r['mensaje'] = 'No se puede eliminar el cliente porque está asociado a un pedido.';
            }
        } else {
            $r['resultado'] = 'eliminar';
            $r['mensaje'] = 'La cédula no existe.';
        }

        return $r;
    }
}

?>