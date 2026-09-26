<?php

require_once("modelo/datos.php");

class pedidos extends datos
{

    function incluir($idCliente, $idProducto, $cantidad, $precio)
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

            $sqlPedido = "INSERT INTO pedidos (idCliente, precioTotal, estadoPedido) VALUES (:idCliente, :precioTotal, 'En Proceso')";
            $stmt = $co->prepare($sqlPedido);
            $stmt->execute([
                ':idCliente' => $idCliente,
                ':precioTotal' => $precioTotal
            ]);

            $numPedido = $co->lastInsertId();

            $sqlDetalle = "INSERT INTO pedidosproducto (numPedido, idProducto, cantidadProd, subTotal) VALUES (:numPedido, :idProducto, :cantidad, :subTotal)";
            $stmtDetalle = $co->prepare($sqlDetalle);

            for ($i = 0; $i < count($idProducto); $i++) {
                $subT = $cantidad[$i] * $precio[$i];
                $stmtDetalle->execute([
                    ':numPedido' => $numPedido,
                    ':idProducto' => $idProducto[$i],
                    ':cantidad' => $cantidad[$i],
                    ':subTotal' => $subT
                ]);
            }

            $co->commit();
            $r['resultado'] = 'incluir';
            $r['mensaje'] = 'Pedido #' . str_pad($numPedido, 3, '0', STR_PAD_LEFT) . ' registrado con éxito.';
        } catch (Exception $e) {
            if ($co->inTransaction()) {
                $co->rollBack();
            }
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    function modificar($numPedido, $idCliente, $idProducto, $cantidad, $precio)
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

            $stmtP = $co->prepare("UPDATE pedidos SET idCliente = :idCliente, precioTotal = :precioTotal WHERE numPedido = :numPedido");
            $stmtP->execute([
                ':idCliente' => $idCliente,
                ':precioTotal' => $precioTotal,
                ':numPedido' => $numPedido
            ]);

            $delD = $co->prepare("DELETE FROM pedidosproducto WHERE numPedido = :numPedido");
            $delD->execute([':numPedido' => $numPedido]);

            $stmtD = $co->prepare("INSERT INTO pedidosproducto (numPedido, idProducto, cantidadProd, subTotal) VALUES (:numPedido, :idProducto, :cantidad, :subTotal)");
            for ($i = 0; $i < count($idProducto); $i++) {
                $subT = $cantidad[$i] * $precio[$i];
                $stmtD->execute([
                    ':numPedido' => $numPedido,
                    ':idProducto' => $idProducto[$i],
                    ':cantidad' => $cantidad[$i],
                    ':subTotal' => $subT
                ]);
            }

            $co->commit();
            $r['resultado'] = 'modificar';
            $r['mensaje'] = 'Pedido #' . str_pad($numPedido, 3, '0', STR_PAD_LEFT) . ' actualizado correctamente.';
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
            $resultado = $co->query("SELECT p.*, c.nombreCli, c.apellidoCli FROM pedidos p JOIN clientes c ON p.idCliente = c.idCliente ORDER BY p.numPedido DESC");
            $html = '';

            if ($resultado) {
                foreach ($resultado as $fila) {
                    $estadoClase = $fila['estadoPedido'] == 'En Proceso' ? 'bg-warning text-dark' : ($fila['estadoPedido'] == 'Cancelado' ? 'bg-danger' : 'bg-success');

                    $detalles = $co->prepare("SELECT pp.cantidadProd, pr.nombreProd FROM pedidosproducto pp JOIN productos pr ON pp.idProducto = pr.idProducto WHERE pp.numPedido = :numPedido");
                    $detalles->execute([':numPedido' => $fila['numPedido']]);
                    $listaDetalles = '';
                    foreach ($detalles as $det) {
                        $listaDetalles .= $det['cantidadProd'] . 'x ' . $det['nombreProd'] . ', ';
                    }
                    $listaDetalles = rtrim($listaDetalles, ', ');

                    $html .= '
                    <div class="card border-0 shadow-sm rounded-4 mb-2">
                        <div class="card-body p-4">
                            <div class="row align-items-center">
                                <div class="col-12 col-xl-7 mb-3 mb-xl-0">
                                    <div class="d-flex align-items-center gap-3 mb-2">
                                        <h5 class="card-title text-dashboard fw-bold mb-0">Orden #' . str_pad($fila['numPedido'], 3, '0', STR_PAD_LEFT) . '</h5>
                                        <span class="badge ' . $estadoClase . ' px-3 py-2 rounded-pill">' . $fila['estadoPedido'] . '</span>
                                    </div>
                                    <hr class="text-light-subtle my-2">
                                    <div class="row mt-3">
                                        <div class="col-sm-5 mb-2 mb-sm-0">
                                            <p class="mb-1 text-muted small">Cliente</p>
                                            <p class="fw-semibold mb-0"><i class="bi bi-person-fill text-secondary"></i> ' . $fila['nombreCli'] . ' ' . $fila['apellidoCli'] . '</p>
                                        </div>
                                        <div class="col-sm-7">
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
                                        <button type="button" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-2 mb-2 w-100" onclick="pone(' . $fila['numPedido'] . ')" title="Editar">
                                            <i class="bi bi-pencil-square"></i> Editar
                                        </button>
                                        <button type="button" class="btn btn-outline-danger btn-sm d-flex align-items-center gap-2 w-100" onclick="eliminar(' . $fila['numPedido'] . ')" title="Eliminar">
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

    function consultarUno($numPedido)
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try {
            $stmtP = $co->prepare("SELECT p.*, CONCAT(c.cedulaCli, ' - ', c.nombreCli, ' ', c.apellidoCli) as clienteInfo FROM pedidos p JOIN clientes c ON p.idCliente = c.idCliente WHERE p.numPedido = :numPedido");
            $stmtP->execute([':numPedido' => $numPedido]);
            $pedido = $stmtP->fetch(PDO::FETCH_ASSOC);

            $stmtD = $co->prepare("SELECT pp.*, pr.codigoProd, pr.nombreProd, pr.precioProd FROM pedidosproducto pp JOIN productos pr ON pp.idProducto = pr.idProducto WHERE pp.numPedido = :numPedido");
            $stmtD->execute([':numPedido' => $numPedido]);
            $detalles = $stmtD->fetchAll(PDO::FETCH_ASSOC);

            $r['resultado'] = 'consultar_uno';
            $r['pedido'] = $pedido;
            $r['detalles'] = $detalles;
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

    function eliminar($numPedido)
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try {
            $delDetalle = $co->prepare("DELETE FROM pedidosproducto WHERE numPedido = :numPedido");
            $delDetalle->execute([':numPedido' => $numPedido]);

            $delPedido = $co->prepare("DELETE FROM pedidos WHERE numPedido = :numPedido");
            $delPedido->execute([':numPedido' => $numPedido]);

            $r['resultado'] = 'eliminar';
            $r['mensaje'] = 'Pedido eliminado correctamente.';
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = 'No se puede eliminar el pedido, posiblemente esté facturado en Ventas.';
        }
        return $r;
    }
}

?>