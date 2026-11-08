<?php

require_once("modelo/datos.php");

class Ventas extends datos
{
    function incluir($idPersonal, $idCliente, $tipoPago, $cedulaPago, $tlfPago, $referencia, $idCuenta, $productos, $cantidades, $precios, $zonaDelivery, $calleDelivery, $numCasaDelivery)
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();

        try {
            $co->beginTransaction();
            $fecha = date('Y-m-d');

            $cantidadTotalVenta = 0;
            $totalVenta = 0;
            for ($i = 0; $i < count($productos); $i++) {
                $cant = intval($cantidades[$i]);
                $precio = floatval($precios[$i]);
                $subtotal = $cant * $precio;
                $cantidadTotalVenta += $cant;
                $totalVenta += $subtotal;
            }

            $idClienteVal = $idCliente ?: null;
            $idCuentaVal = $idCuenta ?: null;
            $estadoPedido = 'Finalizado';

            $stmtPed = $co->prepare("INSERT INTO pedidos (idCliente, idCuenta, precioTotal, estadoPedido) 
            VALUES (:idCliente, :idCuenta, :total, :estadoPedido)");
            
            $stmtPed->bindParam(':idCliente', $idClienteVal);
            $stmtPed->bindParam(':idCuenta', $idCuentaVal);
            $stmtPed->bindParam(':total', $totalVenta);
            $stmtPed->bindParam(':estadoPedido', $estadoPedido);
            $stmtPed->execute();

            $idPedidoGenerado = $co->lastInsertId();

            $stmtDir = $co->prepare("INSERT INTO dirdelivery (idPedido, zona, calle, numCasa) VALUES (:idPedido, :zona, :calle, :numCasa)");
            $stmtDir->bindParam(':idPedido', $idPedidoGenerado);
            $stmtDir->bindParam(':zona', $zonaDelivery);
            $stmtDir->bindParam(':calle', $calleDelivery);
            $stmtDir->bindParam(':numCasa', $numCasaDelivery);
            $stmtDir->execute();

            $stmtProd = $co->prepare("INSERT INTO pedidosproducto (idPedido, idProducto, cantidadProd, subTotal) 
            VALUES (:idPedido, :idProd, :cant, :subtotal)");

            for ($i = 0; $i < count($productos); $i++) {
                $cant = intval($cantidades[$i]);
                $precio = floatval($precios[$i]);
                $subtotal = $cant * $precio;
                $idProdActual = $productos[$i];

                $stmtProd->bindParam(':idPedido', $idPedidoGenerado);
                $stmtProd->bindParam(':idProd', $idProdActual);
                $stmtProd->bindParam(':cant', $cant);
                $stmtProd->bindParam(':subtotal', $subtotal);
                $stmtProd->execute();
            }

            $stmtPago = $co->prepare("INSERT INTO detallesventa (cedulaPago, tlfPago, referencia) 
            VALUES (:cedula, :tlf, :ref)");
            $stmtPago->bindParam(':cedula', $cedulaPago);
            $stmtPago->bindParam(':tlf', $tlfPago);
            $stmtPago->bindParam(':ref', $referencia);
            $stmtPago->execute();

            $idDetalleGenerado = $co->lastInsertId();

            $stmtVenta = $co->prepare("INSERT INTO ventas (idDetalle, idPersonal, tipoPago, fechaVenta, cantidadVenta, totalVenta) 
            VALUES (:idDetalle, :idPersonal, :tipoPago, :fecha, :cantidad, :total)");

            $stmtVenta->bindParam(':idDetalle', $idDetalleGenerado);
            $stmtVenta->bindParam(':idPersonal', $idPersonal);
            $stmtVenta->bindParam(':tipoPago', $tipoPago);
            $stmtVenta->bindParam(':fecha', $fecha);
            $stmtVenta->bindParam(':cantidad', $cantidadTotalVenta);
            $stmtVenta->bindParam(':total', $totalVenta);
            $stmtVenta->execute();

            $idVentaGenerado = $co->lastInsertId();

            $stmtBridge = $co->prepare("INSERT INTO pedidosventa (idPedido, idVenta) 
            VALUES (:idPedido, :idVenta)");
            $stmtBridge->bindParam(':idPedido', $idPedidoGenerado);
            $stmtBridge->bindParam(':idVenta', $idVentaGenerado);
            $stmtBridge->execute();

            $co->commit();
            $r['resultado'] = 'incluir';
            $r['mensaje'] = "Venta y Pedido #$idPedidoGenerado registrados con éxito. N° de Venta: $idVentaGenerado";
        } catch (Exception $e) {
            if ($co->inTransaction()) {
                $co->rollBack();
            }
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }

        return $r;
    }

    function consultar()
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();

        try {
            $stmt = $co->prepare("SELECT v.*, p.nombrePer, p.apellidoPer FROM ventas v LEFT JOIN personal p ON v.idPersonal = p.idPersonal ORDER BY v.idVenta DESC");
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($resultado !== false) {
                $respuesta = '';
                foreach ($resultado as $fila) {
                    $nombrePersonal = !empty($fila['nombrePer']) ? $fila['nombrePer'] . " " . $fila['apellidoPer'] : "Personal #" . $fila['idPersonal'];

                    $respuesta .= "<tr>";
                    $respuesta .= "<td class='fw-bold'>#" . str_pad($fila['idVenta'], 3, "0", STR_PAD_LEFT) . "</td>";
                    $respuesta .= "<td class='fw-semibold'>" . $nombrePersonal . "</td>";
                    $respuesta .= "<td class='fw-bold text-success'>$" . number_format($fila['totalVenta'], 2) . "</td>";
                    $respuesta .= "<td class='fw-semibold'>" . $fila['tipoPago'] . "</td>";
                    $respuesta .= "<td><button type='button' class='btn btn-outline-info btn-sm d-inline-flex align-items-center gap-1' onclick='consultarDetalle(" . $fila['idVenta'] . ")'><i class='bi bi-eye-fill'></i><span class='d-none d-sm-inline'> Ver Detalle</span></button></td>";
                    $respuesta .= "<td>";
                    $respuesta .= "<button type='button' class='btn btn-outline-primary btn-sm d-inline-flex align-items-center gap-1' onclick='pone(" . $fila['idVenta'] . ")'><i class='bi bi-pencil-square'></i><span class='d-none d-sm-inline'> Modificar</span></button> ";
                    $respuesta .= "<button type='button' class='btn btn-outline-danger btn-sm d-inline-flex align-items-center gap-1' onclick='eliminar(" . $fila['idVenta'] . ")'><i class='bi bi-trash-fill'></i><span class='d-none d-sm-inline'> Eliminar</span></button>";
                    $respuesta .= "</td>";
                    $respuesta .= "</tr>";
                }
                if ($respuesta == "") {
                    $respuesta .= "<tr>";
                    $respuesta .= "<td colspan='6' class='text-center text-muted py-4'>";
                    $respuesta .= "<span><i class='bi bi-bag-check-fill fs-1' style='color: #FF8C00'></i></span>";
                    $respuesta .= "<h5 class='text-dashboard'>No Tiene Ventas Registradas</h5>";
                    $respuesta .= "</td>";
                    $respuesta .= "</tr>";
                }

                $r['resultado'] = 'consultar';
                $r['mensaje'] = $respuesta;
            }
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }

        return $r;
    }

    function consultarUno($idVenta)
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();

        try {
            $stmt = $co->prepare("SELECT v.*, d.cedulaPago, d.tlfPago, d.referencia, 
                                   per.cedulaPer, per.nombrePer, per.apellidoPer,
                                   ped.idPedido, ped.idCuenta, 
                                   dir.zona AS zonaDelivery, dir.calle AS calleDelivery, dir.numCasa AS numCasaDelivery,
                                   cli.idCliente, cli.cedulaCli, cli.nombreCli,
                                   c.nombreBanco, c.numCuenta 
                            FROM ventas v 
                            JOIN detallesventa d ON v.idDetalle = d.idDetalle 
                            JOIN personal per ON v.idPersonal = per.idPersonal 
                            JOIN pedidosventa pv ON v.idVenta = pv.idVenta
                            JOIN pedidos ped ON pv.idPedido = ped.idPedido
                            LEFT JOIN clientes cli ON ped.idCliente = cli.idCliente 
                            LEFT JOIN cuentas c ON ped.idCuenta = c.idCuenta
                            LEFT JOIN dirdelivery dir ON ped.idPedido = dir.idPedido
                            WHERE v.idVenta = :idVenta");
            
            $stmt->bindParam(':idVenta', $idVenta);
            $stmt->execute();
            $venta = $stmt->fetch(PDO::FETCH_ASSOC);

            $stmtProd = $co->prepare("SELECT pp.idProducto, pp.cantidadProd AS cantidad, pp.subTotal, p.codigoProd, p.nombreProd, p.precioProd AS precioUnitario 
                                   FROM pedidosproducto pp 
                                   JOIN productos p ON pp.idProducto = p.idProducto 
                                   WHERE pp.idPedido = :idPedido");
            
            $idPedidoTemp = $venta['idPedido'] ?? null;
            $stmtProd->bindParam(':idPedido', $idPedidoTemp);
            $stmtProd->execute();
            $productos = $stmtProd->fetchAll(PDO::FETCH_ASSOC);

            $r['resultado'] = 'consultar_uno';
            $r['venta'] = $venta;
            $r['productos'] = $productos;
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }

        return $r;
    }

    function consultarDetalle($idVenta)
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();

        try {
            $stmt = $co->prepare("SELECT v.*, d.cedulaPago, d.tlfPago, d.referencia, 
                                   per.nombrePer, per.apellidoPer, per.cedulaPer,
                                   cli.nombreCli, cli.cedulaCli, cli.tlfCli,
                                   pp.cantidadProd AS cantidad, pp.subTotal,
                                   prod.codigoProd, prod.nombreProd, prod.precioProd AS precioUnitario,
                                   c.nombreBanco, c.numCuenta,
                                   dir.zona AS zonaDelivery, dir.calle AS calleDelivery, dir.numCasa AS numCasaDelivery
                            FROM ventas v 
                            JOIN detallesventa d ON v.idDetalle = d.idDetalle 
                            JOIN personal per ON v.idPersonal = per.idPersonal 
                            JOIN pedidosventa pv ON v.idVenta = pv.idVenta
                            JOIN pedidos ped ON pv.idPedido = ped.idPedido
                            LEFT JOIN clientes cli ON ped.idCliente = cli.idCliente 
                            JOIN pedidosproducto pp ON ped.idPedido = pp.idPedido
                            JOIN productos prod ON pp.idProducto = prod.idProducto
                            LEFT JOIN cuentas c ON ped.idCuenta = c.idCuenta
                            LEFT JOIN dirdelivery dir ON ped.idPedido = dir.idPedido
                            WHERE v.idVenta = :idVenta");

            $stmt->bindParam(':idVenta', $idVenta);
            $stmt->execute();
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($resultados) {
                $v = $resultados[0];
                $idVentaStr = str_pad($v['idVenta'], 3, "0", STR_PAD_LEFT);
                $totalFormateado = number_format($v['totalVenta'], 2);

                $html = '
                    <div class="card border rounded-4 p-3">
                        <div class="text-center mb-3">
                            <span class="badge bg-warning text-dark px-3 py-2 fs-6">Orden #' . $idVentaStr . '</span>
                        </div>
                        <div class="row g-2 small mb-3">
                            <div class="col-6"><strong>Fecha:</strong> ' . $v['fechaVenta'] . '</div>
                            <div class="col-6"><strong>Personal:</strong> ' . $v['nombrePer'] . ' ' . $v['apellidoPer'] . '</div>
                            <div class="col-6"><strong>Cliente:</strong> ' . (!empty($v['nombreCli']) ? $v['nombreCli'] . ', ' . ($v['cedulaCli'] ?? '') : 'Sin cliente') . '</div>
                            <div class="col-6"><strong>Telefono:</strong> ' . (!empty($v['tlfCli']) ? $v['tlfCli'] : 'N/A') . '</div>
                        </div>

                        <h6 class="fw-bold text-dashboard border-bottom pb-1 mb-2"><i class="bi bi-geo-alt"></i> Dirección de Delivery</h6>
                        <div class="row g-2 small mb-3">
                            <div class="col-4"><strong>Zona:</strong> ' . (!empty($v['zonaDelivery']) ? $v['zonaDelivery'] : 'N/A') . '</div>
                            <div class="col-8"><strong>Calle:</strong> ' . (!empty($v['calleDelivery']) ? $v['calleDelivery'] : 'N/A') . '</div>
                            <div class="col-12"><strong>N° Casa:</strong> ' . (!empty($v['numCasaDelivery']) ? $v['numCasaDelivery'] : 'N/A') . '</div>
                        </div>

                        <h6 class="fw-bold text-dashboard border-bottom pb-1 mb-2"><i class="bi bi-basket"></i> Productos del Pedido</h6>
                        <div class="table-responsive mb-3">
                            <table class="table table-sm table-bordered text-center align-middle bg-white small">
                                <thead class="table-light">
                                    <tr>
                                        <th>Cant</th>
                                        <th>Producto</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>';

                foreach ($resultados as $prod) {
                    if ($prod['nombreProd']) {
                        $html .= '<tr>
                                    <td>' . $prod['cantidad'] . '</td>
                                    <td class="text-start">' . $prod['nombreProd'] . '</td>
                                    <td>$' . number_format($prod['subTotal'], 2) . '</td>
                                  </tr>';
                    }
                }

                $html .= '      </tbody>
                            </table>
                        </div>
                        
                        <h6 class="fw-bold text-dashboard border-bottom pb-1 mb-2"><i class="bi bi-wallet2"></i> Datos del Pago</h6>
                        <div class="row g-2 small mb-3">
                            <div class="col-6"><strong>Banco:</strong> ' . (!empty($v['nombreBanco']) ? $v['nombreBanco'] : 'N/A') . '</div>
                            <div class="col-6"><strong>Tipo de Pago:</strong> ' . (!empty($v['tipoPago']) ? $v['tipoPago'] : 'N/A') . '</div>
                            <div class="col-12"><strong>N° Cuenta:</strong> ' . (!empty($v['numCuenta']) ? $v['numCuenta'] : 'N/A') . '</div>
                            <div class="col-5"><strong>Cédula Titular:</strong> ' . (!empty($v['cedulaPago']) ? $v['cedulaPago'] : 'N/A') . '</div>
                            <div class="col-7"><strong>Teléfono Titular:</strong> ' . (!empty($v['tlfPago']) ? $v['tlfPago'] : 'N/A') . '</div>
                            <div class="col-12"><strong>Referencia:</strong> ' . (!empty($v['referencia']) ? $v['referencia'] : 'N/A') . '</div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center border-top pt-2">
                            <h5 class="fw-bold text-dashboard mb-0">Total Venta:</h5>
                            <h4 class="fw-bold text-success mb-0">$' . $totalFormateado . '</h4>
                        </div>
                    </div>';

                $r['resultado'] = 'consultar_detalle';
                $r['mensaje'] = $html;
            }
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }

        return $r;
    }

    function modificar($idVenta, $idPersonal, $idCliente, $tipoPago, $idDetalle, $idPedido, $cedulaPago, $tlfPago, $referencia, $idCuenta, $productos, $cantidades, $precios, $zonaDelivery, $calleDelivery, $numCasaDelivery)
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();

        try {
            $co->beginTransaction();

            $cantidadTotalVenta = 0;
            $totalVenta = 0;
            for ($i = 0; $i < count($productos); $i++) {
                $cant = intval($cantidades[$i]);
                $precio = floatval($precios[$i]);
                $subtotal = $cant * $precio;
                $cantidadTotalVenta += $cant;
                $totalVenta += $subtotal;
            }

            $modVenta = $co->prepare("UPDATE ventas SET idPersonal = :idPersonal, tipoPago = :tipoPago, cantidadVenta = :cant, totalVenta = :total WHERE idVenta = :idVenta");
            $modVenta->bindParam(':idPersonal', $idPersonal);
            $modVenta->bindParam(':tipoPago', $tipoPago);
            $modVenta->bindParam(':cant', $cantidadTotalVenta);
            $modVenta->bindParam(':total', $totalVenta);
            $modVenta->bindParam(':idVenta', $idVenta);
            $modVenta->execute();

            $modPago = $co->prepare("UPDATE detallesventa SET cedulaPago = :cedula, tlfPago = :tlf, referencia = :ref WHERE idDetalle = :idDetalle");
            $modPago->bindParam(':cedula', $cedulaPago);
            $modPago->bindParam(':tlf', $tlfPago);
            $modPago->bindParam(':ref', $referencia);
            $modPago->bindParam(':idDetalle', $idDetalle);
            $modPago->execute();

            if ($idPedido) {
                $idClienteVal = $idCliente ?: null;
                $idCuentaVal = $idCuenta ?: null;

                $modPed = $co->prepare("UPDATE pedidos SET idCliente = :idCliente, idCuenta = :idCuenta, precioTotal = :total WHERE idPedido = :idPedido");
                $modPed->bindParam(':idCliente', $idClienteVal);
                $modPed->bindParam(':idCuenta', $idCuentaVal);
                $modPed->bindParam(':total', $totalVenta);
                $modPed->bindParam(':idPedido', $idPedido);
                $modPed->execute();

                $delDir = $co->prepare("DELETE FROM dirdelivery WHERE idPedido = :idPedido");
                $delDir->bindParam(':idPedido', $idPedido);
                $delDir->execute();
                
                $insDir = $co->prepare("INSERT INTO dirdelivery (idPedido, zona, calle, numCasa) VALUES (:idPedido, :zona, :calle, :numCasa)");
                $insDir->bindParam(':idPedido', $idPedido);
                $insDir->bindParam(':zona', $zonaDelivery);
                $insDir->bindParam(':calle', $calleDelivery);
                $insDir->bindParam(':numCasa', $numCasaDelivery);
                $insDir->execute();

                $delProd = $co->prepare("DELETE FROM pedidosproducto WHERE idPedido = :idPedido");
                $delProd->bindParam(':idPedido', $idPedido);
                $delProd->execute();

                $insProd = $co->prepare("INSERT INTO pedidosproducto (idPedido, idProducto, cantidadProd, subTotal) VALUES (:idPedido, :idProd, :cant, :subtotal)");
                for ($i = 0; $i < count($productos); $i++) {
                    $cant = intval($cantidades[$i]);
                    $precio = floatval($precios[$i]);
                    $subtotal = $cant * $precio;
                    $idProdActual = $productos[$i];

                    $insProd->bindParam(':idPedido', $idPedido);
                    $insProd->bindParam(':idProd', $idProdActual);
                    $insProd->bindParam(':cant', $cant);
                    $insProd->bindParam(':subtotal', $subtotal);
                    $insProd->execute();
                }
            }

            $co->commit();
            $r['resultado'] = 'modificar';
            $r['mensaje'] = "Venta y Pedido modificados con éxito.";
        } catch (Exception $e) {
            if ($co->inTransaction()) {
                $co->rollBack();
            }
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }

        return $r;
    }

    function eliminar($idVenta)
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();

        try {
            $co->beginTransaction();

            $stmt = $co->prepare("SELECT v.idDetalle, pv.idPedido FROM ventas v JOIN pedidosventa pv ON v.idVenta = pv.idVenta WHERE v.idVenta = :idVenta");
            $stmt->bindParam(':idVenta', $idVenta);
            $stmt->execute();
            $v = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!empty($v['idPedido'])) {
                $stmtDelDir = $co->prepare("DELETE FROM dirdelivery WHERE idPedido = :idPedido");
                $stmtDelDir->bindParam(':idPedido', $v['idPedido']);
                $stmtDelDir->execute();
            }

            $stmtDelVenta = $co->prepare("DELETE FROM ventas WHERE idVenta = :idVenta");
            $stmtDelVenta->bindParam(':idVenta', $idVenta);
            $stmtDelVenta->execute();

            if (!empty($v['idDetalle'])) {
                $stmtDelDet = $co->prepare("DELETE FROM detallesventa WHERE idDetalle = :idDetalle");
                $stmtDelDet->bindParam(':idDetalle', $v['idDetalle']);
                $stmtDelDet->execute();
            }

            if (!empty($v['idPedido'])) {
                $stmtDelPv = $co->prepare("DELETE FROM pedidosventa WHERE idPedido = :idPedido");
                $stmtDelPv->bindParam(':idPedido', $v['idPedido']);
                $stmtDelPv->execute();

                $stmtDelPp = $co->prepare("DELETE FROM pedidosproducto WHERE idPedido = :idPedido");
                $stmtDelPp->bindParam(':idPedido', $v['idPedido']);
                $stmtDelPp->execute();

                $stmtDelPed = $co->prepare("DELETE FROM pedidos WHERE idPedido = :idPedido");
                $stmtDelPed->bindParam(':idPedido', $v['idPedido']);
                $stmtDelPed->execute();
            }

            $co->commit();
            $r['resultado'] = 'eliminar';
            $r['mensaje'] = 'Venta eliminada con éxito.';
        } catch (Exception $e) {
            if ($co->inTransaction()) {
                $co->rollBack();
            }
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }

        return $r;
    }

    function listadoDePersonal()
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try {
            $stmt = $co->prepare("SELECT * FROM personal");
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($resultado !== false) {
                $respuesta = '';
                foreach ($resultado as $fila) {
                    $respuesta .= "
                        <tr style='cursor:pointer' onclick='colocapersonal(this);'>
                            <td style='display:none'>{$fila['idPersonal']}</td>
                            <td>{$fila['cedulaPer']}</td>
                            <td>{$fila['nombrePer']}</td>
                            <td>{$fila['apellidoPer']}</td>
                        </tr>
                    ";
                }
                $r['resultado'] = 'modal_personal';
                $r['mensaje'] = $respuesta;
            }
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    function listadoDeClientes()
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try {
            $stmt = $co->prepare("SELECT * FROM clientes");
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($resultado !== false) {
                $respuesta = '';
                foreach ($resultado as $fila) {
                    $respuesta .= "
                        <tr style='cursor:pointer' onclick='colocacliente(this);'>
                            <td style='display:none'>{$fila['idCliente']}</td>
                            <td>{$fila['cedulaCli']}</td>
                            <td>{$fila['nombreCli']}</td>
                            <td>{$fila['tlfCli']}</td>
                        </tr>
                    ";
                }
                $r['resultado'] = 'modal_clientes';
                $r['mensaje'] = $respuesta;
            }
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    function listadoDeProductos()
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try {
            $stmt = $co->prepare("SELECT * FROM productos");
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($resultado !== false) {
                $html = '';
                foreach ($resultado as $fila) {
                    $html .= "<tr style='cursor:pointer' class='bg-light-hover' onclick='seleccionarProducto(" . $fila['idProducto'] . ", \"" . $fila['codigoProd'] . "\", \"" . $fila['nombreProd'] . "\", " . $fila['precioProd'] . ")'>";
                    $html .= "<td>" . $fila['codigoProd'] . "</td>";
                    $html .= "<td>" . $fila['nombreProd'] . "</td>";
                    $html .= "<td class='text-success fw-bold'>$" . number_format($fila['precioProd'], 2) . "</td>";
                    $html .= "</tr>";
                }
                $r['resultado'] = 'modal_productos';
                $r['mensaje'] = $html;
            }
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    function listadoDeCuentas()
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try {
            $stmt = $co->prepare("SELECT * FROM cuentas");
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($resultado !== false) {
                $respuesta = '';
                foreach ($resultado as $fila) {
                    $respuesta .= "
                        <tr style='cursor:pointer' onclick='colocacuentas(this);'>
                            <td style='display:none'>{$fila['idCuenta']}</td>
                            <td>{$fila['nombreBanco']}</td>
                            <td>{$fila['numCuenta']}</td>
                            <td>{$fila['cedulaTitular']}</td>
                            <td>{$fila['tlfCuenta']}</td>
                            <td>{$fila['tipoCuenta']}</td>
                        </tr>
                    ";
                }
                $r['resultado'] = 'modal_cuentas';
                $r['mensaje'] = $respuesta;
            }
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    function buscar($valor)
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $valor = trim($valor);
        $busqueda = "%" . $valor . "%";
        $r = array();

        try {
            $bus = $co->prepare("SELECT v.*, p.nombrePer, p.apellidoPer 
                                 FROM ventas v 
                                 JOIN personal p ON v.idPersonal = p.idPersonal 
                                 WHERE v.idVenta LIKE :busqueda 
                                 OR p.nombrePer LIKE :busqueda 
                                 OR p.apellidoPer LIKE :busqueda 
                                 ORDER BY v.idVenta ASC");

            $bus->bindParam(':busqueda', $busqueda);
            $bus->execute();
            $resultado = $bus->fetchAll(PDO::FETCH_BOTH);

            $respuesta = "";
            foreach ($resultado as $fila) {
                $idVentaStr = "#" . str_pad($fila['idVenta'], 3, "0", STR_PAD_LEFT);
                $nombrePersonal = isset($fila['nombrePer']) ? $fila['nombrePer'] . " " . $fila['apellidoPer'] : "Personal #" . $fila['idPersonal'];

                $respuesta .= "<tr>";
                $respuesta .= "<td class='fw-bold'>" . $idVentaStr . "</td>";
                $respuesta .= "<td>" . $nombrePersonal . "</td>";
                $respuesta .= "<td class='text-success fw-bold'>$" . number_format($fila['totalVenta'], 2) . "</td>";
                $respuesta .= "<td>" . $fila['tipoPago'] . "</td>";
                $respuesta .= "<td><button type='button' class='btn btn-outline-info btn-sm' onclick='consultarDetalle(" . $fila['idVenta'] . ")'><i class='bi bi-eye-fill'></i><span class='d-none d-sm-inline'> Ver Detalle</span></button></td>";
                $respuesta .= "<td>";
                $respuesta .= "<button type='button' class='btn btn-outline-primary btn-sm d-inline-flex align-items-center gap-1' onclick='pone(" . $fila['idVenta'] . ")'><i class='bi bi-pencil-square'></i><span class='d-none d-sm-inline'> Modificar</span></button> ";
                $respuesta .= "<button type='button' class='btn btn-outline-danger btn-sm d-inline-flex align-items-center gap-1' onclick='eliminar(" . $fila['idVenta'] . ")'><i class='bi bi-trash-fill'></i><span class='d-none d-sm-inline'> Eliminar</span></button>";
                $respuesta .= "</td>";
                $respuesta .= "</tr>";
            }

            if ($respuesta == "") {
                $respuesta .= "<tr>";
                $respuesta .= "<td colspan='6' class='text-center text-muted py-4'>";
                $respuesta .= "<span><i class='bi bi-search fs-1' style='color: #FF8C00'></i></span>";
                $respuesta .= "<h5 class='text-dashboard'>No se encontraron ventas</h5>";
                $respuesta .= "</td>";
                $respuesta .= "</tr>";
            }

            $r['resultado'] = 'buscar';
            $r['mensaje'] = $respuesta;
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }

        return $r;
    }

    function buscarPorFecha($fecha)
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();

        try {
            $bus = $co->prepare("SELECT v.*, p.nombrePer, p.apellidoPer 
                                 FROM ventas v 
                                 JOIN personal p ON v.idPersonal = p.idPersonal 
                                 WHERE v.fechaVenta = :fecha 
                                 ORDER BY v.idVenta ASC");

            $bus->bindParam(':fecha', $fecha);
            $bus->execute();
            $resultado = $bus->fetchAll(PDO::FETCH_BOTH);

            $respuesta = "";
            foreach ($resultado as $fila) {
                $idVentaStr = "#" . str_pad($fila['idVenta'], 3, "0", STR_PAD_LEFT);
                $nombrePersonal = isset($fila['nombrePer']) ? $fila['nombrePer'] . " " . $fila['apellidoPer'] : "Personal #" . $fila['idPersonal'];

                $respuesta .= "<tr>";
                $respuesta .= "<td class='fw-bold'>" . $idVentaStr . "</td>";
                $respuesta .= "<td>" . $nombrePersonal . "</td>";
                $respuesta .= "<td class='text-success fw-bold'>$" . number_format($fila['totalVenta'], 2) . "</td>";
                $respuesta .= "<td>" . $fila['tipoPago'] . "</td>";
                $respuesta .= "<td><button type='button' class='btn btn-outline-primary btn-sm' onclick='consultarDetalle(" . $fila['idVenta'] . ")'><i class='bi bi-eye-fill'></i><span class='d-none d-sm-inline'> Ver Detalle</span></button></td>";
                $respuesta .= "<td>";
                $respuesta .= "<button type='button' class='btn btn-outline-primary btn-sm d-inline-flex align-items-center gap-1' onclick='pone(" . $fila['idVenta'] . ")'><i class='bi bi-pencil-square'></i><span class='d-none d-sm-inline'> Modificar</span></button> ";
                $respuesta .= "<button type='button' class='btn btn-outline-danger btn-sm d-inline-flex align-items-center gap-1' onclick='eliminar(" . $fila['idVenta'] . ")'><i class='bi bi-trash-fill'></i><span class='d-none d-sm-inline'> Eliminar</span></button>";
                $respuesta .= "</td>";
                $respuesta .= "</tr>";
            }

            if ($respuesta == "") {
                $respuesta .= "<tr>";
                $respuesta .= "<td colspan='6' class='text-center text-muted py-4'>";
                $respuesta .= "<span><i class='bi bi-calendar-x fs-1' style='color: #FF8C00'></i></span>";
                $respuesta .= "<h5 class='text-dashboard'>No se encontraron ventas para esta fecha</h5>";
                $respuesta .= "</td>";
                $respuesta .= "</tr>";
            }

            $r['resultado'] = 'buscar_fecha';
            $r['mensaje'] = $respuesta;
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }

        return $r;
    }
}

?>