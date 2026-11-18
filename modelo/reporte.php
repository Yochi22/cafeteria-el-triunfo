<?php

require_once('dompdf/vendor/autoload.php');

use Dompdf\Dompdf;

require_once('modelo/datos.php');

class reportes extends datos
{
    private $fecIni;
    private $fecFin;

    // Setters para el rango de fechas
    function set_fecIni($valor) { $this->fecIni = $valor; }
    function set_fecFin($valor) { $this->fecFin = $valor; }

    // Consulta las métricas de ventas, tasa de cambio y pedidos para la vista
    function consultarDatosReporte()
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();

        try {
            $ventas = "1=1";
            $params = array();

            // Construcción dinámica del filtro de fechas
            if (!empty($this->fecIni) && !empty($this->fecFin)) {
                $ventas = "v.fechaVenta BETWEEN :fechaIni AND :fechaFin";
                $params[':fechaIni'] = $this->fecIni;
                $params[':fechaFin'] = $this->fecFin;
            } elseif (!empty($this->fecIni)) {
                $ventas = "v.fechaVenta >= :fechaIni";
                $params[':fechaIni'] = $this->fecIni;
            } elseif (!empty($this->fecFin)) {
                $ventas = "v.fechaVenta <= :fechaFin";
                $params[':fechaFin'] = $this->fecFin;
            }

            // Obtener total y promedio de ventas en dólares
            $stmtV = $co->prepare("SELECT SUM(v.totalVenta) AS totalVen, AVG(v.totalVenta) AS promedio FROM ventas v WHERE " . $ventas);
            $stmtV->execute($params);
            $resV = $stmtV->fetch(PDO::FETCH_ASSOC);

            $totalVen = $resV['totalVen'] ?? 0;
            $promedio = $resV['promedio'] ?? 0;

            // Obtener la tasa del período o la más reciente
            if (!empty($this->fecIni) && !empty($this->fecFin)) {
                $stmtT = $co->prepare("SELECT precioTasa, fechaTasa FROM tasadiaria WHERE fechaTasa BETWEEN :fechaIni AND :fechaFin ORDER BY fechaTasa DESC LIMIT 1");
                $stmtT->execute([':fechaIni' => $this->fecIni, ':fechaFin' => $this->fecFin]);
                $resT = $stmtT->fetch(PDO::FETCH_ASSOC);
            } else {
                $stmtT = $co->query("SELECT precioTasa, fechaTasa FROM tasadiaria ORDER BY fechaTasa DESC LIMIT 1");
                $resT = $stmtT->fetch(PDO::FETCH_ASSOC);
            }

            $ultimaTasa = $resT['precioTasa'] ?? 0;
            $ultimaFecTasa = $resT['fechaTasa'] ?? '';

            // Respaldo: si no hay tasa en el rango, tomar la última registrada
            if ($ultimaTasa == 0) {
                $stmtT2 = $co->query("SELECT precioTasa, fechaTasa FROM tasadiaria ORDER BY fechaTasa DESC LIMIT 1");
                $resT2 = $stmtT2->fetch(PDO::FETCH_ASSOC);
                $ultimaTasa = $resT2['precioTasa'] ?? 0;
                $ultimaFecTasa = $resT2['fechaTasa'] ?? '';
            }

            // Conversión de montos a Bolívares
            $totalVenBs = $totalVen * $ultimaTasa;
            $promedioBs = $promedio * $ultimaTasa;

            // Conteo de pedidos agrupados por estado
            $stmtP = $co->prepare("SELECT p.estadoPedido, COUNT(*) AS total FROM pedidos p LEFT JOIN pedidosventa pv ON p.idPedido = pv.idPedido LEFT JOIN ventas v ON pv.idVenta = v.idVenta WHERE " . $ventas . " GROUP BY p.estadoPedido");
            $stmtP->execute($params);
            $estados = $stmtP->fetchAll(PDO::FETCH_ASSOC);

            $pedFinalizados = 0;
            $pedCancelados = 0;
            $pedProceso = 0;

            foreach ($estados as $est) {
                if ($est['estadoPedido'] == 'Finalizado') $pedFinalizados = intval($est['total']);
                if ($est['estadoPedido'] == 'Cancelado') $pedCancelados = intval($est['total']);
                if ($est['estadoPedido'] == 'En Proceso') $pedProceso = intval($est['total']);
            }

            // Formateo de respuesta exitosa
            $r['resultado'] = 'exito';
            $r['totalVen'] = "$ " . number_format($totalVen, 2);
            $r['promedio'] = "$ " . number_format($promedio, 2);
            $r['totalVenBs'] = "Bs. " . number_format($totalVenBs, 2, ',', '.');
            $r['promedioBs'] = "Bs. " . number_format($promedioBs, 2, ',', '.');
            $r['tasaReporte'] = "Bs. " . number_format($ultimaTasa, 2, ',', '.');
            $r['fechaTasaReporte'] = !empty($ultimaFecTasa) ? date("d/m/Y", strtotime($ultimaFecTasa)) : "No registrada";
            $r['pedFinalizados'] = $pedFinalizados;
            $r['pedProceso'] = $pedProceso;
            $r['pedCancelados'] = $pedCancelados;
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }

        return $r;
    }

    // Genera y renderiza el reporte en formato PDF
    function generarPDF()
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $ventas = "1=1";
            $params = array();

            // Construcción dinámica del filtro de fechas
            if (!empty($this->fecIni) && !empty($this->fecFin)) {
                $ventas = "v.fechaVenta BETWEEN :fechaIni AND :fechaFin";
                $params[':fechaIni'] = $this->fecIni;
                $params[':fechaFin'] = $this->fecFin;
            } elseif (!empty($this->fecIni)) {
                $ventas = "v.fechaVenta >= :fechaIni";
                $params[':fechaIni'] = $this->fecIni;
            } elseif (!empty($this->fecFin)) {
                $ventas = "v.fechaVenta <= :fechaFin";
                $params[':fechaFin'] = $this->fecFin;
            }

            // Obtener total y promedio de ventas en dólares
            $stmtV = $co->prepare("SELECT SUM(v.totalVenta) AS totalVen, AVG(v.totalVenta) AS promedio FROM ventas v WHERE " . $ventas);
            $stmtV->execute($params);
            $resV = $stmtV->fetch(PDO::FETCH_ASSOC);

            $totalVen = $resV['totalVen'] ?? 0;
            $promedio = $resV['promedio'] ?? 0;

            // Obtener la tasa del período o la más reciente
            if (!empty($this->fecIni) && !empty($this->fecFin)) {
                $stmtT = $co->prepare("SELECT precioTasa, fechaTasa FROM tasadiaria WHERE fechaTasa BETWEEN :fechaIni AND :fechaFin ORDER BY fechaTasa DESC LIMIT 1");
                $stmtT->execute([':fechaIni' => $this->fecIni, ':fechaFin' => $this->fecFin]);
                $resT = $stmtT->fetch(PDO::FETCH_ASSOC);
            } else {
                $stmtT = $co->query("SELECT precioTasa, fechaTasa FROM tasadiaria ORDER BY fechaTasa DESC LIMIT 1");
                $resT = $stmtT->fetch(PDO::FETCH_ASSOC);
            }

            $ultimaTasa = $resT['precioTasa'] ?? 0;
            $ultimaFecTasa = $resT['fechaTasa'] ?? '';

            // Respaldo: si no hay tasa en el rango, tomar la última registrada
            if ($ultimaTasa == 0) {
                $stmtT2 = $co->query("SELECT precioTasa, fechaTasa FROM tasadiaria ORDER BY fechaTasa DESC LIMIT 1");
                $resT2 = $stmtT2->fetch(PDO::FETCH_ASSOC);
                $ultimaTasa = $resT2['precioTasa'] ?? 0;
                $ultimaFecTasa = $resT2['fechaTasa'] ?? '';
            }

            // Conversión de montos a Bolívares
            $totalVenBs = $totalVen * $ultimaTasa;
            $promedioBs = $promedio * $ultimaTasa;

            // Conteo de pedidos agrupados por estado
            $stmtP = $co->prepare("SELECT p.estadoPedido, COUNT(*) AS total FROM pedidos p LEFT JOIN pedidosventa pv ON p.idPedido = pv.idPedido LEFT JOIN ventas v ON pv.idVenta = v.idVenta WHERE " . $ventas . " GROUP BY p.estadoPedido");
            $stmtP->execute($params);
            $estados = $stmtP->fetchAll(PDO::FETCH_ASSOC);

            $pedFinalizados = 0;
            $pedCancelados = 0;
            $pedProceso = 0;

            foreach ($estados as $est) {
                if ($est['estadoPedido'] == 'Finalizado') $pedFinalizados = intval($est['total']);
                if ($est['estadoPedido'] == 'Cancelado') $pedCancelados = intval($est['total']);
                if ($est['estadoPedido'] == 'En Proceso') $pedProceso = intval($est['total']);
            }

            // Construcción de la estructura HTML
            $html = "<html><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8'/><style>";
            $html .= "body { font-family: 'DejaVu Sans', Helvetica, Arial, sans-serif; color: #2c3e50; font-size: 13px; }";
            $html .= ".header { text-align: center; margin-bottom: 25px; border-bottom: 2px solid #34495e; padding-bottom: 10px; }";
            $html .= ".header h2 { color: #d35400; margin: 0; font-size: 20px; text-transform: uppercase; }";
            $html .= ".metrics-box { margin-bottom: 15px; padding: 15px; background: #f8f9fa; border: 1px solid #bdc3c7; line-height: 1.8; }";
            $html .= "</style></head><body>";

            // Encabezado del reporte
            $html .= "<div class='header'>";
            $html .= "<h2>Cafetería El Triunfo</h2>";
            $html .= "<p>Reporte Resumido de Ventas, Ganancias y Estado de Pedidos</p>";

            if (!empty($this->fecIni) || !empty($this->fecFin)) {
                $html .= "<p>Período filtrado: " . ($this->fecIni ?: 'Inicio') . " al " . ($this->fecFin ?: 'Actualidad') . "</p>";
            }

            $html .= "</div>";

            // Caja de métricas principales
            $html .= "<div class='metrics-box'>";
            $html .= "<strong>Total de Ventas en Dólares:</strong> $ " . number_format($totalVen, 2) . "<br>";
            $html .= "<strong>Total de Ventas en Bolívares:</strong> Bs. " . number_format($totalVenBs, 2, ',', '.') . "<br>";
            $html .= "<strong>Promedio de Ganancias en Dólares:</strong> $ " . number_format($promedio, 2) . "<br>";
            $html .= "<strong>Promedio de Ganancias en Bolívares:</strong> Bs. " . number_format($promedioBs, 2, ',', '.') . "<br>";
            $html .= "<strong>Tasa aplicada:</strong> Bs. " . number_format($ultimaTasa, 2, ',', '.') . " (" . (!empty($ultimaFecTasa) ? date("d/m/Y", strtotime($ultimaFecTasa)) : "Sin fecha") . ")<br>";
            $html .= "<strong>Pedidos Finalizados:</strong> " . $pedFinalizados . "<br>";
            $html .= "<strong>Pedidos en Proceso:</strong> " . $pedProceso . "<br>";
            $html .= "<strong>Pedidos Cancelados:</strong> " . $pedCancelados;
            $html .= "</div>";

            $html .= "</body></html>";
        } catch (Exception $e) {
            $html = "<html><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8'/></head><body><h3>Error: " . $e->getMessage() . "</h3></body></html>";
        }

        // Configuración, renderizado y salida directa del PDF
        $pdf = new Dompdf();
        $pdf->setPaper("A4", "portrait");
        $pdf->loadHtml($html);
        $pdf->render();
        $pdf->stream('Reporte.pdf', array("Attachment" => false));
    }
}

?>