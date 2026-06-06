<?php
require_once('modelo/datos.php');

class metodospago extends datos{

	//definicion de variables
	private $idMetodo;
	private $nombreBanco;
	private $cedulaTitular;
	private $telefono;
	private $cuenta;
	private $estado;

	//setters
	function set_idMetodo($valor){
		$this->idMetodo=$valor;
	}
	function set_nombreBanco($valor){
		$this->nombreBanco= $valor;
	}
	function set_cedulaTitular($valor){
		$this->cedulaTitular= $valor;
	}
	function set_telefono($valor){
		$this->telefono= $valor;
	}
	function set_cuenta($valor){
		$this->cuenta= $valor;
	}
	function set_estado($valor){
		$this->estado= $valor;
	}

	//getters
	function get_idMetodo(){
		return $this->idMetodo;
	}
	function get_nombreBanco(){
		return $this->nombreBanco;
	}
	function get_cedulaTitular(){
		return $this->cedulaTitular;
	}
	function get_telefono(){
		return $this->telefono;
	}
	function get_cuenta(){
		return $this->cuenta;
	}
	function get_estado(){
		return $this->estado;
	}

	//FUNCIONES
	//incluir

    function incluir()
    {
        $r = array();
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $st = $co->prepare("SELECT idMetodo, estado FROM metodospago WHERE cuenta = :cuenta");
            $st->bindParam(':cuenta', $this->cuenta);
            $st->execute();
            $metodo_existente = $st->fetch(PDO::FETCH_ASSOC);

            if ($metodo_existente) {
                if ($metodo_existente['estado'] == 1) {
                    $r['resultado'] = 'incluir';
                    $r['mensaje'] = 'Ya Existe un Metodo de Pago con ese numero de cuenta.';
                } else {
                    $m = $co->prepare("UPDATE metodospago SET nombreBanco = :nombreBanco, cedulaTitular = :cedulaTitular, 
                    telefono = :telefono, estado = 1 WHERE idMetodo = :idMetodo");
                    $m->bindParam(':idMetodo', $metodo_existente['idMetodo']);
                    $m->bindParam(':nombreBanco', $this->nombreBanco);
                    $m->bindParam(':cedulaTitular', $this->cedulaTitular);
                    $m->bindParam(':telefono', $this->telefono);
                    $m->execute();
                    
                    $r['resultado'] = 'incluir';
                    $r['mensaje'] = 'Registro Incluido.';
                }
            } else {
                $m = $co->prepare("INSERT INTO metodospago(nombreBanco, cedulaTitular, telefono, cuenta) VALUES(:nombreBanco, :cedulaTitular, :telefono, :cuenta)");
                $m->bindParam(':nombreBanco', $this->nombreBanco);
                $m->bindParam(':cedulaTitular', $this->cedulaTitular);
                $m->bindParam(':telefono', $this->telefono);
                $m->bindParam(':cuenta', $this->cuenta);
                $m->execute();
                
                $r['resultado'] = 'incluir';
                $r['mensaje'] = 'Registro Incluido';
            }
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

	//modificar
	function modificar()
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        
        if ($this->existe($this->idMetodo)) {
            try {
                $m_check = $co->prepare("SELECT idMetodo FROM metodospago WHERE cuenta = :cuenta AND idMetodo != :idMetodo AND estado = 1");
                $m_check->bindParam(':cuenta', $this->cuenta);
                $m_check->bindParam(':idMetodo', $this->idMetodo);
                $m_check->execute();

                if ($m_check->rowCount() > 0) {
                    $r['resultado'] = 'modificar';
                    $r['mensaje'] = 'Ya Existe otro Metodo de Pago con ese Numero de Cuenta.';
                    return $r;
                }

                $m = $co->prepare("UPDATE metodospago SET nombreBanco = :nombreBanco, cedulaTitular = :cedulaTitular, 
                    telefono = :telefono, estado = 1 WHERE idMetodo = :idMetodo");
                $m->bindParam(':idMetodo', $metodo_existente['idMetodo']);
                $m->bindParam(':nombreBanco', $this->nombreBanco);
                $m->bindParam(':cedulaTitular', $this->cedulaTitular);
                $m->bindParam(':telefono', $this->telefono);
                $m->execute();

                $r['resultado'] = 'modificar';
                $r['mensaje'] = 'Registro Modificado';
            } catch (Exception $e) {
                $r['resultado'] = 'error';
                $r['mensaje'] =  $e->getMessage();
            }
        } else {
            $r['resultado'] = 'modificar';
            $r['mensaje'] =  'Metodo de Pago no Registrada';
        }
        return $r;
    }

	//eliminar
	function eliminar()
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        
        if ($this->existe($this->idMetodo)) {
            try {
                $e = $co->prepare("UPDATE metodospago SET estado = 0 WHERE idMetodo = :idMetodo");
                $e->bindParam(':idMetodo', $this->idMetodo);
                $e->execute();
                
                $r['resultado'] = 'eliminar';
                $r['mensaje'] = 'Registro Eliminado';
            } catch (Exception $e) {
                $r['resultado'] = 'error';
                $r['mensaje'] =  $e->getMessage();
            }
        } else {
            $r['resultado'] = 'eliminar';
            $r['mensaje'] = 'Metodo de Pago no Registrada';
        }
        return $r;
    }

	//consultar

    function consultar(){
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try{
            $resultado = $co->query("SELECT * FROM metodospago WHERE estado = 1");
            if($resultado){
                $respuesta = '';
                foreach($resultado as $fila){
                    $respuesta .= "<tr>";
                    $respuesta .= "<td>" . $fila['nombreBanco'] . "</td>";
                    $respuesta .= "<td>" . $fila['cedulaTitular'] ."</td>";
                    $respuesta .= "<td>" . $fila['telefono'] . "</td>";
                    $respuesta .= "<td>" . $fila['cuenta'] . "</td>";
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

	//funcion para saber si ya el Metodo de Pago esta resgitrado
    function existe($cuenta){
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try{
            $resultado = $co->query("SELECT * FROM metodospago WHERE cuenta = '$cuenta' AND estado = 1");
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