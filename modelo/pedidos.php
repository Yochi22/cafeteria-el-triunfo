<?php

require_once("modelo/datos.php");

class pedidos extends datos
{
    function incluir($idCliente, $idCuenta, $idProducto, $cantidad, $precio)
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();

        try {
            $co->beginTransaction();
            $precioTotal = 0;

            for ($i = 0; $i < count($idProducto); $i++) {
                $precioTotal += ($cantidad[$i] * $precio[$i]);
            }

            $sqlPedido = "INSERT INTO pedidos (idCliente, idCuenta, precioTotal, estadoPedido) VALUES (:idCliente, :idCuenta, :precioTotal, 'En Proceso')";
            $stmt = $co->prepare($sqlPedido);
            $stmt->execute([
                ':idCliente' => $idCliente,
                ':idCuenta' => empty($idCuenta) ? null : $idCuenta,
                ':precioTotal' => $precioTotal
            ]);

            $idPedido = $co->lastInsertId();

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

    function consultar()
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();

        try {
            $resultado = $co->query("SELECT p.*, c.nombreCli, c.apellidoCli, cu.nombreBanco, cu.numCuenta FROM pedidos p JOIN clientes c ON p.idCliente = c.idCliente LEFT JOIN cuentas cu ON p.idCuenta = cu.idCuenta ORDER BY p.idPedido DESC");
            $html = '';

            if ($resultado) {
                foreach ($resultado as $fila) {
                    $estadoClase = $fila['estadoPedido'] == 'En Proceso' ? 'bg-warning text-dark' : ($fila['estadoPedido'] == 'Cancelado' ? 'bg-danger' : 'bg-success');

                    $detalles = $co->prepare("SELECT pp.cantidadProd, pr.nombreProd FROM pedidosproducto pp JOIN productos pr ON pp.idProducto = pr.idProducto WHERE pp.idPedido = :idPedido");
                    $detalles->execute([':idPedido' => $fila['idPedido']]);
                    $listaDetalles = '';

                    foreach ($detalles as $det) {
                        $listaDetalles .= $det['cantidadProd'] . 'x ' . $det['nombreProd'] . ', ';
                    }

                    $listaDetalles = rtrim($listaDetalles, ', ');
                    $bancoTexto = $fila['idCuenta'] ? $fila['nombreBanco'] . ' - ' . substr($fila['numCuenta'], -4) : 'Sin cuenta asignada';

                    $html .= '
                    <div class="card border-0 shadow-sm rounded-4 mb-2">
                        <div class="card-body p-4">
                            <div class="row align-items-center">
                                <div class="col-12 col-xl-7 mb-3 mb-xl-0">
                                    <div class="d-flex align-items-center gap-3 mb-2">
                                        <h5 class="card-title text-dashboard fw-bold mb-0">Orden #' . str_pad($fila['idPedido'], 3, '0', STR_PAD_LEFT) . '</h5>
                                        <span class="badge ' . $estadoClase . ' px-3 py-2 rounded-pill">' . $fila['estadoPedido'] . '</span>
                                    </div>
                                    <hr class="text-light-subtle my-2">
                                    <div class="row mt-3">
                                        <div class="col-sm-4 mb-2 mb-sm-0">
                                            <p class="mb-1 text-muted small">Cliente</p>
                                            <p class="fw-semibold mb-0"><i class="bi bi-person-fill text-secondary"></i> ' . $fila['nombreCli'] . ' ' . $fila['apellidoCli'] . '</p>
                                        </div>
                                        <div class="col-sm-4 mb-2 mb-sm-0">
                                            <p class="mb-1 text-muted small">Cuenta</p>
                                            <p class="fw-semibold mb-0"><i class="bi bi-bank text-secondary"></i> ' . $bancoTexto . '</p>
                                        </div>
                                        <div class="col-sm-4">
                                            <p class="mb-1 text-muted small">Detalle del pedido</p>
                                            <p class="mb-0 text-truncate" title="' . $listaDetalles . '">' . $listaDetalles . '</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-xl-5 d-flex justify-content-xl-end justify-content-between align-items-center gap-3 border-start-xl ps-xl-4">
                                    <div class="text-start text-xl-end mb-sm-0">
                                        <p class="text-muted small mb-0">Total</p>
                                        <h4 class="text-success fw-bold mb-0">$' . number_format($fila['precioTotal'], 2) . '</h4>
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-2 mb-2 w-100" onclick="pone(' . $fila['idPedido'] . ')" title="Editar">
                                            <i class="bi bi-pencil-square"></i> Editar
                                        </button>
                                        <button type="button" class="btn btn-outline-danger btn-sm d-flex align-items-center gap-2 w-100" onclick="eliminar(' . $fila['idPedido'] . ')" title="Eliminar">
                                            <i class="bi bi-trash-fill"></i> Eliminar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>';
                }
            }

            if ($html == '') {
                $html = '<div class="col-12 text-center text-muted py-5"><i class="bi bi-journal-x fs-1 text-warning"></i><h5 class="mt-2 text-dashboard">No hay pedidos registrados.</h5></div>';
            }

            $r['resultado'] = 'consultar';
            $r['mensaje'] = $html;
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }

        return $r;
    }

    public function consultarUno($idPedido)
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $stmt = $co->prepare("SELECT p.idPedido, p.idCliente, p.idCuenta, p.estadoPedido, c.cedulaCli, c.nombreCli, c.apellidoCli, cu.nombreBanco, cu.numCuenta FROM pedidos p INNER JOIN clientes c ON p.idCliente = c.idCliente LEFT JOIN cuentas cu ON p.idCuenta = cu.idCuenta WHERE p.idPedido = :idPedido");
            $stmt->execute([':idPedido' => $idPedido]);
            $datosPedido = $stmt->fetch(PDO::FETCH_ASSOC);

            $stmt2 = $co->prepare("SELECT pp.idProducto, pr.codigoProd, pr.nombreProd, pr.precioProd, pp.cantidadProd FROM pedidosproducto pp INNER JOIN productos pr ON pp.idProducto = pr.idProducto WHERE pp.idPedido = :idPedido");
            $stmt2->execute([':idPedido' => $idPedido]);
            $resultadosProductos = $stmt2->fetchAll(PDO::FETCH_ASSOC);

            $detalleHtml = "";
            foreach ($resultadosProductos as $fila) {
                $detalleHtml .= "<tr>";
                $detalleHtml .= "<td class='align-middle fw-bold'><input type='hidden' name='idProducto[]' value='" . $fila['idProducto'] . "'>" . $fila['codigoProd'] . "</td>";
                $detalleHtml .= "<td class='align-middle'>" . $fila['nombreProd'] . "</td>";
                $detalleHtml .= "<td class='align-middle'><input type='hidden' name='precio[]' value='" . $fila['precioProd'] . "'>$" . $fila['precioProd'] . "</td>";
                $detalleHtml .= "<td class='align-middle' style='width: 120px;'><input type='number' class='form-control form-control-sm text-center' value='" . $fila['cantidadProd'] . "' name='cantidad[]' min='1' onchange='modificasubtotal(this)' onkeyup='modificasubtotal(this)'></td>";
                $detalleHtml .= "<td class='align-middle text-success fw-bold subtotal-cell'>$" . ($fila['cantidadProd'] * $fila['precioProd']) . "</td>";
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
                "estadoPedido" => $datosPedido['estadoPedido'],
                "detalleHtml" => $detalleHtml
            );
        } catch (Exception $e) {
            return array("resultado" => "error", "mensaje" => $e->getMessage());
        }
    }

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

    function modificar($idPedido, $idCliente, $idCuenta, $estadoPedido, $idProducto, $cantidad, $precio)
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();

        try {
            $co->beginTransaction();

            $precioTotal = 0;
            for ($i = 0; $i < count($idProducto); $i++) {
                $precioTotal += ($cantidad[$i] * $precio[$i]);
            }

            $stmtP = $co->prepare("UPDATE pedidos SET idCliente = :idCliente, idCuenta = :idCuenta, precioTotal = :precioTotal, estadoPedido = :estadoPedido WHERE idPedido = :idPedido");
            $stmtP->execute([
                ':idCliente' => $idCliente,
                ':idCuenta' => empty($idCuenta) ? null : $idCuenta,
                ':precioTotal' => $precioTotal,
                ':estadoPedido' => $estadoPedido,
                ':idPedido' => $idPedido
            ]);

            $delD = $co->prepare("DELETE FROM pedidosproducto WHERE idPedido = :idPedido");
            $delD->execute([':idPedido' => $idPedido]);

            $stmtD = $co->prepare("INSERT INTO pedidosproducto (idPedido, idProducto, cantidadProd, subTotal) VALUES (:idPedido, :idProducto, :cantidad, :subTotal)");

            for ($i = 0; $i < count($idProducto); $i++) {
                $subT = $cantidad[$i] * $precio[$i];
                $stmtD->execute([
                    ':idPedido' => $idPedido,
                    ':idProducto' => $idProducto[$i],
                    ':cantidad' => $cantidad[$i],
                    ':subTotal' => $subT
                ]);
            }

            $co->commit();
            $r['resultado'] = 'modificar';
            $r['mensaje'] = 'Pedido modificado correctamente.';
        } catch (Exception $e) {
            if ($co->inTransaction()) {
                $co->rollBack();
            }

            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }

        return $r;
    }

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
            $r['mensaje'] = 'Pedido eliminado correctamente.';
        } catch (Exception $e) {
            if ($co->inTransaction()) {
                $co->rollBack();
            }

            $r['resultado'] = 'error';
            $r['mensaje'] = 'No se puede eliminar el pedido, posiblemente esté facturado en Ventas.';
        }

        return $r;
    }
}

?>