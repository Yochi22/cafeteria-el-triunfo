<?php
require_once('dompdf/vendor/autoload.php'); 
use Dompdf\Dompdf; 

require_once('modelo/datos.php');

class rreportes extends datos {
    private $fecIni;
    private $fecFin;

    function set_fecIni($valor) { 
        $this->fecIni = $valor; 
    }
    function set_fecFin($valor) { 
        $this->fecFin = $valor; 
    }

    function consultarDatosReporte() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();

        try {
            $ventas = "1=1";
            $params = array();

            if(!empty($this->fecIni) && !empty($this->fecFin)) {
                $ventas = "v.fechaVenta BETWEEN :fechaIni AND :fechaFin";
                $params[':fechaIni'] = $this->fecIni;
                $params[':fechaFin'] = $this->fecFin;
            } elseif(!empty($this->fecIni)) {
                $ventas = "v.fechaVenta >= :fechaIni";
                $params[':fechaIni'] = $this->fecIni;
            } elseif(!empty($this->fecFin)) {
                $ventas = "v.fechaVenta <= :fechaFin";
                $params[':fechaFin'] = $this->fecFin;
            }

            //calculo del promedio de ventas
            $stmtV = $co->prepare("SELECT SUM(v.totalVenta) AS totalVen, AVG(v.totalVenta) AS promedio FROM ventas v WHERE " . $ventas);
            $stmtV->execute($params);
            $resV = $stmtV->fetch(PDO::FETCH_ASSOC);

            $totalVen = $resV['totalVen'] ?? 0;
            $promedio = $resV['promedio'] ?? 0;

            //contando cuantos pedidos cancelados y finalizados hay
            $stmtP = $co->prepare("SELECT p.estadoPedido, COUNT(*) AS total FROM pedidos p LEFT JOIN pedidosventa pv ON p.idPedido = pv.idPedido LEFT JOIN ventas v ON pv.idVenta = v.idVenta WHERE " . $ventas . " GROUP BY p.estadoPedido");
            $stmtP->execute($params);
            $estados = $stmtP->fetchAll(PDO::FETCH_ASSOC);

            $pedFinalizados = 0;
            $pedCancelados = 0;

            foreach($estados as $est) {
                if($est['estadoPedido'] == 'Finalizado') {
                    $pedFinalizados = intval($est['total']);
                } elseif($est['estadoPedido'] == 'Cancelado') {
                    $pedCancelados = intval($est['total']);
                }
            }

            $r['resultado'] = 'exito';
            $r['totalVen'] = "$ " . number_format($totalVen, 2);
            $r['promedio'] = "$ " . number_format($promedio, 2);
            $r['pedFinalizados'] = $pedFinalizados;
            $r['pedCancelados'] = $pedCancelados;

        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }

        return $r;
    }

    function generarPDF() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        try {
            $ventas = "1=1";
            $params = array();

            if(!empty($this->fecIni) && !empty($this->fecFin)) {
                $ventas = "v.fechaVenta BETWEEN :fechaIni AND :fechaFin";
                $params[':fechaIni'] = $this->fecIni;
                $params[':fechaFin'] = $this->fecFin;
            } elseif(!empty($this->fecIni)) {
                $ventas = "v.fechaVenta >= :fechaIni";
                $params[':fechaIni'] = $this->fecIni;
            } elseif(!empty($this->fecFin)) {
                $ventas = "v.fechaVenta <= :fechaFin";
                $params[':fechaFin'] = $this->fecFin;
            }

            $stmtV = $co->prepare("SELECT SUM(v.totalVenta) as totalVen, AVG(v.totalVenta) as promedio FROM ventas v WHERE " . $ventas);
            $stmtV->execute($params);
            $resV = $stmtV->fetch(PDO::FETCH_ASSOC);

            $stmtP = $co->prepare("SELECT p.estadoPedido, COUNT(*) as total FROM pedidos p LEFT JOIN pedidosventa pv ON p.idPedido = pv.idPedido LEFT JOIN ventas v ON pv.idVenta = v.idVenta WHERE " . $ventas . " GROUP BY p.estadoPedido");
            $stmtP->execute($params);
            $estados = $stmtP->fetchAll(PDO::FETCH_ASSOC);

            $pedFinalizados = 0;
            $pedCancelados = 0;
            foreach($estados as $est) {
                if($est['estadoPedido'] == 'Finalizado') $pedFinalizados = intval($est['total']);
                if($est['estadoPedido'] == 'Cancelado') $pedCancelados = intval($est['total']);
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
            if(!empty($this->fecIni) || !empty($this->fecFin)) {
                $html .= "<p>Periodo filtrado: " . ($this->fecIni ?: 'Inicio') . " al " . ($this->fecFin ?: 'Actualidad') . "</p>";
            }
            $html .= "</div>";

            $html .= "<div class='metrics-box'>";
            $html .= "<strong>Total de Ventas:</strong> $ " . number_format($resV['totalVen'] ?? 0, 2) . "<br>";
            $html .= "<strong>Promedio de Ganancias:</strong> $ " . number_format($resV['promedio'] ?? 0, 2) . "<br>";
            $html .= "<strong>Pedidos Finalizados (Cantidad):</strong> " . $pedFinalizados . "<br>";
            $html .= "<strong>Pedidos Cancelados (Cantidad):</strong> " . $pedCancelados;
            $html .= "</div>";

            $html .= "</body></html>";

        } catch(Exception $e) {
            $html = "<html><body><h3>Error: " . $e->getMessage() . "</h3></body></html>";
        }

        $pdf = new DOMPDF();
        $pdf->set_paper("A4", "portrait");
        $pdf->load_html(utf8_decode($html));
        $pdf->render();
        $pdf->stream('reporte.pdf', array("Attachment" => false));
    }
}
?>