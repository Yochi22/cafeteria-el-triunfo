<?php
require_once('modelo/datos.php');

class clientes extends datos
{

    //Atributos
    private $cedulaCli;
    private $nombreCli;
    private $apellidoCli;
    private $tlfCli;

    //funcion SET
    function set_cedulaCli($valor){ $this->cedulaCli = $valor; }
    function set_nombreCli($valor){ $this->nombreCli = $valor; }
    function set_apellidoCli($valor){ $this->apellidoCli = $valor; }
    function set_tlfCli($valor){ $this->tlfCli = $valor; }

    //funcion GET
    function get_cedulaCli(){ return $this->cedulaCli; }
    function get_nombreCli(){ return $this->nombreCli; }
    function get_apellidoCli(){ return $this->apellidoCli; }
    function get_tlfCli(){ return $this->tlfCli; }

    // funcion de Registrar Cliente
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
                $r['mensaje'] = 'Cliente Registrado';
            } catch (Exception $e) {
                $r['resultado'] = 'error';
                $r['mensaje'] = $e->getMessage();
            }
        } else {
            $r['resultado'] = 'incluir';
            $r['mensaje'] = 'Ya existe la Cedula a Resgitrar';
        }
        return $r;
    }

    // funcion de Modificar cliente
    function modificar()
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        if ($this->existe($this->cedulaCli)) {
            try {
                $mod = $co->prepare("UPDATE clientes
                    SET cedulaCli = :cedulaCli, nombreCli = :nombreCli, apellidoCli = :apellidoCli, tlfCli = :tlfCli 
                    WHERE cedulaCli = :cedulaCli");

                $mod->bindParam(':cedulaCli', $this->cedulaCli);
                $mod->bindParam(':nombreCli', $this->nombreCli);
                $mod->bindParam(':apellidoCli', $this->apellidoCli);
                $mod->bindParam(':tlfCli', $this->tlfCli);
                $mod->execute();

                $r['resultado'] = 'modificar';
                $r['mensaje'] = 'Cliente Modificado';
            } catch (Exception $e) {
                $r['resultado'] = 'error';
                $r['mensaje'] = $e->getMessage();
            }
        } else {
            $r['resultado'] = 'modificar';
            $r['mensaje'] = 'La Cedula No Existe';
        }
        return $r;
    }

    //funcion de Eliminar Cliente
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
                $r['mensaje'] = 'Cliente Eliminado';
            } catch (Exception $e) {
                $r['resultado'] = 'error';
                $r['mensaje'] = 'No se puede eliminar el cliente porque está asociado a un pedido.';
            }
        } else {
            $r['resultado'] = 'eliminar';
            $r['mensaje'] = 'La cedula No Existe';
        }
        return $r;
    }

    //funcion de Consultar el Cliente
    function consultar()
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try {
            $resultado = $co->query("SELECT * FROM clientes");
            if ($resultado) {
                $respuesta = '';
                foreach ($resultado as $fila) {
                    $respuesta .= "<tr>";
                    $respuesta .= "<td>" . $fila['cedulaCli'] . "</td>";
                    $respuesta .= "<td>" . $fila['nombreCli'] . " " . $fila['apellidoCli'] . "</td>";
                    $respuesta .= "<td>" . $fila['tlfCli'] . "</td>";
                    $respuesta .= "<td>";
                    $respuesta .= "<button type='button' class='btn text-white w-80 small-width m-1' style='background-color: #FF8C00;' onclick='pone(this)'><i class='bi bi-pencil-square'></i><span class='d-none d-sm-inline'> Modificar</span></button>";
                    $respuesta .= "<button type='button' class='btn text-white w-80 small-width m-1' style='background-color: #FF8C00;' onclick='eliminar(this)'><i class='bi bi-trash-fill'></i><span class='d-none d-sm-inline'> Eliminar</span></button>";
                    $respuesta .= "</td>";
                    $respuesta .= "</tr>";
                }

                if ($respuesta == "") {
                    $respuesta .= "<tr>";
                    $respuesta .= "<td colspan = '5' class='text-center text-muted py-4>'";
                    $respuesta .= "<span><i class='bi bi-person-fill fs-1' style='color: #FF8C00'></i></span>";
                    $respuesta .= "<h5 class= 'text-dashboard'> No tienes Cientes Registrados </h5>";
                    $respuesta .= "</td>";
                    $respuesta .= "</tr>";
                }

                $r['resultado'] = 'consultar';
                $r['mensaje'] = $respuesta;
            } else {
                $r['resultado'] = 'consultar';
                $r['mensaje'] = '';
            }
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    //funcion para saber si ya el Cliente esta resgitrado
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

    // funcion para buscar
    function buscar($valor)
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $valor = trim($valor);
        $busqueda = "%" . $valor . "%";
        $r = array();
        try {
            if ($busqueda) {
                $bus = $co->prepare("SELECT cedulaCli, nombreCli, apellidoCli, tlfCli 
                                    FROM clientes
                                    WHERE cedulaCli LIKE :busqueda
                                    OR nombreCli LIKE :busqueda
                                    OR apellidoCli LIKE :busqueda
                                    OR tlfCli LIKE :busqueda");

                $bus->bindParam(':busqueda', $busqueda);
                $bus->execute();
                $resultado = $bus->fetchAll();

                $respuesta = "";
                foreach ($resultado as $fila) {
                    $respuesta .= "<tr>";
                    $respuesta .= "<td>" . $fila['cedulaCli'] . "</td>";
                    $respuesta .= "<td>" . $fila['nombreCli'] . " " . $fila['apellidoCli'] . "</td>";
                    $respuesta .= "<td>" . $fila['tlfCli'] . "</td>";
                    $respuesta .= "<td>";
                    $respuesta .= "<button type='button' class='btn text-white w-80 small-width m-1' style='background-color: #FF8C00;' onclick='pone(this)'><i class='bi bi-pencil-square'></i> Modificar</button>";
                    $respuesta .= "<button type='button' class='btn text-white w-80 small-width m-1' style='background-color: #FF8C00;' onclick='eliminar(this)'><i class='bi bi-trash-fill'></i> Eliminar</button>";
                    $respuesta .= "</td>";
                    $respuesta .= "</tr>";
                }
                if ($respuesta == "") {
                    $respuesta .= "<tr>";
                    $respuesta .= "<td colspan = '5' class='text-center text-muted py-4>'";
                    $respuesta .= "<span><i class='bi bi-person-fill-slash fs-1' style='color : #FF8C00'></i></i></span>";
                    $respuesta .= "<h5 class= 'text-dashboard'> No se Encuentran Registros </h5>";
                    $respuesta .= "<h6 class= 'text-dashboard'> Intenta de Nuevo </h6>";
                    $respuesta .= "</td>";
                    $respuesta .= "</tr>";
                }

                $r['resultado'] = 'buscar';
                $r['mensaje'] = $respuesta;
            } else {
                $r['resultado'] = 'consultar';
                $r['mensaje'] = '';
            }
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }
}

?>