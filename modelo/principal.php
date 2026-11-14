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

    // Funcion para consultar si ya se registro una tasa del dia
    public function obtenerTasaHoy()
    {
    $co = $this->conecta();
    $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $r = array();
    try {
        date_default_timezone_set('America/Caracas');
        $fechaDb = date('Y-m-d');
        $fechaMostrar = date('d-m-Y');

        $resultado = $co->prepare("SELECT * FROM tasadiaria WHERE fechaTasa = :fechaTasa");
        $resultado->bindParam(':fechaTasa', $fechaDb);
        $resultado->execute();
        $fila = $resultado->fetch(PDO::FETCH_ASSOC);

        if ($fila) {
            $precioTasa = $fila['precioTasa'];
            $precioFormateado = number_format($precioTasa, 2, ',', '.');
            
            $html = '';
            $html .= '
                <div class="fs-4 text-success mb-2">Bs. ' . $precioFormateado . '</div>
                <div class="fs-6 text-muted fw-normal" style="font-size: 0.8rem;">Actualizado el: ' . $fechaMostrar . '</div>';
            
            $r['resultado'] = 'mostrarTasa';
            $r['mensaje'] = $html;
        } else {

            $html = '';
            $html .= '
            <h5 class="text-danger mb-2">¡API no disponible!</h5>
            <p class="text-muted small mb-3">No hay conexión a internet y no se ha registrado ninguna tasa hoy. Por favor, ingresar manualmente:</p>
                        
                    <form id="formTasaManual">
                    <div class="mb-3 text-start">
                        <label for="tasaManual" class="form-label small fw-bold">Tasa del Dólar (Bs):</label>
                        <input type="number" step="0.01" class="form-control rounded-3" id="tasaManual" name="tasaManual" placeholder="00.00 Bs." required>
                        <span id="stasaManual" class="text-danger small"></span>
                    </div>
                        <button type="submit" class="btn btn-crear btn-sm d-block mx-auto">Guardar Tasa</button>
                    </form>';

            $r['resultado'] = 'noExisteTasa';
            $r['mensaje'] = $html;
        }
    } catch (Exception $e) {
        $r['resultado'] = 'error';
        $r['mensaje'] = $e->getMessage();
    }
    return $r;
}

    // Funcion para Guardar Tasa
    public function guardarTasa($precioTasa)
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        
        try {
            date_default_timezone_set('America/Caracas');
            
            $fechaDb = date('Y-m-d');      
            $fechaMostrar = date('d-m-Y'); 
                
            $stmt = $co->prepare("SELECT precioTasa FROM tasadiaria WHERE fechaTasa = :fechaDb");
            $stmt->bindParam(':fechaDb', $fechaDb);
            $stmt->execute();
            $fila = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($fila) {
                $precioRegistrado = $fila['precioTasa'];
                $precioFormateado = number_format($precioRegistrado, 2, ',', '.');
                
                $html = '
                    <div class="fs-4 text-success mb-2">Bs. ' . $precioFormateado . '</div>
                    <div class="fs-6 text-muted fw-normal" style="font-size: 0.8rem;">Actualizado el: ' . $fechaMostrar . '</div>
                ';
                $r['resultado'] = 'guardarTasa';
                $r['mensaje'] = $html;
                return $r; 
            }
        
            $inc = $co->prepare("INSERT INTO tasadiaria(precioTasa, fechaTasa) 
            VALUES (:precioTasa, :fechaDb)");

            $inc->bindParam(':precioTasa', $precioTasa);
            $inc->bindParam(':fechaDb', $fechaDb);
            $inc->execute();

            $precioFormateado = number_format($precioTasa, 2, ',', '.');
            $html = '
                <div class="fs-4 text-success mb-2">Bs. ' . $precioFormateado . '</div>
                <div class="fs-6 text-muted fw-normal" style="font-size: 0.8rem;">Actualizado el: ' . $fechaMostrar . '</div>
            ';

            $r['resultado'] = 'guardarTasa';
            $r['mensaje'] = $html;

        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = '
                <h5 class="text-danger mb-2">¡Error en la Base de Datos!</h5>
                <p class="text-muted small">' . $e->getMessage() . '</p>
            ';
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