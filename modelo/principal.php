<?php

require_once("modelo/datos.php");

class principal extends datos
{
    public function consultarHorarios()
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $stmt = $co->query("SELECT * FROM horario");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return array();
        }
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
            $stmt = $co->prepare("UPDATE horario SET horaApertura = :apertura, horaCierre = :cierre WHERE idHorario = :id");
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
        try {
            $stmt = $co->query("SELECT * FROM precioDelivery LIMIT 1");
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return array();
        }
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

            $campo = "";
            switch ($zona) {
                case 'Este':
                    $campo = 'precioEste';
                    break;
                case 'Oeste':
                    $campo = 'precioOeste';
                    break;
                case 'Norte':
                    $campo = 'precioNorte';
                    break;
                case 'Sur':
                    $campo = 'precioSur';
                    break;
                case 'Centro':
                    $campo = 'precioCentro';
                    break;
                default:
                    throw new Exception('Zona no válida.');
            }
            $stmt = $co->prepare("UPDATE precioDelivery SET $campo = :precio WHERE idPrecioDelivery = 1");
            $stmt->execute([':precio' => $precio]);
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