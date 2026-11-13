<?php

require_once("modelo/datos.php");

class principal extends datos
{
    // Función para ver si la Tasa ya existe
    public function existe($fechaTasa)
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $resultado = $co->prepare("SELECT * FROM tasadiaria WHERE fechaTasa = :fechaTasa");
            $resultado->bindParam(':fechaTasa', $fechaTasa);
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

    // Función para guardar Tasa
    public function guardarTasa($precioTasa)
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try {
            $fecha = date('Y-m-d');
                    if ($this->existe($fecha)) {
                    $r['resultado'] = 'error';
                    $r['mensaje'] = '¡La tasa del día de hoy ya está registrada!';
                    return $r; 
                }
            
            $inc = $co->prepare("INSERT INTO tasadiaria(precioTasa, fechaTasa) 
            VALUES (:precioTasa, :fecha)");

            $inc->bindParam(':precioTasa', $precioTasa);
            $inc->bindParam(':fecha', $fecha);
            $inc->execute();

                $r['resultado'] = 'guardarTasa';
                $r['mensaje'] = 'Cuenta registrada con éxito.';
            } catch (Exception $e) {
                $r['resultado'] = 'error';
                $r['mensaje'] = $e->getMessage();
            }

        return $r;
    }

public function consultarHorarios()
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();

        try {
            $stmt = $co->query("SELECT * FROM horarios");

            $r['resultado'] = 'consultar_horarios';
            $r['mensaje'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }

        return $r;
    }

    public function modificarHorario($idHorario, $horaApertura, $horaCierre)
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();

        try {
            if (empty($horaApertura) || empty($horaCierre)) {
                throw new Exception('Debe completar ambas horas.');
            }

            $stmt = $co->prepare("UPDATE horarios SET horaApertura = :apertura, horaCierre = :cierre WHERE idHorario = :id");
            $stmt->execute([':apertura' => $horaApertura, ':cierre' => $horaCierre, ':id' => $idHorario]);

            $r['resultado'] = 'modificar_horario';
            $r['mensaje'] = 'Horario actualizado con éxito.';
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }

        return $r;
    }

    public function consultarPreciosDelivery()
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();

        try {
            $stmt = $co->query("SELECT * FROM preciosdelivery");

            $r['resultado'] = 'consultar_precios';
            $r['mensaje'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }

        return $r;
    }

    public function modificarPrecioDelivery($zona, $precio)
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();

        try {
            if ($precio <= 0) {
                throw new Exception('El precio debe ser mayor a cero.');
            }

            $stmtCheck = $co->prepare("SELECT COUNT(*) FROM preciosdelivery WHERE nombreZona = :zona");
            $stmtCheck->execute([':zona' => $zona]);

            if ($stmtCheck->fetchColumn() == 0) {
                throw new Exception('Zona no válida.');
            }

            $stmt = $co->prepare("UPDATE preciosdelivery SET precio = :precio WHERE nombreZona = :zona");
            $stmt->execute([':precio' => $precio, ':zona' => $zona]);

            $r['resultado'] = 'modificar_precio';
            $r['mensaje'] = 'Precio de delivery actualizado con éxito.';
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }

        return $r;
    }
}

?>