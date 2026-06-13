<?php
require_once('modelo/datos.php');

class personal extends datos{

  
    private $cedulaPer;
    private $nombre;
    private $apellido;
    private $password;
    private $rol;
    private $estado;
    

    function set_cedulaPer($valor){ $this->cedulaPer = $valor; }
    function set_nombre($valor){ $this->nombre = $valor; }
    function set_apellido($valor){ $this->apellido = $valor; }
    function set_password($valor){ $this->password = $valor; }
    function set_rol($valor){ $this->rol = $valor; }
    function set_estado($valor){ $this->estado = $valor; }


    function get_cedulaPer($valor){ $this->cedulaPer = $valor; }
    function get_nombre($valor){ $this->nombre = $valor; }
    function get_apellido($valor){ $this->apellido = $valor; }
    function get_password($valor){ $this->password = $valor; }
    function get_rol($valor){ $this->rol = $valor; }
    function get_estado($valor){ $this->estado = $valor; }

    // funcion de Registrar Cliente
    function incluir(){
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        if(!$this->existe($this->cedulaPer)){
            try{
                $inc = $co->prepare("INSERT INTO personal(cedulaPer, nombre, apellido, password, rol, estado)
                VALUES (:cedulaPer, :nombre, :apellido, :password, :rol, 1)");

                $inc->bindParam(':cedulaPer', $this->cedulaPer);
                $inc->bindParam(':nombre', $this->nombre);
                $inc->bindParam(':apellido', $this->apellido);
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
                    SET nombre = :nombre, apellido = :apellido, password = :password, rol = :rol 
                    WHERE cedulaPer = :cedulaPer");

                $mod->bindParam(':cedulaPer', $this->cedulaPer);
                $mod->bindParam(':nombre', $this->nombre);
                $mod->bindParam(':apellido', $this->apellido);
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
                $eli = $co->prepare("UPDATE personal SET estado = 0 WHERE cedulaPer = :cedulaPer");

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
            $resultado = $co->query("SELECT * FROM personal WHERE estado = 1");
            if($resultado){
                $respuesta = '';
                foreach($resultado as $fila){
                    $respuesta .= "<tr>";
                    $respuesta .= "<td>" . $fila['cedulaPer'] . "</td>";
                    $respuesta .= "<td>" . $fila['nombre'] ." ". $fila['apellido'] . "</td>";
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
            $resultado = $co->prepare("SELECT * FROM personal WHERE cedulaPer = :cedulaPer AND estado = 1");
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
                $bus = $co->prepare("SELECT cedulaPer, nombre, apellido, password, rol 
                                    FROM personal
                                    WHERE (cedulaPer LIKE :busqueda
                                    OR nombre LIKE :busqueda
                                    OR apellido LIKE :busqueda
                                    OR password LIKE :busqueda
                                    OR rol LIKE :busqueda) 
                                    AND estado = 1");
                
                $bus->bindParam(':busqueda', $busqueda);
                $bus->execute();
                $resultado = $bus->fetchAll();
                
                $respuesta = "";
                foreach($resultado as $fila){
                    $respuesta .= "<tr>";
                    $respuesta .= "<td>" . $fila['cedulaPer'] . "</td>";
                    $respuesta .= "<td>" . $fila['nombre'] ." ". $fila['apellido'] . "</td>";
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