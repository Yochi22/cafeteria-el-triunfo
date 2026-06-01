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
		// Método de Pago
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $stmt = $co->prepare("SELECT idMetodo, estado FROM metodospago WHERE cuenta = :cuenta");
            $stmt->bindParam(':cuenta', $this->cuenta);
            $stmt->execute();
            $metodo_existente = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($metodo_existente) {
                if ($metodo_existente['estado'] == 1) {
                    return "Ya Existe un Método de Pago con ese Número de Cuenta";
                } else {
                    $m = $co->prepare("UPDATE metodospago SET nombreBanco = :nombreBanco, cedulaTitular = :cedulaTitular, telefono = :telefono, cuenta = :cuenta, estado = 1 WHERE idMetodo = :idMetodo");
                    $m->bindParam(':idMetodo', $metodo_existente['idMetodo']);
                    $m->bindParam(':nombreBanco', $this->nombreBanco);
					$m->bindParam(':cedulaTitular', $this->cedulaTitular);
					$m->bindParam(':telefono', $this->telefono);
                    $m->execute();

                    return "Método de Pago Incluido";
                }
            } else {
                $r = $co->prepare("INSERT INTO metodospago(idMetodo, nombreBanco, cedulaTitular, telefono, cuenta) 
                                   VALUES(:idMetodo, :nombreBanco, :cedulaTitular, :telefono, :cuenta)");

                $r->bindParam(':idMetodo', $this->idMetodo);
                $r->bindParam(':nombreBanco', $this->nombreBanco);
                $r->bindParam(':cedulaTitular', $this->cedulaTitular);
                $r->bindParam(':telefono', $this->telefono);
				$r->bindParam(':cuenta', $this->cuenta);
                $r->execute();

                return "Método de Pago Incluido";
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

	//modificar
	function modificar()
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            if ($this->existe_cuenta($this->cuenta)) {

                $m = $co->prepare("UPDATE metodospago SET nombreBanco = :nombreBanco, cedulaTitular = :cedulaTitular, telefono = :telefono, cuenta = :cuenta WHERE idMetodo = :idMetodo");

                $m->bindParam(':idMetodo', $this->idMetodo);
                $m->bindParam(':nombreBanco', $this->nombreBanco);
                $m->bindParam(':cedulaTitular', $this->cedulaTitular);
                $m->bindParam(':telefono', $this->telefono);
                $m->bindParam(':cuenta', $this->cuenta);
                $m->bindParam(':estado', $this->estado);
                $m->execute();
                return "Método de Pago modificado";
            } else {
                return "No existe ese Método de Pago.";
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

	//eliminar
	function eliminar()
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            if ($this->existe_id($this->idMetodo)) {
                $e = $co->prepare("UPDATE metodospago SET estado = 0 WHERE idMetodo = :idMetodo");
                $e->bindParam(':idCategoria', $this->idMetodo);
                $e->execute();
                return "Método de Pago Eliminado";
            } else {
                return "No existe ese Método de Pago";
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

	//consultar

	function consultar()
    {
        try {
            $co = $this->conecta();
            $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $resultado = $co->query("SELECT * FROM categorias WHERE estado = 1");
            
            if ($resultado) {
                $respuesta = '';
                if ($resultado) {
                $respuesta = '';
                foreach ($resultado as $r) {
                    $respuesta = $respuesta."<tr>";
					$respuesta = $respuesta."<td>";
                    $respuesta .= "<td>" . $r['codigo'] . "</td>";
                    $respuesta .= "<td>" . $r['nombre'] . "</td>";
                    $respuesta .= "<td>" . $r['fecha_nac'] . "</td>";
                    $respuesta .= "<td>" . $r['tipo'] . "</td>";
                    $respuesta .= "<td>" . $r['ced_dueño'] . "</td>";
                    $respuesta .= "<td>" . $r['nombre_dueño'] . "</td>";
                    $respuesta .= "<td>" . $r['direccion'] . "</td>";
                    $respuesta .= "<td>" . $r['telefono'] . "</td>";
                    $respuesta .= "<td>" . $r['correo'] . "</td>";
					$respuesta .= '
					<td class="py-4">
                        <div class="d-flex gap-4 fs-4">
                            <i class="bi bi-pencil-square text-primary" style="cursor:pointer" onclick="modificar('.$r['idMetodo'].')"></i>
                            <i class="bi bi-trash text-danger" style="cursor:pointer" onclick="eliminar('.$r['idMetodo'].')"></i>
                        </div>
                    </td>
					</div>';
                }
                return $respuesta;
            } else {
                return '';
            }
		}
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

	//funciones internas
	private function existe_cuenta($cuenta)
    {
        try {
            $co = $this->conecta();
            $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $co->prepare("SELECT idMetodo FROM metodospago WHERE cuenta = ?");
            $stmt->execute([$cuenta]);
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            return false;
        }
    }

	private function existe_id($id)
    {
        try {
            $co = $this->conecta();
            $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $co->prepare("SELECT idMetodo FROM metodospago WHERE idMetodo = ?");
            $stmt->execute([$id]);
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            return false;
        }
    }

	function consultar_id()
    {
        try {
            $co = $this->conecta();
            $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $co->prepare("SELECT * FROM metodospago WHERE idMetodo = ?");
            $stmt->execute([$this->idMetodo]);
            $fila = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($fila) {
                $envia = array('resultado' => 'encontro');
                $envia = array_merge($envia, $fila);
                return json_encode($envia);
            } else {
                return json_encode(array('resultado' => 'noencontro'));
            }
        } catch (Exception $e) {
            return json_encode(array('resultado' => $e->getMessage()));
        }
    }
}
?>