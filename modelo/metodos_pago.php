<?php
require_once('modelo/datos.php');

class metodopago extends datos{
	
    private $idMetodo;
	private $cedulaTitular; 
	private $nombreBanco;
	private $telefono;
	private $cuenta;
	
	//setters
	function set_idMetodo($valor){
		$this->idMetodo = $valor;
	}
	function set_cuenta($valor){
		$this->cuenta = $valor;
	}
	function set_nombreBanco($valor){
		$this->nombreBanco = $valor;
	}
	function set_cedulaTitular($valor){
		$this->cedulaTitular = $valor; 
	}
	
	function set_telefono($valor){
		$this->telefono = $valor;
	}
	
	
	//getters
	function get_idMetodo(){
		return $this->idMetodo;
	}
	
	function get_cuenta(){
		return $this->cuenta;
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
	
	
	//metodos para CRUD
	//1. Registrar
	function incluir(){
		
		if(!$this->existe($this->cuenta)){
			//verificacion de la existencia del numero de cuenta, en caso que no exista, se registrara el Metodo.
			$co = $this->conecta();
			$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			try {
					$co->query("Insert into metodopago(
						cuenta,
						nombreBanco,
						cedulaTitular,
						telefono
						)
						Values(
						'$this->cuenta',
						'$this->nombreBanco',
						'$this->cedulaTitular',
						'$this->telefono'
						)");
						return "Método de pago registrado con éxito.";
			} catch(Exception $e) {
				return $e->getMessage();
			}
		}
		else{
			return "Ya existe la Cuenta que desea ingresar.";
		}
	}
	
	//2. Modificar
	function modificar(){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		if($this->existe($this->cuenta)){
			try {
					$co->query("Update metodopago set 
					    cuenta = '$this->cuenta',
						cedulaTitular = '$this->cedulaTitular',
						nombreBanco = '$this->nombreBanco',
						telefono = '$this->telefono'
						where
						cuenta = '$this->cuenta'
						");
						return "Registro Modificado.";
			} catch(Exception $e) {
				return $e->getMessage();
			}
		}
		else{
			return "Cuenta no registrada.";
		}
		
	}
	
	//3. Eliminar
	function eliminar(){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		if($this->existe($this->cedulaTitular)){
			try {
					$co->query("delete from metodopago 
						where
						cuenta = '$this->cuenta'
						");
						return "Registro Eliminado.";
			} catch(Exception $e) {
				return $e->getMessage();
			}
		}
		else{
			return "Cuenta no registrada.";
		}
	}

	//4. Consultar
	function consultar(){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try{
			
			$resultado = $co->query("Select * from metodopago");
			
			if($resultado){
				
				$respuesta = '';
				foreach($resultado as $r){
					$respuesta = $respuesta."<tr>";
					    $respuesta = $respuesta."<td>";
							$respuesta = $respuesta."<button type='button'
							class='btn btn-primary w-100 small-width mb-3' 
							onclick='pone(this,0)'
						    >Modificar</button><br/>";
							$respuesta = $respuesta."<button type='button'
							class='btn btn-primary w-100 small-width mt-2' 
							onclick='pone(this,1)'
						    >Eliminar</button><br/>";
						$respuesta = $respuesta."</td>";
						$respuesta = $respuesta."<td>";
							$respuesta = $respuesta.$r['nombreBanco'];
						$respuesta = $respuesta."</td>";
						$respuesta = $respuesta."<td>";
							$respuesta = $respuesta.$r['cedulaTitular'];
						$respuesta = $respuesta."</td>";
						$respuesta = $respuesta."<td>";
							$respuesta = $respuesta.$r['telefono'];
						$respuesta = $respuesta."</td>";
						$respuesta = $respuesta."<td>";
							$respuesta = $respuesta.$r['cuenta'];
						$respuesta = $respuesta."</td>";
					$respuesta = $respuesta."</tr>";
				}
				
			    $r['resultado'] = 'consultar';
				$r['mensaje'] =  $respuesta;
			}
			else{
				$r['resultado'] = 'consultar';
				$r['mensaje'] =  '';
			}
			
		}catch(Exception $e){
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage();
		}
		return $r;
	}
	
	//Funciones internas
	//Funcion para verificar si un registro existe.
	private function existe($cuenta){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		try{
			
			$resultado = $co->query("Select * from metodopago where cuenta='$cuenta'");
			
			
			$fila = $resultado->fetchAll(PDO::FETCH_BOTH);
			if($fila){

				return true;
			    
			}
			else{
				
				return false;;
			}
			
		}catch(Exception $e){
			return false;
		}
	}
		
	}
	
?>