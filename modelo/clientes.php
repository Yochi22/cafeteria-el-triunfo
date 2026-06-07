<?php
require_once('modelo/datos.php');

class clientes extends datos{

    //Atributos
    private $cedulaCliente;
    private $nombreCliente;
    private $apellidoCliente;
    private $tlfCliente;
    private $dirCliente;
    private $estado;

    //funcion SET
    function set_cedulaCliente($valor){ $this->cedulaCliente = $valor; }
    function set_nombreCliente($valor){ $this->nombreCliente = $valor; }
    function set_apellidoCliente($valor){ $this->apellidoCliente = $valor; }
    function set_tlfCliente($valor){ $this->tlfCliente = $valor; }
    function set_dirCliente($valor){ $this->dirCliente = $valor; }
    function set_estado($valor) {$this->estado = $valor; }

    //funcion GET
    function get_cedulaCliente(){ return $this->cedulaCliente; }
    function get_nombreCliente(){ return $this->nombreCliente; }
    function get_apellidoCliente(){ return $this->apellidoCliente; }
    function get_tlfCliente(){ return $this->tlfCliente; }
    function get_dirCliente(){ return $this->dirCliente;}
    function get_estado(){ return $this->estado; }
 

    // funcion de Resgistrar Cliente
    function incluir(){
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        if(!$this->existe($this->cedulaCliente)){
            try{
                $inc = $co->prepare("INSERT INTO clientes(cedulaCliente, nombreCliente, apellidoCliente, tlfCliente, dirCliente)
                VALUES (:cedulaCliente, :nombreCliente, :apellidoCliente, :tlfCliente, :dirCliente)");

                $inc->bindParam(':cedulaCliente', $this->cedulaCliente);
                $inc->bindParam(':nombreCliente', $this->nombreCliente);
                $inc->bindParam(':apellidoCliente', $this->apellidoCliente);
                $inc->bindParam(':tlfCliente', $this->tlfCliente);
                $inc->bindParam(':dirCliente', $this->dirCliente);
                $inc->execute();

                $r['resultado'] = 'incluir';
                $r['mensaje'] = 'Cliente Registrado';
            } catch(Exception $e){
                $r['resultado'] = 'error';
                $r['mensaje'] = $e->getMessage();
            }
        } else{
            $r['resultado'] = 'incluir';
            $r['mensaje'] = 'Ya existe la Cedula a Resgitrar';
        }
        return $r;
    }

    // funcion de Mofidicar cliente
    function modificar(){
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        if($this->existe($this->cedulaCliente)){
            try{
                $mod = $co->prepare("UPDATE clientes
                    SET cedulaCliente = :cedulaCliente, nombreCliente = :nombreCliente, apellidoCliente = :apellidoCliente, tlfCliente = :tlfCliente, dirCliente = :dirCliente 
                    WHERE cedulaCliente = :cedulaCliente");

                $mod->bindParam(':cedulaCliente', $this->cedulaCliente);
                $mod->bindParam(':nombreCliente', $this->nombreCliente);
                $mod->bindParam(':apellidoCliente', $this->apellidoCliente);
                $mod->bindParam(':tlfCliente', $this->tlfCliente);
                $mod->bindParam(':dirCliente', $this->dirCliente);
                $mod->execute();
                
                $r['resultado'] = 'modificar';
                $r['mensaje'] = 'Cliente Modificado';
            } catch(Exception $e){
                $r['resultado'] = 'error';
                $r['mensaje'] = $e->getMessage();
            }
        } else{
            $r['resultado'] = 'modificar';
            $r['mensaje'] = 'La Cedula No Existe';
        }
        return $r;
    }

    //funcion de Eliminar Cliente
    function eliminar(){
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        if($this->existe($this->cedulaCliente)){
            try{
                $eli = $co->prepare("UPDATE clientes SET estado = 0 WHERE cedulaCliente = :cedulaCliente");

                $eli->bindParam(':cedulaCliente', $this->cedulaCliente);
                $eli->execute();

                $r['resultado'] = 'eliminar';
                $r['mensaje'] = 'Cliente Eliminmado';
            } catch(Exception $e) {
                $r['resultado'] = 'error';
                $r['mensaje'] = $e->getMessage();
            }
        } else{
            $r['resultado'] = 'eliminar';
            $r['mensaje'] = 'La cedula No Existe';
        }
        return $r;
    }

    //funcion de Consultar el Cliente
    function consultar(){
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try{
            $resultado = $co->query("SELECT * FROM clientes WHERE estado = 1");
            if($resultado){
                $respuesta = '';
                foreach($resultado as $fila){
                    $respuesta .= "<tr>";
                    $respuesta .= "<td>" . $fila['cedulaCliente'] . "</td>";
                    $respuesta .= "<td>" . $fila['nombreCliente'] ." ". $fila['apellidoCliente'] . "</td>";
                    $respuesta .= "<td>" . $fila['tlfCliente'] . "</td>";
                    $respuesta .= "<td>" . $fila['dirCliente'] . "</td>";
                    $respuesta .= "<td>";
                        $respuesta .= "<button type='button' class='btn text-white w-80 small-width me-1' style='background-color: #FF8C00;' onclick='pone(this,0)'><i class='bi bi-pencil-square'></i> Modificar</button>";
                        $respuesta .= "<button type='button' class='btn text-white w-80 small-width ms-1' style='background-color: #FF8C00;' onclick='pone(this,1)'><i class='bi bi-trash-fill'></i> Eliminar</button>";
                    $respuesta .= "</td>";
                    $respuesta .= "</tr>";
                }
                $r['resultado'] = 'consultar';
                $r['mensaje'] = $respuesta;
            } else{
                $r['resultado'] = 'consultar';
                $r['mensaje'] = '';
            }
        } catch(Exception $e){
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    //funcion para saber si ya el Cliente esta resgitrado
    function existe($cedulaCliente){
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try{
            $resultado = $co->prepare("SELECT * FROM clientes WHERE cedulaCliente = :cedulaCliente AND estado = 1");
            $resultado->bindParam(':cedulaCliente', $cedulaCliente);
            $resultado->execute();
            $fila = $resultado->fetchAll(PDO::FETCH_BOTH);
            if($fila){
                return true;
            } else{
                return false;
            }
        } catch(Exception $e){
            return false;
        }
    }

}
?>