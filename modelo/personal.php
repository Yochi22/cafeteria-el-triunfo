<?php
require_once('modelo/datos.php');

class personal extends datos{

  
    private $cedulaPer;
    private $nombrePer;
    private $apellidoPer;
    private $password;
    private $rol;
  
    

    function set_cedulaPer($valor){ $this->cedulaPer = $valor; }
    function set_nombrePer($valor){ $this->nombrePer = $valor; }
    function set_apellidoPer($valor){ $this->apellidoPer = $valor; }
    function set_password($valor){ $this->password = $valor; }
    function set_rol($valor){ $this->rol = $valor; }



    function get_cedulaPer($valor){ $this->cedulaPer = $valor; }
    function get_nombrePer($valor){ $this->nombrePer = $valor; }
    function get_apellidoPer($valor){ $this->apellidoPer = $valor; }
    function get_password($valor){ $this->password = $valor; }
    function get_rol($valor){ $this->rol = $valor; }


    // funcion de Registrar Cliente
    function incluir(){
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        if(!$this->existe($this->cedulaPer)){
            try{
                $inc = $co->prepare("INSERT INTO personal(cedulaPer, nombrePer, apellidoPer, password, rol)
                VALUES (:cedulaPer, :nombrePer, :apellidoPer, :password, :rol)");

                $inc->bindParam(':cedulaPer', $this->cedulaPer);
                $inc->bindParam(':nombrePer', $this->nombrePer);
                $inc->bindParam(':apellidoPer', $this->apellidoPer);
                $inc->bindParam(':password', $this->password);
                $inc->bindParam(':rol', $this->rol);
                $inc->execute();

                $r['resultado'] = 'incluir';
                $r['mensaje'] = 'Empleado Registrado';
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

    // funcion de Modificar Personal
    function modificar(){
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        if($this->existe($this->cedulaPer)){
            try{
                $mod = $co->prepare("UPDATE personal
                    SET nombrePer = :nombrePer, apellidoPer = :apellidoPer, password = :password, rol = :rol 
                    WHERE cedulaPer = :cedulaPer");

                $mod->bindParam(':cedulaPer', $this->cedulaPer);
                $mod->bindParam(':nombrePer', $this->nombrePer);
                $mod->bindParam(':apellidoPer', $this->apellidoPer);
                $mod->bindParam(':password', $this->password);
                $mod->bindParam(':rol', $this->rol);
                $mod->execute();
                
                $r['resultado'] = 'modificar';
                $r['mensaje'] = 'Empleado Modificado';
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


    function eliminar(){
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        if($this->existe($this->cedulaPer)){
            try{
                $eli = $co->prepare("DELETE FROM personal WHERE cedulaPer = :cedulaPer");

                $eli->bindParam(':cedulaPer', $this->cedulaPer);
                $eli->execute();

                $r['resultado'] = 'eliminar';
                $r['mensaje'] = 'Empleado Eliminado';
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

    function consultar(){
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try{
                $resultado = $co->query("SELECT * FROM personal");            if($resultado){
                $respuesta = '';
                foreach($resultado as $fila){
                    $respuesta .= "<tr>";
                    $respuesta .= "<td>" . $fila['cedulaPer'] . "</td>";
                    $respuesta .= "<td>" . $fila['nombrePer'] ." ". $fila['apellidoPer'] . "</td>";
                    $respuesta .= "<td>" . $fila['password'] . "</td>";
                    $respuesta .= "<td>" . $fila['rol'] . "</td>";
                    $respuesta .= "<td>";
                    $respuesta .= "<button type='button' class='btn text-white w-80 small-width m-1' style='background-color: #FF8C00;' onclick='pone(this)'><i class='bi bi-pencil-square'></i> Modificar</button>";
                    $respuesta .= "<button type='button' class='btn text-white w-80 small-width m-1' style='background-color: #FF8C00;' onclick='eliminar(this)'><i class='bi bi-trash-fill'></i> Eliminar</button>";
                    $respuesta .= "</td>";
                    $respuesta .= "</tr>";
                }

                if($respuesta == ""){
                    $respuesta .= "<tr>";
                    $respuesta .= "<td colspan = '5' class='text-center text-muted py-4>'";
                    $respuesta .= "<span><i class='bi bi-person-fill fs-1' style='color: #FF8C00'></i></span>";
                    $respuesta .= "<h5 class= 'text-dashboard'> No tienes Personal Registrados </h5>";
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

    //funcion para saber si ya el Empleado esta resgitrado
    function existe($cedulaPer){
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try{
            $resultado = $co->prepare("SELECT * FROM personal WHERE cedulaPer = :cedulaPer");
            $resultado->bindParam(':cedulaPer', $cedulaPer);
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
                $bus = $co->prepare("SELECT cedulaPer, nombrePer, apellidoPer, password, rol 
                                    FROM personal
                                    WHERE (cedulaPer LIKE :busqueda
                                    OR nombrePer LIKE :busqueda
                                    OR apellidoPer LIKE :busqueda
                                    OR password LIKE :busqueda
                                    OR rol LIKE :busqueda");
                
                $bus->bindParam(':busqueda', $busqueda);
                $bus->execute();
                $resultado = $bus->fetchAll();
                
                $respuesta = "";
                foreach($resultado as $fila){
                    $respuesta .= "<tr>";
                    $respuesta .= "<td>" . $fila['cedulaPer'] . "</td>";
                    $respuesta .= "<td>" . $fila['nombrePer'] ." ". $fila['apellidoPer'] . "</td>";
                    $respuesta .= "<td>" . $fila['password'] . "</td>";
                    $respuesta .= "<td>" . $fila['rol'] . "</td>";
                    $respuesta .= "<td>";
                    $respuesta .= "<button type='button' class='btn text-white w-80 small-width m-1' style='background-color: #FF8C00;' onclick='pone(this)'><i class='bi bi-pencil-square'></i> Modificar</button>";
                    $respuesta .= "<button type='button' class='btn text-white w-80 small-width m-1' style='background-color: #FF8C00;' onclick='eliminar(this)'><i class='bi bi-trash-fill'></i> Eliminar</button>";
                    $respuesta .= "</td>";
                    $respuesta .= "</tr>";
                }
                if($respuesta == ""){
                    $respuesta .= "<tr>";
                    $respuesta .= "<td colspan='5' class='text-center text-muted py-4'>";
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