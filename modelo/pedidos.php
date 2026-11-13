<?php

require_once("modelo/datos.php");

class pedidos extends datos
{
    // INCLUIR PEDIDO
    function incluir($idCliente, $idCuenta, $idProducto, $cantidad, $precio, $numComprobante)
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();

        try {
            $co->beginTransaction();

            // Calcular precio total sumando las líneas
            $precioTotal = 0;
            for ($i = 0; $i < count($idProducto); $i++) {
                $precioTotal += ($cantidad[$i] * $precio[$i]);
            }

            // Insertar cabecera del pedido
            $sqlPedido = "INSERT INTO pedidos (idCliente, idCuenta, precioTotal, numComprobante, estadoPedido) VALUES (:idCliente, :idCuenta, :precioTotal, :numComprobante, 'En Proceso')";
            $stmt = $co->prepare($sqlPedido);
            $stmt->execute([
                ':idCliente' => $idCliente,
                ':idCuenta' => empty($idCuenta) ? null : $idCuenta,
                ':precioTotal' => $precioTotal,
                ':numComprobante' => empty($numComprobante) ? null : $numComprobante
            ]);

            $idPedido = $co->lastInsertId();

            // Insertar los productos del detalle
            $sqlDetalle = "INSERT INTO pedidosproducto (idPedido, idProducto, cantidadProd, subTotal) VALUES (:idPedido, :idProducto, :cantidad, :subTotal)";
            $stmtDetalle = $co->prepare($sqlDetalle);

            for ($i = 0; $i < count($idProducto); $i++) {
                $subT = $cantidad[$i] * $precio[$i];
                $stmtDetalle->execute([
                    ':idPedido' => $idPedido,
                    ':idProducto' => $idProducto[$i],
                    ':cantidad' => $cantidad[$i],
                    ':subTotal' => $subT
                ]);
            }

            $co->commit();
            $r['resultado'] = 'incluir';
            $r['mensaje'] = 'Pedido registrado con éxito.';
        } catch (Exception $e) {
            if ($co->inTransaction()) {
                $co->rollBack();
            }

            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }

        return $r;
    }


    // CONSULTAR LISTADO DE PEDIDOS
    function consultar($estado = 'Todos')
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();

        try {
            $sql = "SELECT p.*, c.nombreCli, c.apellidoCli, c.cedulaCli, c.tlfCli, cu.nombreBanco, cu.numCuenta, cu.tipoCuenta 
                    FROM pedidos p 
                    JOIN clientes c ON p.idCliente = c.idCliente 
                    LEFT JOIN cuentas cu ON p.idCuenta = cu.idCuenta";

            $params = array();
            if (!empty($estado) && $estado != 'Todos') {
                $sql .= " WHERE p.estadoPedido = :estado";
                $params[':estado'] = $estado;
            }

            $sql .= " ORDER BY p.idPedido DESC";
            $stmt = $co->prepare($sql);
            $stmt->execute($params);

            $html = '';
            foreach ($stmt as $fila) {
                $estadoClase = $fila['estadoPedido'] == 'En Proceso' ? 'bg-warning text-dark' : ($fila['estadoPedido'] == 'Cancelado' ? 'bg-danger' : 'bg-success');

                $detalles = $co->prepare("SELECT pp.cantidadProd, pr.nombreProd FROM pedidosproducto pp LEFT JOIN productos pr ON pp.idProducto = pr.idProducto WHERE pp.idPedido = :idPedido");
                $detalles->execute([':idPedido' => $fila['idPedido']]);

                $listaDetallesHtml = '';
                $listaDetallesPlain = '';
                foreach ($detalles as $det) {
                    $nombreProd = !empty($det['nombreProd']) ? $det['nombreProd'] : 'Producto eliminado del catálogo';
                    $listaDetallesHtml .= $det['cantidadProd'] . 'x ' . $nombreProd . ', ';
                    $listaDetallesPlain .= $det['cantidadProd'] . 'x ' . $nombreProd . ', ';
                }

                $listaDetallesHtml = rtrim($listaDetallesHtml, ', ');
                $listaDetallesPlain = rtrim($listaDetallesPlain, ', ');

                if (empty($listaDetallesHtml)) {
                    $listaDetallesHtml = '<span class="text-muted fst-italic">Productos eliminados del catálogo</span>';
                    $listaDetallesPlain = 'Productos eliminados del catálogo';
                }

                $bancoTexto = $fila['idCuenta'] ? $fila['nombreBanco'] . ' - ' . substr($fila['numCuenta'], -4) : 'Sin cuenta asignada';
                $comprobanteTexto = !empty($fila['numComprobante']) ? $fila['numComprobante'] : 'N/A';
                $tipoPagoTexto = !empty($fila['tipoPago']) ? $fila['tipoPago'] : 'Por confirmar';
                $pagadorTexto = !empty($fila['cedulaPago']) ? 'Pagador: ' . $fila['cedulaPago'] . ' / ' . $fila['tlfPago'] : '';

                $borderClass = $fila['estadoPedido'] == 'En Proceso' ? 'border-warning' : ($fila['estadoPedido'] == 'Cancelado' ? 'border-danger' : 'border-success');

                $html .= '
                <div class="card border-0 shadow-sm rounded-4 mb-3 border-start border-4 ' . $borderClass . '">
                    <div class="card-body p-4">
                        <div class="row align-items-center g-4">
                            <div class="col-12 col-lg-8">
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <h5 class="card-title text-dashboard fw-bold mb-0">Orden #' . str_pad($fila['idPedido'], 3, '0', STR_PAD_LEFT) . '</h5>
                                        <span class="badge ' . $estadoClase . ' px-3 py-2 rounded-pill">' . $fila['estadoPedido'] . '</span>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <div class="p-3 bg-light rounded-3 h-100">
                                            <p class="mb-2 text-muted small fw-bold text-uppercase"><i class="bi bi-person-fill text-secondary me-1"></i> Cliente</p>
                                            <p class="mb-1 fw-semibold text-dark">' . $fila['nombreCli'] . ' ' . $fila['apellidoCli'] . '</p>
                                            <p class="mb-1 small text-muted">Cédula: ' . $fila['cedulaCli'] . '</p>
                                            <p class="mb-0 small text-muted">Tel: ' . $fila['tlfCli'] . '</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="p-3 bg-light rounded-3 h-100">
                                            <p class="mb-2 text-muted small fw-bold text-uppercase"><i class="bi bi-wallet2 text-secondary me-1"></i> Pago / Banco</p>
                                            <p class="mb-1 fw-semibold text-dark">' . $bancoTexto . '</p>';
                if ($fila['idCuenta']) {
                    $html .= '<p class="mb-1 small text-muted">Tipo: ' . $fila['tipoCuenta'] . '</p>';
                }
                $html .= '                      <p class="mb-1 small text-muted">Método: ' . htmlspecialchars($tipoPagoTexto, ENT_QUOTES, 'UTF-8') . '</p>
                                            <p class="mb-1 small text-muted">Comprobante: ' . $comprobanteTexto . '</p>';
                if (!empty($pagadorTexto)) {
                    $html .= '              <p class="mb-0 small text-muted">' . htmlspecialchars($pagadorTexto, ENT_QUOTES, 'UTF-8') . '</p>';
                }
                $html .= '          </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="p-3 bg-light rounded-3 h-100">
                                            <p class="mb-2 text-muted small fw-bold text-uppercase"><i class="bi bi-box-seam text-secondary me-1"></i> Productos</p>
                                            <p class="mb-0 small text-muted lh-sm" title="' . $listaDetallesPlain . '">' . $listaDetallesHtml . '</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-lg-4 d-flex flex-column flex-sm-row flex-lg-column justify-content-between align-items-lg-end border-start-lg ps-lg-4 gap-3">
                                <div class="text-start text-lg-end">
                                    <span class="text-muted small text-uppercase fw-bold d-block mb-1">Total a Pagar</span>
                                    <h3 class="text-success fw-bold mb-0">$' . number_format($fila['precioTotal'], 2) . '</h3>
                                </div>
                                <div class="d-flex flex-row flex-lg-column gap-2 w-100" style="max-width: 160px;">
                                    <button type="button" class="btn btn-outline-primary btn-sm rounded-pill d-flex align-items-center justify-content-center gap-2 w-100 py-1 px-3" onclick="pone(' . $fila['idPedido'] . ')" title="Editar">
                                        <i class="bi bi-pencil-square"></i> Editar
                                    </button>
                                    <button type="button" class="btn btn-outline-danger btn-sm rounded-pill d-flex align-items-center justify-content-center gap-2 w-100 py-1 px-3" onclick="eliminar(' . $fila['idPedido'] . ')" title="Eliminar">
                                        <i class="bi bi-trash-fill"></i> Eliminar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';
            }

            if ($html == '') {
                $html = '<div class="col-12 text-center text-muted py-5"><i class="bi bi-journal-x fs-1 text-warning"></i><h5 class="mt-2 text-dashboard">No hay pedidos registrados con este estado.</h5></div>';
            }

            $r['resultado'] = 'consultar';
            $r['mensaje'] = $html;
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }

        return $r;
    }


    // CONSULTAR UN SOLO PEDIDO (MODIFICAR)
    public function consultarUno($idPedido)
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $stmt = $co->prepare("SELECT p.idPedido, p.idCliente, p.idCuenta, p.numComprobante, p.tipoPago, p.cedulaPago, p.tlfPago, p.estadoPedido, c.cedulaCli, c.nombreCli, c.apellidoCli, cu.nombreBanco, cu.numCuenta FROM pedidos p INNER JOIN clientes c ON p.idCliente = c.idCliente LEFT JOIN cuentas cu ON p.idCuenta = cu.idCuenta WHERE p.idPedido = :idPedido");
            $stmt->execute([':idPedido' => $idPedido]);
            $datosPedido = $stmt->fetch(PDO::FETCH_ASSOC);

            $stmt2 = $co->prepare("SELECT pp.idProducto, pr.codigoProd, pr.nombreProd, pr.precioProd, pp.cantidadProd FROM pedidosproducto pp LEFT JOIN productos pr ON pp.idProducto = pr.idProducto WHERE pp.idPedido = :idPedido");
            $stmt2->execute([':idPedido' => $idPedido]);
            $resultadosProductos = $stmt2->fetchAll(PDO::FETCH_ASSOC);

            $detalleHtml = "";
            foreach ($resultadosProductos as $fila) {
                $nombreProd = !empty($fila['nombreProd']) ? $fila['nombreProd'] : 'Producto eliminado del catálogo';
                $precioProd = !empty($fila['precioProd']) ? $fila['precioProd'] : 0;
                $codigoProd = !empty($fila['codigoProd']) ? $fila['codigoProd'] : 'S/C';

                $detalleHtml .= "<tr>";
                $detalleHtml .= "<td class='align-middle fw-bold'><input type='hidden' name='idProducto[]' value='" . $fila['idProducto'] . "'>" . $codigoProd . "</td>";
                $detalleHtml .= "<td class='align-middle'>" . $nombreProd . "</td>";
                $detalleHtml .= "<td class='align-middle'><input type='hidden' name='precio[]' value='" . $precioProd . "'>$" . $precioProd . "</td>";
                $detalleHtml .= "<td class='align-middle' style='width: 120px;'><input type='number' class='form-control form-control-sm text-center' value='" . $fila['cantidadProd'] . "' name='cantidad[]' min='1' onchange='modificasubtotal(this)' onkeyup='modificasubtotal(this)'></td>";
                $detalleHtml .= "<td class='align-middle text-success fw-bold subtotal-cell'>$" . ($fila['cantidadProd'] * $precioProd) . "</td>";
                $detalleHtml .= "<td class='align-middle'><button type='button' class='btn btn-outline-danger btn-sm' onclick='eliminalineadetalle(this)'><i class='bi bi-x-lg'></i></button></td>";
                $detalleHtml .= "</tr>";
            }

            $cuentaInfo = "";
            if ($datosPedido['idCuenta']) {
                $cuentaInfo = $datosPedido['nombreBanco'] . " - " . $datosPedido['numCuenta'];
            }

            return array(
                "resultado" => "consultar_uno",
                "idPedido" => $datosPedido['idPedido'],
                "idCliente" => $datosPedido['idCliente'],
                "clienteInfo" => $datosPedido['cedulaCli'] . " - " . $datosPedido['nombreCli'] . " " . $datosPedido['apellidoCli'],
                "idCuenta" => $datosPedido['idCuenta'],
                "cuentaInfo" => $cuentaInfo,
                "numComprobante" => $datosPedido['numComprobante'],
                "tipoPago" => $datosPedido['tipoPago'],
                "cedulaPago" => $datosPedido['cedulaPago'],
                "tlfPago" => $datosPedido['tlfPago'],
                "estadoPedido" => $datosPedido['estadoPedido'],
                "detalleHtml" => $detalleHtml
            );
        } catch (Exception $e) {
            return array("resultado" => "error", "mensaje" => $e->getMessage());
        }
    }


    // MODALES AUXILIARES (CLIENTES, CUENTAS, PRODUCTOS)
    function listadoDeClientes()
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();

        try {
            $resultado = $co->query("SELECT * FROM clientes");
            if ($resultado) {
                $html = '';
                foreach ($resultado as $fila) {
                    $html .= "<tr style='cursor:pointer' class='bg-light-hover' onclick='seleccionarCliente(" . $fila['idCliente'] . ", \"" . $fila['cedulaCli'] . "\", \"" . $fila['nombreCli'] . " " . $fila['apellidoCli'] . "\")'>";
                    $html .= "<td>" . $fila['cedulaCli'] . "</td>";
                    $html .= "<td>" . $fila['nombreCli'] . " " . $fila['apellidoCli'] . "</td>";
                    $html .= "<td>" . $fila['tlfCli'] . "</td>";
                    $html .= "</tr>";
                }
                $r['resultado'] = 'modalclientes';
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
            $resultado = $co->query("SELECT * FROM cuentas");
            if ($resultado) {
                $html = '';
                foreach ($resultado as $fila) {
                    $html .= "<tr style='cursor:pointer' class='bg-light-hover' onclick='seleccionarCuenta(" . $fila['idCuenta'] . ", \"" . $fila['nombreBanco'] . "\", \"" . $fila['numCuenta'] . "\")'>";
                    $html .= "<td>" . $fila['nombreBanco'] . "</td>";
                    $html .= "<td>" . $fila['numCuenta'] . "</td>";
                    $html .= "<td>" . $fila['tipoCuenta'] . "</td>";
                    $html .= "</tr>";
                }
                $r['resultado'] = 'modalcuentas';
                $r['mensaje'] = $html;
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
            $resultado = $co->query("SELECT * FROM productos");
            if ($resultado) {
                $html = '';
                foreach ($resultado as $fila) {
                    $html .= "<tr style='cursor:pointer' class='bg-light-hover' onclick='seleccionarProducto(" . $fila['idProducto'] . ", \"" . $fila['codigoProd'] . "\", \"" . $fila['nombreProd'] . "\", " . $fila['precioProd'] . ")'>";
                    $html .= "<td>" . $fila['codigoProd'] . "</td>";
                    $html .= "<td>" . $fila['nombreProd'] . "</td>";
                    $html .= "<td class='text-success fw-bold'>$" . number_format($fila['precioProd'], 2) . "</td>";
                    $html .= "</tr>";
                }
                $r['resultado'] = 'modalproductos';
                $r['mensaje'] = $html;
            }
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }

        return $r;
    }


    // MODIFICAR PEDIDO (CON TRANSACCIÓN Y VENTA)
    function modificar($idPedido, $idCliente, $idCuenta, $estadoPedido, $idProducto, $cantidad, $precio, $numComprobante, $tipoPago = '', $cedulaPago = '', $tlfPago = '')
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();

        try {
            $co->beginTransaction();

            // Bloquear y verificar existencia
            $bloqueo = $co->prepare('SELECT estadoPedido, tipoPago, cedulaPago, tlfPago, numComprobante FROM pedidos WHERE idPedido = :idPedido FOR UPDATE');
            $bloqueo->execute(array(':idPedido' => $idPedido));
            $pedidoActual = $bloqueo->fetch(PDO::FETCH_ASSOC);

            if (!$pedidoActual) {
                throw new Exception('El pedido indicado no existe.');
            }

            // Verificar si ya fue convertido a venta
            $vinculo = $co->prepare('SELECT idVenta FROM pedidosventa WHERE idPedido = :idPedido LIMIT 1');
            $vinculo->execute(array(':idPedido' => $idPedido));
            if ($vinculo->fetch(PDO::FETCH_ASSOC)) {
                throw new Exception('Este pedido ya fue convertido en venta y no puede procesarse nuevamente.');
            }

            if (!in_array($estadoPedido, array('En Proceso', 'Finalizado', 'Cancelado'), true)) {
                throw new Exception('Estado de pedido no válido.');
            }

            if (!is_array($idProducto) || count($idProducto) === 0 || count($idProducto) !== count($cantidad)) {
                throw new Exception('El pedido debe contener al menos un producto válido.');
            }

            // Procesar líneas y calcular totales
            $lineas = array();
            $precioTotal = 0;
            $cantidadTotal = 0;
            $buscarProducto = $co->prepare('SELECT precioProd FROM productos WHERE idProducto = :idProducto LIMIT 1');

            foreach ($idProducto as $i => $idProd) {
                $cant = filter_var($cantidad[$i] ?? null, FILTER_VALIDATE_INT);
                if (!$cant || $cant < 1) {
                    throw new Exception('La cantidad de uno de los productos no es válida.');
                }

                $buscarProducto->execute(array(':idProducto' => $idProd));
                $producto = $buscarProducto->fetch(PDO::FETCH_ASSOC);
                if (!$producto) {
                    throw new Exception('Uno de los productos seleccionados ya no existe.');
                }

                $precioReal = (float)$producto['precioProd'];
                $subtotal = $precioReal * $cant;

                $precioTotal += $subtotal;
                $cantidadTotal += $cant;
                $lineas[] = array('idProducto' => $idProd, 'cantidad' => $cant, 'subtotal' => $subtotal);
            }

            // Validar pagos si pasa a Finalizado
            $tipoPagoGuardar = trim($tipoPago) !== '' ? trim($tipoPago) : ($pedidoActual['tipoPago'] ?? '');
            $cedulaGuardar = trim((string)$cedulaPago);
            $telefonoGuardar = trim((string)$tlfPago);
            $referenciaGuardar = trim((string)$numComprobante);

            if ($estadoPedido === 'Finalizado') {
                if (!in_array($tipoPagoGuardar, array('Efectivo', 'Pago Móvil', 'Tarjeta'), true)) {
                    throw new Exception('Seleccione el método de pago antes de finalizar el pedido.');
                }
                if ($tipoPagoGuardar === 'Pago Móvil') {
                    if (empty($idCuenta) || $referenciaGuardar === '' || $cedulaGuardar === '' || $telefonoGuardar === '') {
                        throw new Exception('Para finalizar con Pago Móvil debe indicar cuenta destino, referencia, cédula y teléfono del pagador.');
                    }
                    if (strlen($cedulaGuardar) > 12 || strlen($telefonoGuardar) > 12 || strlen($referenciaGuardar) > 100) {
                        throw new Exception('Los datos del pago superan la longitud permitida.');
                    }
                }
            }

            $cuentaGuardar = in_array($tipoPagoGuardar, array('Efectivo', 'Tarjeta'), true) ? null : (($idCuenta === '' || $idCuenta === null) ? null : $idCuenta);

            // Actualizar cabecera de pedido
            $stmtP = $co->prepare('UPDATE pedidos SET idCliente = :idCliente, idCuenta = :idCuenta, precioTotal = :precioTotal, numComprobante = :numComprobante, tipoPago = :tipoPago, cedulaPago = :cedulaPago, tlfPago = :tlfPago, estadoPedido = :estadoPedido WHERE idPedido = :idPedido');
            $stmtP->execute(array(
                ':idCliente' => $idCliente,
                ':idCuenta' => $cuentaGuardar,
                ':precioTotal' => $precioTotal,
                ':numComprobante' => $referenciaGuardar !== '' ? $referenciaGuardar : null,
                ':tipoPago' => $tipoPagoGuardar !== '' ? $tipoPagoGuardar : null,
                ':cedulaPago' => $cedulaGuardar !== '' ? $cedulaGuardar : null,
                ':tlfPago' => $telefonoGuardar !== '' ? $telefonoGuardar : null,
                ':estadoPedido' => $estadoPedido,
                ':idPedido' => $idPedido
            ));

            // Reinsertar detalles del pedido
            $delD = $co->prepare('DELETE FROM pedidosproducto WHERE idPedido = :idPedido');
            $delD->execute(array(':idPedido' => $idPedido));

            $stmtD = $co->prepare('INSERT INTO pedidosproducto (idPedido, idProducto, cantidadProd, subTotal) VALUES (:idPedido, :idProducto, :cantidad, :subTotal)');
            foreach ($lineas as $linea) {
                $stmtD->execute(array(':idPedido' => $idPedido, ':idProducto' => $linea['idProducto'], ':cantidad' => $linea['cantidad'], ':subTotal' => $linea['subtotal']));
            }

            // Si está finalizado, generar la venta automática
            if ($estadoPedido === 'Finalizado') {
                $personal = $co->query('SELECT idPersonal FROM personal ORDER BY idPersonal ASC LIMIT 1')->fetch(PDO::FETCH_ASSOC);
                if (!$personal) {
                    throw new Exception('No hay personal registrado para asociar la venta. Registre un empleado antes de finalizar.');
                }

                $cedulaDetalle = $tipoPagoGuardar === 'Pago Móvil' ? $cedulaGuardar : '';
                $telefonoDetalle = $tipoPagoGuardar === 'Pago Móvil' ? $telefonoGuardar : '';
                $referenciaDetalle = $tipoPagoGuardar === 'Efectivo' ? '' : $referenciaGuardar;

                $insertarDetalle = $co->prepare('INSERT INTO detallesventa (cedulaPago, tlfPago, referencia) VALUES (:cedula, :telefono, :referencia)');
                $insertarDetalle->execute(array(':cedula' => $cedulaDetalle, ':telefono' => $telefonoDetalle, ':referencia' => $referenciaDetalle));
                $idDetalle = $co->lastInsertId();

                $insertarVenta = $co->prepare('INSERT INTO ventas (idDetalle, idPersonal, tipoPago, fechaVenta, cantidadVenta, totalVenta) VALUES (:idDetalle, :idPersonal, :tipoPago, :fechaVenta, :cantidad, :total)');
                $insertarVenta->execute(array(
                    ':idDetalle' => $idDetalle,
                    ':idPersonal' => $personal['idPersonal'],
                    ':tipoPago' => $tipoPagoGuardar,
                    ':fechaVenta' => date('Y-m-d'),
                    ':cantidad' => $cantidadTotal,
                    ':total' => $precioTotal
                ));
                $idVenta = $co->lastInsertId();

                $relacion = $co->prepare('INSERT INTO pedidosventa (idPedido, idVenta) VALUES (:idPedido, :idVenta)');
                $relacion->execute(array(':idPedido' => $idPedido, ':idVenta' => $idVenta));
            }

            $co->commit();
            $r['resultado'] = 'modificar';
            $r['mensaje'] = $estadoPedido === 'Finalizado' ? 'Pedido finalizado y venta registrada con éxito.' : 'Pedido modificado con éxito.';
        } catch (Exception $e) {
            if ($co->inTransaction()) {
                $co->rollBack();
            }

            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }

        return $r;
    }

    // ELIMINAR PEDIDO
    function eliminar($idPedido)
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();

        try {
            $co->beginTransaction();

            $delDetalle = $co->prepare("DELETE FROM pedidosproducto WHERE idPedido = :idPedido");
            $delDetalle->execute([':idPedido' => $idPedido]);

            $delPedido = $co->prepare("DELETE FROM pedidos WHERE idPedido = :idPedido");
            $delPedido->execute([':idPedido' => $idPedido]);

            $co->commit();
            $r['resultado'] = 'eliminar';
            $r['mensaje'] = 'Pedido eliminado con éxito.';
        } catch (Exception $e) {
            if ($co->inTransaction()) {
                $co->rollBack();
            }

            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }

        return $r;
    }
}

?>