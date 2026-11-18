<?php

require_once("modelo/datos.php");

class Ventas extends datos
{
    // FUNCIÓN DE INCLUIR
    function incluir($idPersonal, $idCliente, $tipoPago, $cedulaPago, $tlfPago, $referencia, $idCuenta, $productos, $cantidades, $precios, $zonaDelivery, $calleDelivery, $numCasaDelivery)
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();

        try {
            $co->beginTransaction();
            $fecha = date('Y-m-d');

            // Bucle for para calcular la cantidad total y el precio de productos 
            $cantidadTotalVenta = 0;
            $precioProductos = 0;
            for ($i = 0; $i < count($productos); $i++) {
                $cant = intval($cantidades[$i]);
                $precio = floatval($precios[$i]);
                $subtotal = $cant * $precio;
                $cantidadTotalVenta += $cant;
                $precioProductos += $subtotal;
            }

            // Obtener precio del delivery según la zona
            $precioDelivery = 0.00;
            if (!empty($zonaDelivery)) {
                $stmtZona = $co->prepare("SELECT precio FROM preciosdelivery WHERE nombreZona = :zona LIMIT 1");
                $stmtZona->execute([':zona' => $zonaDelivery]);
                $resZona = $stmtZona->fetch(PDO::FETCH_ASSOC);
                $precioDelivery = $resZona ? (float)$resZona['precio'] : 0.00;
            }

            $totalVenta = $precioProductos + $precioDelivery;

            $idClienteVal = $idCliente ?: null;
            $estadoPedido = 'Finalizado';

            // Pone valores para cuando el tipo de pago no sea pago móvil
            if ($tipoPago === 'Pago Móvil') {
                $cedulaVal = !empty($cedulaPago) ? $cedulaPago : 'N/A';
                $tlfVal = !empty($tlfPago) ? $tlfPago : 'N/A';
                $refVal = !empty($referencia) ? $referencia : '0000';
                $idCuentaVal = !empty($idCuenta) ? $idCuenta : null;
            } else {
                $cedulaVal = 'N/A';
                $tlfVal = 'N/A';
                $refVal = '0000';
                $idCuentaVal = null;
            }

            // Guarda el pedido completo
            $stmtPed = $co->prepare("INSERT INTO pedidos (idCliente, idCuenta, precioTotal, tipoPago, cedulaPago, tlfPago, numComprobante, estadoPedido) 
            VALUES (:idCliente, :idCuenta, :total, :tipoPago, :cedulaPago, :tlfPago, :numComprobante, :estadoPedido)");

            $stmtPed->bindParam(':idCliente', $idClienteVal);
            $stmtPed->bindParam(':idCuenta', $idCuentaVal);
            $stmtPed->bindParam(':total', $totalVenta);
            $stmtPed->bindParam(':tipoPago', $tipoPago);
            $stmtPed->bindParam(':cedulaPago', $cedulaVal);
            $stmtPed->bindParam(':tlfPago', $tlfVal);
            $stmtPed->bindParam(':numComprobante', $refVal);
            $stmtPed->bindParam(':estadoPedido', $estadoPedido);
            $stmtPed->execute();

            $idPedidoGenerado = $co->lastInsertId();

            // Guarda la dirección de delivery
            if (!empty($zonaDelivery)) {
                $stmtDir = $co->prepare("INSERT INTO dirdelivery (idPedido, zona, calle, numCasa) VALUES (:idPedido, :zona, :calle, :numCasa)");
                $stmtDir->bindParam(':idPedido', $idPedidoGenerado);
                $stmtDir->bindParam(':zona', $zonaDelivery);
                $stmtDir->bindParam(':calle', $calleDelivery);
                $stmtDir->bindParam(':numCasa', $numCasaDelivery);
                $stmtDir->execute();
            }

            // Guarda los productos asociados al pedido
            $stmtProd = $co->prepare("INSERT INTO pedidosproducto (idPedido, idProducto, cantidadProd, subTotal) 
            VALUES (:idPedido, :idProd, :cant, :subtotal)");

            // Bucle for para insertar los productos en la tabla pedidosproducto
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

            // Guarda los detalles del pago
            $stmtPago = $co->prepare("INSERT INTO detallesventa (cedulaPago, tlfPago, referencia) 
            VALUES (:cedula, :tlf, :ref)");
            $stmtPago->bindParam(':cedula', $cedulaVal);
            $stmtPago->bindParam(':tlf', $tlfVal);
            $stmtPago->bindParam(':ref', $refVal);
            $stmtPago->execute();
            $idDetalleGenerado = $co->lastInsertId();

            // Registrar la venta
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

            // Une el pedido con la venta en la tabla pedidosventa
            $stmtBridge = $co->prepare("INSERT INTO pedidosventa (idPedido, idVenta) 
            VALUES (:idPedido, :idVenta)");
            $stmtBridge->bindParam(':idPedido', $idPedidoGenerado);
            $stmtBridge->bindParam(':idVenta', $idVentaGenerado);
            $stmtBridge->execute();

            $co->commit(); // Si todo salió bien, hace commit
            $r['resultado'] = 'incluir';
            $r['mensaje'] = "Venta registrada. N° de Venta: $idVentaGenerado";
        } catch (Exception $e) {
            if ($co->inTransaction()) {
                $co->rollBack(); // Si no salió bien, hace rollback
            }
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }

        return $r;
    }

    // FUNCIÓN CONSULTAR
    function consultar()
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $respuesta = $co->prepare("SELECT v.idVenta, 
                                          CONCAT(p.nombrePer, ' ', p.apellidoPer) AS nombrePer, 
                                          v.totalVenta, 
                                          v.tipoPago,
                                          v.fechaVenta
                                   FROM ventas v 
                                   LEFT JOIN personal p ON v.idPersonal = p.idPersonal 
                                   ORDER BY v.idVenta DESC");
            $respuesta->execute();
            return $respuesta->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return array();
        }
    }

    // FUNCIÓN QUE CONSULTA UNA VENTA ESPECIFICA
    function consultarUno($idVenta)
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();

        try {
            // Datos generales de la venta
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
            $venta = $stmt->fetch(PDO::FETCH_ASSOC); // Guarda toda la información seleccionada aquí

            // Datos de los productos de esa venta
            $stmtProd = $co->prepare("SELECT pp.idProducto, pp.cantidadProd AS cantidad, pp.subTotal, p.codigoProd, p.nombreProd, p.precioProd AS precioUnitario 
                                   FROM pedidosproducto pp 
                                   JOIN productos p ON pp.idProducto = p.idProducto 
                                   WHERE pp.idPedido = :idPedido");

            $idPedidoTemp = $venta['idPedido'] ?? null;
            $stmtProd->bindParam(':idPedido', $idPedidoTemp);
            $stmtProd->execute();
            $productos = $stmtProd->fetchAll(PDO::FETCH_ASSOC); // Guarda todos los productos aquí

            $r['resultado'] = 'consultarUno';
            $r['venta'] = $venta;
            $r['productos'] = $productos;
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }

        return $r;
    }

    // FUNCIÓN QUE CONSULTA EL DETALLE DE UNA VENTA ESPECÍFICA
    function consultarDetalle($idVenta)
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();

        try {
            // MEGA CONSULTA PARA OBTENER TODOS LOS DATOS DE LA VENTA
            $stmt = $co->prepare("SELECT v.*, d.cedulaPago, d.tlfPago, d.referencia, 
                                   per.nombrePer, per.apellidoPer, per.cedulaPer,
                                   cli.nombreCli, cli.cedulaCli, cli.tlfCli,
                                   pp.cantidadProd AS cantidad, pp.subTotal,
                                   prod.codigoProd, prod.nombreProd, prod.precioProd AS precioUnitario,
                                   c.nombreBanco, c.numCuenta,
                                   dir.zona AS zonaDelivery, dir.calle AS calleDelivery, dir.numCasa AS numCasaDelivery,
                                   pd.precio AS precioDeliveryCosto
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
                            LEFT JOIN preciosdelivery pd ON dir.zona = pd.nombreZona
                            WHERE v.idVenta = :idVenta");

            $stmt->bindParam(':idVenta', $idVenta);
            $stmt->execute();
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC); // Guarda todos los resultados aquí

            if ($resultados) {
                $v = $resultados[0];
                $idVentaStr = str_pad($v['idVenta'], 3, "0", STR_PAD_LEFT);
                $totalFormateado = number_format($v['totalVenta'], 2);
                $costoDelivery = isset($v['precioDeliveryCosto']) ? floatval($v['precioDeliveryCosto']) : 0.00;

                // Busca la tasa exacta del día de la venta
                $stmtTasa = $co->prepare("SELECT precioTasa FROM tasadiaria WHERE fechaTasa = :fechaVenta");
                $stmtTasa->bindParam(':fechaVenta', $v['fechaVenta']);
                $stmtTasa->execute();
                $tasaRow = $stmtTasa->fetch(PDO::FETCH_ASSOC);

                $precioTasa = $tasaRow ? floatval($tasaRow['precioTasa']) : 0;

                // Busca la tasa histórica más cercana anterior si no hay tasa del dia
                if ($precioTasa == 0) {
                    $stmtCercana = $co->prepare("SELECT precioTasa FROM tasadiaria WHERE fechaTasa <= :fechaVenta ORDER BY fechaTasa DESC LIMIT 1");
                    $stmtCercana->bindParam(':fechaVenta', $v['fechaVenta']);
                    $stmtCercana->execute();
                    $tasaCercanaRow = $stmtCercana->fetch(PDO::FETCH_ASSOC);
                    $precioTasa = $tasaCercanaRow ? floatval($tasaCercanaRow['precioTasa']) : 0;
                }

                // Tomamos la última disponible si no hay más
                if ($precioTasa == 0) {
                    $stmtUltimaTasa = $co->prepare("SELECT precioTasa FROM tasadiaria ORDER BY fechaTasa DESC LIMIT 1");
                    $stmtUltimaTasa->execute();
                    $ultimaTasaRow = $stmtUltimaTasa->fetch(PDO::FETCH_ASSOC);
                    $precioTasa = $ultimaTasaRow ? floatval($ultimaTasaRow['precioTasa']) : 0;
                }

                // Cálculo del total en Bolívares
                $totalBs = $v['totalVenta'] * $precioTasa;
                $totalBsFormateado = number_format($totalBs, 2);

                // CREA EL MODAL CON LA MINI FACTURA DE LA VENTA
                $html = '
                    <!-- cabecera de la venta -->
                    <div class="card border rounded-4 p-3">
                        <div class="text-center mb-3">
                            <span class="badge bg-warning text-dark px-3 py-2 fs-6">Orden #' . $idVentaStr . '</span>
                        </div>

                        <!-- datos generales de la venta -->
                        <div class="row g-2 small mb-3">
                            <div class="col-6"><strong>Fecha:</strong> ' . $v['fechaVenta'] . '</div>
                            <div class="col-6"><strong>Personal:</strong> ' . $v['nombrePer'] . ' ' . $v['apellidoPer'] . '</div>
                            <div class="col-6"><strong>Cliente:</strong> ' . (!empty($v['nombreCli']) ? $v['nombreCli'] . ', ' . ($v['cedulaCli'] ?? '') : 'Sin cliente') . '</div>
                            <div class="col-6"><strong>Telefono:</strong> ' . (!empty($v['tlfCli']) ? $v['tlfCli'] : 'N/A') . '</div>
                        </div>

                        <!-- datos del delivery -->
                        <h6 class="fw-bold text-dashboard border-bottom pb-1 mb-2"><i class="bi bi-geo-alt"></i> Dirección de Delivery</h6>
                        <div class="row g-2 small mb-3">
                            <div class="col-4"><strong>Zona:</strong> ' . (!empty($v['zonaDelivery']) ? $v['zonaDelivery'] . ' ($' . number_format($costoDelivery, 2) . ')' : 'N/A') . '</div>
                            <div class="col-8"><strong>Calle:</strong> ' . (!empty($v['calleDelivery']) ? $v['calleDelivery'] : 'N/A') . '</div>
                            <div class="col-12"><strong>N° Casa:</strong> ' . (!empty($v['numCasaDelivery']) ? $v['numCasaDelivery'] : 'N/A') . '</div>
                        </div>

                        <!-- productos del pedido -->
                        <h6 class="fw-bold text-dashboard border-bottom pb-1 mb-2"><i class="bi bi-basket"></i> Productos del Pedido</h6>

                        <!-- Crea la minitabla de productos -->
                        <div class="table-responsive mb-1">
                            <table class="table table-sm table-bordered text-center align-middle bg-white small mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Cant</th>
                                        <th>Producto</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>';
                $precioTotalProductosDolar = 0;
                // Recorre los productos y los agrega a la tabla
                foreach ($resultados as $prod) {
                    if ($prod['nombreProd']) {
                        $precioTotalProductosDolar += floatval($prod['subTotal']);
                        $html .= '<tr>
                                    <td>' . $prod['cantidad'] . '</td>
                                    <td class="text-start">' . $prod['nombreProd'] . '</td>
                                    <td>$' . number_format($prod['subTotal'], 2) . '</td>
                                  </tr>';
                    }
                }
                $precioTotalProductosBs = $precioTotalProductosDolar * $precioTasa;
                $html .= '      </tbody>
                            </table>
                        </div>';
                // Texto que muestra el precio de los productos 
                $html .= '
                        <div class="d-flex justify-content-between align-items-center px-1 py-2 mb-3 border-bottom small text-muted">
                            <span>Subtotal Productos:</span>
                            <span><strong class="text-success">$' . number_format($precioTotalProductosDolar, 2) . '</strong> <span class="ms-1">Bs. ' . number_format($precioTotalProductosBs, 2) . '</span></span>
                        </div>

                        <!-- datos del pago -->
                        <h6 class="fw-bold text-dashboard border-bottom pb-1 mb-2"><i class="bi bi-wallet2"></i> Datos del Pago</h6>
                        <div class="row g-2 small mb-3">
                            <div class="col-6"><strong>Banco:</strong> ' . (!empty($v['nombreBanco']) ? $v['nombreBanco'] : 'N/A') . '</div>
                            <div class="col-6"><strong>Tipo de Pago:</strong> ' . (!empty($v['tipoPago']) ? $v['tipoPago'] : 'N/A') . '</div>
                            <div class="col-12"><strong>N° Cuenta:</strong> ' . (!empty($v['numCuenta']) ? $v['numCuenta'] : 'N/A') . '</div>
                            <div class="col-5"><strong>Cédula Titular:</strong> ' . (!empty($v['cedulaPago']) ? $v['cedulaPago'] : 'N/A') . '</div>
                            <div class="col-7"><strong>Teléfono Titular:</strong> ' . (!empty($v['tlfPago']) ? $v['tlfPago'] : 'N/A') . '</div>
                            <div class="col-12"><strong>Referencia:</strong> ' . (!empty($v['referencia']) ? $v['referencia'] : 'N/A') . '</div>
                        </div>

                        <!-- resumen final de la venta -->
                        <div class="border-top pt-2 mt-2">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="small text-muted">Precio Delivery (' . (!empty($v['zonaDelivery']) ? $v['zonaDelivery'] : 'N/A') . '):</span>
                                <span class="small fw-bold text-secondary">$' . number_format($costoDelivery, 2) . ' <span class="text-muted fw-normal">/ Bs. ' . number_format($costoDelivery * $precioTasa, 2) . '</span></span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center border-top pt-2">
                                <div>
                                    <h5 class="fw-bold text-dashboard mb-0">Total Venta:</h5>
                                    <small class="text-muted">Tasa aplicada: ' . ($precioTasa > 0 ? number_format($precioTasa, 2) . ' Bs/$' : 'No registrada') . '</small>
                                </div>
                                <div class="text-end">
                                    <h4 class="fw-bold text-success mb-0">$' . $totalFormateado . '</h4>
                                    <h5 class="fw-bold text-secondary mb-0">Bs. ' . $totalBsFormateado . '</h5>
                                </div>
                            </div>
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

    // FUNCIÓN DE MODIFICAR
    function modificar($idVenta, $idPersonal, $idCliente, $tipoPago, $idDetalle, $idPedido, $cedulaPago, $tlfPago, $referencia, $idCuenta, $productos, $cantidades, $precios, $zonaDelivery, $calleDelivery, $numCasaDelivery)
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();

        try {
            $co->beginTransaction();

            $cantidadTotalVenta = 0;
            $precioProductos = 0;

            for ($i = 0; $i < count($productos); $i++) {
                $cant = intval($cantidades[$i]);
                $precio = floatval($precios[$i]);
                $subtotal = $cant * $precio;
                $cantidadTotalVenta += $cant;
                $precioProductos += $subtotal;
            }

            $precioDelivery = 0.00;
            if (!empty($zonaDelivery)) {
                $stmtZona = $co->prepare("SELECT precio FROM preciosdelivery WHERE nombreZona = :zona LIMIT 1");
                $stmtZona->execute([':zona' => $zonaDelivery]);
                $resZona = $stmtZona->fetch(PDO::FETCH_ASSOC);
                $precioDelivery = $resZona ? (float)$resZona['precio'] : 0.00;
            }

            $totalVenta = $precioProductos + $precioDelivery;

            // Agregar datos por defecto si el pago no es pago móvil
            $cedulaVal = ($tipoPago === 'Pago Móvil' && !empty($cedulaPago)) ? $cedulaPago : 'N/A';
            $tlfVal = ($tipoPago === 'Pago Móvil' && !empty($tlfPago)) ? $tlfPago : 'N/A';
            $refVal = ($tipoPago === 'Pago Móvil' && !empty($referencia)) ? $referencia : '0000';

            // ACTUALIZA LAS TABLAS
            $modVenta = $co->prepare("UPDATE ventas SET idPersonal = :idPersonal, tipoPago = :tipoPago, cantidadVenta = :cant, totalVenta = :total WHERE idVenta = :idVenta");
            $modVenta->bindParam(':idPersonal', $idPersonal);
            $modVenta->bindParam(':tipoPago', $tipoPago);
            $modVenta->bindParam(':cant', $cantidadTotalVenta);
            $modVenta->bindParam(':total', $totalVenta);
            $modVenta->bindParam(':idVenta', $idVenta);
            $modVenta->execute();

            $modPago = $co->prepare("UPDATE detallesventa SET cedulaPago = :cedula, tlfPago = :tlf, referencia = :ref WHERE idDetalle = :idDetalle");
            $modPago->bindParam(':cedula', $cedulaVal);
            $modPago->bindParam(':tlf', $tlfVal);
            $modPago->bindParam(':ref', $refVal);
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

                // Para el delivery borra los datos nuevos y los ingresa de nuevo
                $delDir = $co->prepare("DELETE FROM dirdelivery WHERE idPedido = :idPedido");
                $delDir->bindParam(':idPedido', $idPedido);
                $delDir->execute();

                if (!empty($zonaDelivery)) {
                    $insDir = $co->prepare("INSERT INTO dirdelivery (idPedido, zona, calle, numCasa) VALUES (:idPedido, :zona, :calle, :numCasa)");
                    $insDir->bindParam(':idPedido', $idPedido);
                    $insDir->bindParam(':zona', $zonaDelivery);
                    $insDir->bindParam(':calle', $calleDelivery);
                    $insDir->bindParam(':numCasa', $numCasaDelivery);
                    $insDir->execute();
                }

                // Hace lo mismo con los productos
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

            $co->commit(); // Si todo salió bien, hace commit
            $r['resultado'] = 'modificar';
            $r['mensaje'] = "Venta modificada con éxito.";
        } catch (Exception $e) {
            if ($co->inTransaction()) {
                $co->rollBack(); // Si no salió bien, hace rollback
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

            // Busca los IDs asociados a ventas
            $stmt = $co->prepare("SELECT v.idDetalle, pv.idPedido FROM ventas v JOIN pedidosventa pv ON v.idVenta = pv.idVenta WHERE v.idVenta = :idVenta");
            $stmt->bindParam(':idVenta', $idVenta);
            $stmt->execute();
            $v = $stmt->fetch(PDO::FETCH_ASSOC);

            // ELIMINACIÓN EN CASCADA
            if (!empty($v['idPedido'])) {
                // Borra el delivery
                $stmtDelDir = $co->prepare("DELETE FROM dirdelivery WHERE idPedido = :idPedido");
                $stmtDelDir->bindParam(':idPedido', $v['idPedido']);
                $stmtDelDir->execute();
            }

            // Borra la venta
            $stmtDelVenta = $co->prepare("DELETE FROM ventas WHERE idVenta = :idVenta");
            $stmtDelVenta->bindParam(':idVenta', $idVenta);
            $stmtDelVenta->execute();

            // Borra el detalle de la venta
            if (!empty($v['idDetalle'])) {
                $stmtDelDet = $co->prepare("DELETE FROM detallesventa WHERE idDetalle = :idDetalle");
                $stmtDelDet->bindParam(':idDetalle', $v['idDetalle']);
                $stmtDelDet->execute();
            }

            // Borra el pedido y los productos asociados
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

            $co->commit(); // Si todo salió bien, hace commit
            $r['resultado'] = 'eliminar';
            $r['mensaje'] = 'Venta eliminada con éxito.';
        } catch (Exception $e) {
            if ($co->inTransaction()) {
                $co->rollBack(); // Si no salió bien, hace rollback
            }

            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }

        return $r;
    }

    // FUNCIONES PARA LISTAR PERSONAL, CLIENTES, PRODUCTOS Y CUENTAS
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

                $r['resultado'] = 'listarPersonal';
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

                $r['resultado'] = 'listarClientes';
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
                $respuesta = '';

                foreach ($resultado as $fila) {
                    $respuesta .= "<tr style='cursor:pointer' class='bg-light-hover' onclick='seleccionarProducto(" . $fila['idProducto'] . ", \"" . $fila['codigoProd'] . "\", \"" . $fila['nombreProd'] . "\", " . $fila['precioProd'] . ")'>";
                    $respuesta .= "<td>" . $fila['codigoProd'] . "</td>";
                    $respuesta .= "<td>" . $fila['nombreProd'] . "</td>";
                    $respuesta .= "<td class='text-success fw-bold'>$" . number_format($fila['precioProd'], 2) . "</td>";
                    $respuesta .= "</tr>";
                }

                $r['resultado'] = 'listarProductos';
                $r['mensaje'] = $respuesta;
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

                $r['resultado'] = 'listarCuentas';
                $r['mensaje'] = $respuesta;
            }
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }

        return $r;
    }
}

?>