<?php
require_once("modelo/datos.php");

class metodos_pago extends datos
{
    //definicion de variables
    private $nombreBanco;
    private $cedulaTitular;
    private $tlfCuenta;
    private $tipoCuenta;
    private $numCuenta;

    //setters
    function set_nombreBanco($valor) { $this->nombreBanco = $valor; }
    function set_cedulaTitular($valor) { $this->cedulaTitular = $valor; }
    function set_tlfCuenta($valor) { $this->tlfCuenta = $valor; }
    function set_tipoCuenta($valor) { $this->tipoCuenta = $valor; }
    function set_numCuenta($valor) { $this->numCuenta = $valor; }

    //getters
    function get_nombreBanco() { return $this->nombreBanco; }
    function get_cedulaTitular() { return $this->cedulaTitular; }
    function get_tlfCuenta() { return $this->tlfCuenta; }
    function get_tipoCuenta() { return $this->tipoCuenta; }
    function get_numCuenta() { return $this->numCuenta; }

    //funcion INCLUIR
    function incluir(){
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        if(!$this->existe($this->numCuenta)){
            try{
                $inc = $co->prepare("INSERT INTO metodospago(nombreBanco, cedulaTitular, tlfCuenta, tipoCuenta, numCuenta)
                VALUES (:nombreBanco, :cedulaTitular, :tlfCuenta, :tipoCuenta, :numCuenta)");

                $inc->bindParam(':nombreBanco', $this->nombreBanco);
                $inc->bindParam(':cedulaTitular', $this->cedulaTitular);
                $inc->bindParam(':tlfCuenta', $this->tlfCuenta);
                $inc->bindParam(':tipoCuenta', $this->tipoCuenta);
                $inc->bindParam(':numCuenta', $this->numCuenta);
                $inc->execute();

                $r['resultado'] = 'incluir';
                $r['mensaje'] = 'Metodo de Pago Registrado';
            } catch(Exception $e){
                $r['resultado'] = 'error';
                $r['mensaje'] = $e->getMessage();
            }
        } else{
            $r['resultado'] = 'incluir';
            $r['mensaje'] = 'Ya existe el Numero de Cuenta a resgitrar.';
        }
        return $r;
    }

    //funcion MODIFICAR
    function modificar(){
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        if($this->existe($this->numCuenta)){
            try{
                $mod = $co->prepare("UPDATE metodospago SET nombreBanco = :nombreBanco, cedulaTitular = :cedulaTitular, 
                tlfCuenta = :tlfCuenta, tipoCuenta = :tipoCuenta, numCuenta = :numCuenta WHERE numCuenta = :numCuenta");

                $mod->bindParam(':nombreBanco', $this->nombreBanco);
                $mod->bindParam(':cedulaTitular', $this->cedulaTitular);
                $mod->bindParam(':tlfCuenta', $this->tlfCuenta);
                $mod->bindParam(':tipoCuenta', $this->tipoCuenta);
                $mod->bindParam(':numCuenta', $this->numCuenta);
                $mod->execute();
                
                $r['resultado'] = 'modificar';
                $r['mensaje'] = 'Metodo de Pago Modificado';
            } catch(Exception $e){
                $r['resultado'] = 'error';
                $r['mensaje'] = $e->getMessage();
            }
        } else{
            $r['resultado'] = 'modificar';
            $r['mensaje'] = 'El Numero de Cuenta no Existe';
        }
        return $r;
    }

    //funcion ELIMINAR
    function eliminar(){
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        if ($this->existe($this->numCuenta)) {
            try {
                $eli = $co->prepare("DELETE FROM metodospago WHERE numCuenta = :numCuenta");
                $eli->bindParam(':numCuenta', $this->numCuenta);
                $eli->execute();
                
                $r['resultado'] = 'eliminar';
                $r['mensaje'] = 'Metodo de Pago Eliminado';
            } catch(Exception $e) {
                $r['resultado'] = 'error';
                $r['mensaje'] = $e->getMessage();
            }
        } else{
            $r['resultado'] = 'eliminar';
            $r['mensaje'] = 'El Metodo de Pago No Existe';
        }
        return $r;
    }

   function consultar(){
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try{
            $resultado = $co->query("SELECT * FROM metodospago");
            if($resultado){
                $respuesta = '';
                foreach($resultado as $fila){
                    $respuesta .= "<tr>";
                    $respuesta .= "<td>" . $fila['nombreBanco'] . "</td>";
                    $respuesta .= "<td>" . $fila['cedulaTitular'] ."</td>";
                    $respuesta .= "<td>" . $fila['tlfCuenta'] . "</td>";
                    $respuesta .= "<td>" . $fila['tipoCuenta'] . "</td>";
                    $respuesta .= "<td>" . $fila['numCuenta'] . "</td>";
                    $respuesta .= "<td class='text-center'>";
                        $respuesta .= "<button type='button' class='btn text-white w-80 small-width m-1' style='background-color: #FF8C00;' onclick='pone(this)'><i class='bi bi-pencil-square'></i><span class='d-none d-sm-inline'> Modificar</span></button>";
                        $respuesta .= "<button type='button' class='btn text-white w-80 small-width m-1' style='background-color: #FF8C00;' onclick='eliminar(this)'><i class='bi bi-trash-fill'></i><span class='d-none d-sm-inline'> Eliminar</span></button>";
                    $respuesta .= "</td>";
                    $respuesta .= "</tr>";
                }

                if($respuesta == ""){
                    $respuesta .= "<tr>";
                    $respuesta .= "<td colspan = '5' class='text-center text-muted py-4>'";
                    $respuesta .= "<span><i class='bi bi-person-fill fs-1' style='color: #FF8C00'></i></span>";
                    $respuesta .= "<h5 class= 'text-dashboard'> No tienes Metodos de Pago Registrados </h5>";
                    $respuesta .= "</td>";
                    $respuesta .= "</tr>";
                }

                $r['resultado'] = 'consultar';
                $r['mensaje'] = $respuesta;
            } else {
                $r['resultado'] = 'consultar';
                $r['mensaje'] = '';
            }
        } catch(Exception $e){
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

//funcion para saber si ya el Metodo de Pago esta resgitrado
    function existe($numCuenta){
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try{
            $resultado = $co->prepare("SELECT * FROM metodospago WHERE numCuenta = :numCuenta");
            $resultado->bindParam(':numCuenta', $numCuenta);
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

    // funcion para buscar
    function buscar($valor){
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $valor = trim($valor);
        $busqueda = "%" . $valor . "%";
        $r = array();
        try{
            if($busqueda){
                $bus = $co->prepare("SELECT nombreBanco, cedulaTitular, tlfCuenta, tipoCuenta, numCuenta 
                                    FROM metodospago
                                    WHERE (nombreBanco LIKE :busqueda
                                    OR cedulaTitular LIKE :busqueda
                                    OR tlfCuenta LIKE :busqueda
                                    OR tipoCuenta LIKE :busqueda
                                    OR numCuenta LIKE :busqueda");
                
                $bus->bindParam(':busqueda', $busqueda);
                $bus->execute();
                $resultado = $bus->fetchAll();
                
                $respuesta = "";
                foreach($resultado as $fila){
                    $respuesta .= "<tr>";
                    $respuesta .= "<td>" . $fila['nombreBanco'] . "</td>";
                    $respuesta .= "<td>" . $fila['cedulaTitular'] . "</td>";
                    $respuesta .= "<td>" . $fila['tlfCuenta'] . "</td>";
                    $respuesta .= "<td>" . $fila['tipoCuenta'] . "</td>";
                    $respuesta .= "<td>" . $fila['numCuenta'] . "</td>";
                    $respuesta .= "<td>";
                        $respuesta .= "<button type='button' class='btn text-white w-80 small-width m-1' style='background-color: #FF8C00;' onclick='pone(this)'><i class='bi bi-pencil-square'></i> Modificar</button>";
                        $respuesta .= "<button type='button' class='btn text-white w-80 small-width m-1' style='background-color: #FF8C00;' onclick='eliminar(this)'><i class='bi bi-trash-fill'></i> Eliminar</button>";
                    $respuesta .= "</td>";
                    $respuesta .= "</tr>";
                }
                if($respuesta == ""){
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
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

}
?>