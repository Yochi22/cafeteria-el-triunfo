<?php
require_once('modelo/datos.php');

class principal extends datos {
    
    public function obtenerMetricas() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $hoy = date('Y-m-d');
        
        try {
           
            $s1 = $co->prepare("SELECT COUNT(*) as total FROM pedido WHERE fechaPedido = ?");
            $s1->execute([$hoy]);
            $total = $s1->fetch(PDO::FETCH_ASSOC)['total'];
            
          
            $s2 = $co->prepare("SELECT COUNT(*) as enviadas FROM pedido WHERE fechaPedido = ? AND estado = 'finalizado'");
            $s2->execute([$hoy]);
            $enviadas = $s2->fetch(PDO::FETCH_ASSOC)['enviadas'];
            
           
            $s3 = $co->prepare("SELECT COUNT(*) as canceladas FROM pedido WHERE fechaPedido = ? AND estado = 'anulado'");
            $s3->execute([$hoy]);
            $canceladas = $s3->fetch(PDO::FETCH_ASSOC)['canceladas'];
            
          
            $s4 = $co->prepare("SELECT SUM(precioTotal) as ganancia FROM pedido WHERE fechaPedido = ? AND estado = 'finalizado'");
            $s4->execute([$hoy]);
            $ganancia = $s4->fetch(PDO::FETCH_ASSOC)['ganancia'] ?? 0;
            
            return [
                'total' => $total,
                'enviadas' => $enviadas,
                'canceladas' => $canceladas,
                'ganancia' => number_format($ganancia, 2)
            ];
            
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
?>
