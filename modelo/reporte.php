<?php

require_once('dompdf/vendor/autoload.php');

use Dompdf\Dompdf;

require_once('modelo/datos.php');

class reportes extends datos
{
    private $fecIni;
    private $fecFin;

    function set_fecIni($valor) { $this->fecIni = $valor; }
    function set_fecFin($valor) { $this->fecFin = $valor; }

    // Función de consulta a ventas, pedidos y la tasa
    function consultarDatosReporte()
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();

        try {
            $ventas = "1=1";
            $params = array();

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

            // Obtener el total y promedio de ventas en dolares
            $stmtV = $co->prepare("SELECT SUM(v.totalVenta) AS totalVen, AVG(v.totalVenta) AS promedio FROM ventas v WHERE " . $ventas);
            $stmtV->execute($params);
            $resV = $stmtV->fetch(PDO::FETCH_ASSOC);

            $totalVen = $resV['totalVen'] ?? 0;
            $promedio = $resV['promedio'] ?? 0;

            // Obtener la tasa
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

            // Por si acaso el filtrado por fecha no tiene tasa en esos días
            if ($ultimaTasa == 0) {
                $stmtT2 = $co->query("SELECT precioTasa, fechaTasa FROM tasadiaria ORDER BY fechaTasa DESC LIMIT 1");
                $resT2 = $stmtT2->fetch(PDO::FETCH_ASSOC);
                $ultimaTasa = $resT2['precioTasa'] ?? 0;
                $ultimaFecTasa = $resT2['fechaTasa'] ?? '';
            }

            // Calcular los Bolívares
            $totalVenBs = $totalVen * $ultimaTasa;
            $promedioBs = $promedio * $ultimaTasa;

            // Contar pedidos finalizados y cancelados
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

    // Función para crear el PDF
    function generarPDF()
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $ventas = "1=1";
            $params = array();

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

            // Consulta para calcular el promedio de ventas en dolares
            $stmtV = $co->prepare("SELECT SUM(v.totalVenta) as totalVen, AVG(v.totalVenta) as promedio FROM ventas v WHERE " . $ventas);
            $stmtV->execute($params);
            $resV = $stmtV->fetch(PDO::FETCH_ASSOC);

            $totalVen = $resV['totalVen'] ?? 0;
            $promedio = $resV['promedio'] ?? 0;

            // Consulta para obtener la tasa por rango de fechas
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

            // Si no hay registro de tasa en el rango de fechas se muestra la última que hubo por defecto
            if ($ultimaTasa == 0) {
                $stmtT2 = $co->query("SELECT precioTasa, fechaTasa FROM tasadiaria ORDER BY fechaTasa DESC LIMIT 1");
                $resT2 = $stmtT2->fetch(PDO::FETCH_ASSOC);
                $ultimaTasa = $resT2['precioTasa'] ?? 0;
                $ultimaFecTasa = $resT2['fechaTasa'] ?? '';
            }

            // Cálculo de total de ventas en Bs
            $totalVenBs = $totalVen * $ultimaTasa;
            $promedioBs = $promedio * $ultimaTasa;


            // Consulta de los pedidos cancelados y finalizados
            $stmtP = $co->prepare("SELECT p.estadoPedido, COUNT(*) as total FROM pedidos p LEFT JOIN pedidosventa pv ON p.idPedido = pv.idPedido LEFT JOIN ventas v ON pv.idVenta = v.idVenta WHERE " . $ventas . " GROUP BY p.estadoPedido");
            $stmtP->execute($params);
            $estados = $stmtP->fetchAll(PDO::FETCH_ASSOC);

            $pedFinalizados = 0;
            $pedCancelados = 0;

            // Bucle para generar el PDF
            foreach ($estados as $est) {
                if ($est['estadoPedido'] == 'Finalizado') $pedFinalizados = intval($est['total']);
                if ($est['estadoPedido'] == 'Cancelado') $pedCancelados = intval($est['total']);
                if ($est['estadoPedido'] == 'En Proceso') $pedProceso = intval($est['total']);
            }

            $html = "<html><head><meta charset='utf-8'><style>";
            $html .= "body { font-family: Helvetica, Arial, sans-serif; color: #2c3e50; font-size: 13px; }";
            $html .= ".header { text-align: center; margin-bottom: 25px; border-bottom: 2px solid #34495e; padding-bottom: 10px; }";
            $html .= ".header h2 { color: #d35400; margin: 0; font-size: 20px; text-transform: uppercase; }";
            $html .= ".metrics-box { margin-bottom: 15px; padding: 15px; background: #f8f9fa; border: 1px solid #bdc3c7; line-height: 1.8; }";
            $html .= "</style></head><body>";

            $html .= "<div class='header'>";
            $html .= "<h2>Cafeteria El Triunfo</h2>";
            $html .= "<p>Reporte Resumido de Ventas, Ganancias y Estado de Pedidos</p>";

            if (!empty($this->fecIni) || !empty($this->fecFin)) {
                $html .= "<p>Periodo filtrado: " . ($this->fecIni ?: 'Inicio') . " al " . ($this->fecFin ?: 'Actualidad') . "</p>";
            }

            $html .= "</div>";

            $html .= "<div class='metrics-box'>";
            $html .= "<strong>Total de Ventas en Dolares:</strong> $ " . number_format($totalVen, 2) . "<br>";
            $html .= "<strong>Total de Ventas en Bolivares:</strong> Bs. " . number_format($totalVenBs, 2, ',', '.') . "<br>";
            $html .= "<strong>Promedio de Ganancias en Dolares:</strong> $ " . number_format($promedio, 2) . "<br>";
            $html .= "<strong>Promedio de Ganancias en Bolivares:</strong> $ " . number_format($promedioBs, 2) . "<br>";
            $html .= "<strong>Tasa aplicada:</strong> Bs. " . number_format($ultimaTasa, 2, ',', '.') . " (" . (!empty($ultimaFecTasa) ? date("d/m/Y", strtotime($ultimaFecTasa)) : "Sin fecha") . ")<br>";
            $html .= "<strong>Pedidos Finalizados:</strong> " . $pedFinalizados . "<br>";
            $html .= "<strong>Pedidos Cancelados:</strong> " . $pedCancelados;
            $html .= "</div>";

            $html .= "</body></html>";
        } catch (Exception $e) {
            $html = "<html><body><h3>Error: " . $e->getMessage() . "</h3></body></html>";
        }

        // Generar, renderizar y mostrar el archivo PDF en el navegador
        $pdf = new DOMPDF();
        $pdf->set_paper("A4", "portrait");
        $pdf->load_html(utf8_decode($html));
        $pdf->render();
        $pdf->stream('reporte.pdf', array("Attachment" => false));
    }
}

?>