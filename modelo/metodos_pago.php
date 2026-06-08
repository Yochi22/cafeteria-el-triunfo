<?php
require_once("modelo/datos.php");

class metodos_pago extends datos
{
    private $idMetodo;
    private $nombreBanco;
    private $cedulaTitular;
    private $telefono;
    private $cuenta;

    function set_idMetodo($valor) { $this->idMetodo = $valor; }
    function set_nombreBanco($valor) { $this->nombreBanco = $valor; }
    function set_cedulaTitular($valor) { $this->cedulaTitular = $valor; }
    function set_telefono($valor) { $this->telefono = $valor; }
    function set_cuenta($valor) { $this->cuenta = $valor; }

    function get_idMetodo() { return $this->idMetodo; }
    function get_nombreBanco() { return $this->nombreBanco; }
    function get_cedulaTitular() { return $this->cedulaTitular; }
    function get_telefono() { return $this->telefono; }
    function get_cuenta() { return $this->cuenta; }

    private function existe($idMetodo)
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $st = $co->prepare("SELECT idMetodo FROM metodospago WHERE idMetodo = :idMetodo AND estado = 1");
            $st->bindParam(':idMetodo', $idMetodo);
            $st->execute();
            $fila = $st->fetch(PDO::FETCH_ASSOC);
            return $fila ? true : false;
        } catch (Exception $e) {
            return false;
        }
    }

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
                    $r['resultado'] = 'error';
                    $r['mensaje'] = 'Ya existe un método de pago con ese número de cuenta.';
                } else {
                    $m = $co->prepare("UPDATE metodospago SET nombreBanco = :nombreBanco, cedulaTitular = :cedulaTitular, telefono = :telefono, estado = 1 WHERE idMetodo = :idMetodo");
                    $m->bindParam(':idMetodo', $metodo_existente['idMetodo']);
                    $m->bindParam(':nombreBanco', $this->nombreBanco);
                    $m->bindParam(':cedulaTitular', $this->cedulaTitular);
                    $m->bindParam(':telefono', $this->telefono);
                    $m->execute();
                    
                    $r['resultado'] = 'incluir';
                    $r['mensaje'] = 'Registro Incluido';
                }
            } else {
                $m = $co->prepare("INSERT INTO metodospago(nombreBanco, cedulaTitular, telefono, cuenta, estado) VALUES(:nombreBanco, :cedulaTitular, :telefono, :cuenta, 1)");
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
                    $r['resultado'] = 'error';
                    $r['mensaje'] = 'Ya existe otro método de pago con ese número de cuenta.';
                    return $r;
                }

                $m = $co->prepare("UPDATE metodospago SET nombreBanco = :nombreBanco, cedulaTitular = :cedulaTitular, telefono = :telefono, cuenta = :cuenta WHERE idMetodo = :idMetodo");
                $m->bindParam(':idMetodo', $this->idMetodo);
                $m->bindParam(':nombreBanco', $this->nombreBanco);
                $m->bindParam(':cedulaTitular', $this->cedulaTitular);
                $m->bindParam(':telefono', $this->telefono);
                $m->bindParam(':cuenta', $this->cuenta);
                $m->execute();

                $r['resultado'] = 'modificar';
                $r['mensaje'] = 'Registro Modificado';
            } catch (Exception $e) {
                $r['resultado'] = 'error';
                $r['mensaje'] =  $e->getMessage();
            }
        } else {
            $r['resultado'] = 'error';
            $r['mensaje'] =  'Método de pago no registrado.';
        }
        return $r;
    }

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
            $r['resultado'] = 'error';
            $r['mensaje'] = 'Método de pago no registrado.';
        }
        return $r;
    }

    function consultar()
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try {
            $resultado = $co->query("SELECT * FROM metodospago WHERE estado = 1");
            if ($resultado) {
                $respuesta = '';
                foreach ($resultado as $fila) {
                    $respuesta .= "<tr>";
                    $respuesta .= "<td style='display:none;'>" . $fila['idMetodo'] . "</td>";
                    $respuesta .= "<td>" . $fila['nombreBanco'] . "</td>";
                    $respuesta .= "<td>" . $fila['cedulaTitular'] ."</td>";
                    $respuesta .= "<td>" . $fila['telefono'] . "</td>";
                    $respuesta .= "<td>" . $fila['cuenta'] . "</td>";
                    $respuesta .= "<td class='text-center'>";
                    $respuesta .= "<button type='button' class='btn text-white w-80 small-width me-1' style='background-color: #FF8C00;' onclick='pone(this,0)'><i class='bi bi-pencil-square'></i> Modificar</button>";
                    $respuesta .= "<button type='button' class='btn text-white w-80 small-width ms-1' style='background-color: #FF8C00;' onclick='pone(this,1)'><i class='bi bi-trash-fill'></i> Eliminar</button>";
                    $respuesta .= "</td>";
                    $respuesta .= "</tr>";
                }
                $r['resultado'] = 'consultar';
                $r['mensaje'] = $respuesta;
            } else {
                $r['resultado'] = 'consultar';
                $r['mensaje'] = '<tr><td colspan="5" class="text-center py-4">No hay registros disponibles</td></tr>';
            }
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }
}
?>