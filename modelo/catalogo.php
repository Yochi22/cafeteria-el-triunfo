<?php

require_once("modelo/datos.php");

class catalogo extends datos
{
    function obtener_datos_categorias()
    {
        $r = array();
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $stmt = $co->query("SELECT * FROM categorias");
            $cats = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $html = '';
            $html .= '
            <a href="#" class="list-group-item list-group-item-action rounded-3 border-0 fw-semibold text-dark mb-1 active-category" onclick="cargarProductos(); return false;">
                <i class="bi bi-grid me-2"></i> Todos los productos
            </a>';

            if ($cats) {
                foreach ($cats as $c) {
                    $idCat = $c['idCategoria'];
                    $nombreCat = $c['nombreCat'];

                    $html .= '
                    <a href="#" class="list-group-item list-group-item-action rounded-3 border-0 text-secondary mb-1" onclick="listar_productos(' . $idCat . ', \'' . $nombreCat . '\'); return false;">
                        <i class="bi bi-tag me-2"></i> ' . $nombreCat . '
                    </a>';
                }
            } else {
                $html .= '<div class="text-center text-muted py-3 small">No hay categorías</div>';
            }

            $r['resultado'] = 'categorias_cargadas';
            $r['mensaje'] = $html;
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }

        return $r;
    }

    function cargarProductos()
    {
        $r = array();
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $stmt = $co->query("SELECT * FROM productos");
            $prods = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $html = '';

            if ($prods) {
                foreach ($prods as $p) {
                    $fotoProd = !empty($p['fotoProd']) ? $p['fotoProd'] : 'img/principal.jpg';
                    $nombreProd = $p['nombreProd'];
                    $descProd = $p['descProd'];
                    $precioProd = $p['precioProd'];
                    $codigoProd = $p['codigoProd'];

                    $html .= '
                    <div class="card border-0 rounded-4 shadow-sm mb-3 p-3 bg-white dynamic-card">
                        <div class="row align-items-center">
                            <div class="col-4 col-md-2 text-center">
                                <img src="' . $fotoProd . '" class="img-fluid rounded-3" style="height:90px; width:90px; object-fit:cover;">
                            </div>
                            <div class="col-8 col-md-7">
                                <div class="card-body p-0">
                                    <h5 class="text-dashboard fw-bold mb-1">' . $nombreProd . '</h5>
                                    <p class="text-muted small mb-2 text-truncate">' . $descProd . '</p>
                                    <p class="text-success fw-bold mb-0">$' . number_format($precioProd, 2) . '</p>
                                </div>
                            </div>
                            <div class="col-12 col-md-3 text-end mt-3 mt-md-0">
                                <button class="btn btn-sm rounded-pill px-3 py-2 fw-bold w-100 shadow-sm" style="color: #FF8C00; border: 1px solid #FF8C00;" onclick="abrirModalDetalle(\'' . $codigoProd . '\', \'' . $nombreProd . '\', \'' . $descProd . '\', ' . $precioProd . ', \'' . $fotoProd . '\')">
                                    <i class="bi bi-cart2"></i> Agregar al carrito
                                </button>
                            </div>
                        </div>
                    </div>';
                }
            } else {
                $html = '
                <div class="col-12 text-center text-muted py-5">
                    <span><i class="bi bi-box-seam fs-1" style="color: #FF8C00"></i></span>
                    <h5 class="text-dashboard mt-2">No hay productos registrados.</h5>
                </div>';
            }

            $r['resultado'] = 'cargarProductos';
            $r['mensaje'] = $html;
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }

        return $r;
    }

    function listar_productos($idCategoria)
    {
        $r = array();
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $stmt = $co->prepare("SELECT * FROM productos WHERE idCategoria = ?");
            $stmt->execute([$idCategoria]);
            $prods = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $html = '';

            if ($prods) {
                foreach ($prods as $p) {
                    $fotoProd = !empty($p['fotoProd']) ? $p['fotoProd'] : 'img/principal.jpg';
                    $nombreProd = $p['nombreProd'];
                    $descProd = $p['descProd'];
                    $precioProd = $p['precioProd'];
                    $codigoProd = $p['codigoProd'];

                    $html .= '
                    <div class="card border-0 rounded-4 shadow-sm mb-3 p-3 bg-white dynamic-card">
                        <div class="row align-items-center">
                            <div class="col-4 col-md-2 text-center">
                                <img src="' . $fotoProd . '" class="img-fluid rounded-3" style="height:90px; width:90px; object-fit:cover;">
                            </div>
                            <div class="col-8 col-md-7">
                                <div class="card-body p-0">
                                    <h5 class="text-dashboard fw-bold mb-1">' . $nombreProd . '</h5>
                                    <p class="text-muted small mb-2 text-truncate">' . $descProd . '</p>
                                    <p class="text-success fw-bold mb-0">$' . number_format($precioProd, 2) . '</p>
                                </div>
                            </div>
                            <div class="col-12 col-md-3 text-end mt-3 mt-md-0">
                                <button class="btn btn-sm rounded-pill px-3 py-2 fw-bold w-100 shadow-sm" style="color: #FF8C00; border: 1px solid #FF8C00;" onclick="abrirModalDetalle(\'' . $codigoProd . '\', \'' . $nombreProd . '\', \'' . $descProd . '\', ' . $precioProd . ', \'' . $fotoProd . '\')">
                                    <i class="bi bi-eye me-1"></i> Ver / Comprar
                                </button>
                            </div>
                        </div>
                    </div>';
                }
            } else {
                $html = '
                <div class="col-12 text-center text-muted py-5">
                    <span><i class="bi bi-folder2-open fs-1" style="color: #FF8C00"></i></span>
                    <h5 class="text-dashboard mt-2">No hay productos en esta categoría.</h5>
                </div>';
            }

            $r['resultado'] = 'listar_productos';
            $r['mensaje'] = $html;
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }

        return $r;
    }

    function buscar($valor)
    {
        $r = array();
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $busqueda = "%" . trim($valor) . "%";

        try {
            $b = $co->prepare("SELECT * FROM productos WHERE (codigoProd LIKE :busqueda OR nombreProd LIKE :busqueda OR descProd LIKE :busqueda)");
            $b->bindParam(':busqueda', $busqueda);
            $b->execute();
            $prods = $b->fetchAll(PDO::FETCH_ASSOC);

            $html = '';

            if ($prods) {
                foreach ($prods as $p) {
                    $fotoProd = !empty($p['fotoProd']) ? $p['fotoProd'] : 'img/principal.jpg';
                    $nombreProd = $p['nombreProd'];
                    $descProd = $p['descProd'];
                    $precioProd = $p['precioProd'];
                    $codigoProd = $p['codigoProd'];

                    $html .= '
                    <div class="card border-0 rounded-4 shadow-sm mb-3 p-3 bg-white dynamic-card">
                        <div class="row align-items-center">
                            <div class="col-4 col-md-2 text-center">
                                <img src="' . $fotoProd . '" class="img-fluid rounded-3" style="height:90px; width:90px; object-fit:cover;">
                            </div>
                            <div class="col-8 col-md-7">
                                <div class="card-body p-0">
                                    <h5 class="text-dashboard fw-bold mb-1">' . $nombreProd . '</h5>
                                    <p class="text-muted small mb-2 text-truncate">' . $descProd . '</p>
                                    <p class="text-success fw-bold mb-0">$' . number_format($precioProd, 2) . '</p>
                                </div>
                            </div>
                            <div class="col-12 col-md-3 text-end mt-3 mt-md-0">
                                <button class="btn btn-sm rounded-pill px-3 py-2 fw-bold w-100 shadow-sm" style="color: #FF8C00; border: 1px solid #FF8C00;" onclick="abrirModalDetalle(\'' . $codigoProd . '\', \'' . $nombreProd . '\', \'' . $descProd . '\', ' . $precioProd . ', \'' . $fotoProd . '\')">
                                    <i class="bi bi-eye me-1"></i> Ver / Comprar
                                </button>
                            </div>
                        </div>
                    </div>';
                }
            } else {
                $html = '
                <div class="col-12 text-center text-muted py-5">
                    <span><i class="bi bi-search fs-1" style="color: #FF8C00"></i></span>
                    <h5 class="text-dashboard mt-2">No se encuentran productos</h5>
                    <h6 class="text-dashboard text-secondary small">Intenta con otra búsqueda.</h6>
                </div>';
            }

            $r['resultado'] = 'buscar';
            $r['mensaje'] = $html;
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }

        return $r;
    }

    public function cargarCarrito($productos)
    {
        $r = array();
        $carrito = json_decode($productos, true);

        $html = '';
        $subtotalGeneral = 0;

        if (!empty($carrito)) {
            foreach ($carrito as $index => $p) {
                $subtotalProd = $p['precioProd'] * $p['cantidad'];
                $subtotalGeneral += $subtotalProd;

                $html .= '
                    <div class="col-12 col-md-6 carritoContenido" data-precio="' . $p['precioProd'] . '" data-cantidad="1">
                        <div class="card border-0 rounded-4 shadow-sm h-100">
                            <div class="card-body d-flex align-items-center p-3">
                            <div class="me-3">
                                <img src="' . $p['fotoProd'] . '" alt="' . $p['nombreProd'] . '" class="img-fluid rounded-3 shadow-sm" style="height: 90px; width: 90px; object-fit: cover;">
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-1">' . $p['nombreProd'] . '</h6>
                                <div class="small fw-bold mb-2 text-success">
                                    <i class="bi bi-star-fill text-warning"></i> <span class="precioProdBs">Bs. 0,00</span> 
                                    <span class="text-muted ms-2 fw-bold" style="font-size: 0.8rem;">(' . '$' . number_format($p['precioProd'], 2) . ')</span>
                                </div>
                                <button class="btn btn-sm btn-light text-danger border px-2 py-1 shadow-sm btn-eliminar" data-index="' . $index . '">
                                    <i class="bi bi-trash me-1"></i>Eliminar
                                </button>
                            </div>
                            <div class="d-flex flex-column align-items-end ms-2 h-100 justify-content-center">
                                <div class="d-flex flex-column align-items-center qty-controls p-1">
                                    <button class="btn btn-sm text-orange p-0 px-2" onclick="cambiarCantidadLocal(' . $index . ', 1)"><i class="bi bi-plus"></i></button>
                                    <span class="fw-bold small my-1">' . $p['cantidad'] . '</span>
                                    <button class="btn btn-sm text-secondary p-0 px-2" onclick="cambiarCantidadLocal(' . $index . ', -1)"><i class="bi bi-dash"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';
            }

            $r['resultado'] = 'cargarCarrito';
            $r['mensaje']  = $html;
            $r['subtotal'] = number_format($subtotalGeneral, 2, '.', '');
        } else {
            $r['resultado'] = 'cargarCarrito';
            $r['mensaje']  = '<div class="col-12 text-center py-4 text-dashboard">
                                <i class="bi bi-cart-x fs-1 mb-2" style="color: #FF8C00;"></i>
                                <p class="mb-0 fw-semibold">Tu carrito está vacío.</p>
                            </div>';
            $r['subtotal'] = '0.00';
        }

        return $r;
    }

    public function cargarCuentas()
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();

        try {
            $cc = $co->prepare("SELECT idCuenta, nombreBanco FROM cuentas");
            $cc->execute();
            $cuentas = $cc->fetchAll(PDO::FETCH_ASSOC);

            $html = '<option value="">Seleccione...</option>';
            foreach ($cuentas as $c) {
                $html .= '<option value="' . $c['idCuenta'] . '">' . $c['nombreBanco'] . '</option>';
            }

            $r['resultado'] = 'cuentasCargadas';
            $r['mensaje'] = $html;
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = 'Error al cargar cuentas: ' . $e->getMessage();
        }

        return $r;
    }

    public function obtenerPrecioDelivery($datos)
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();

        try {
            $zona = trim($datos['zona'] ?? '');

            if ($zona === '') {
                throw new Exception('Zona no especificada.');
            }

            $zonasPermitidas = array('Este', 'Oeste', 'Norte', 'Sur', 'Centro');
            if (!in_array($zona, $zonasPermitidas)) {
                throw new Exception('Zona no válida.');
            }

            $de = $co->prepare("SELECT precio FROM preciosdelivery WHERE nombreZona = :zona LIMIT 1");
            $de->execute([':zona' => $zona]);
            $resultado = $de->fetch(PDO::FETCH_ASSOC);

            $precio = $resultado ? (float)$resultado['precio'] : 0.00;

            $r['resultado'] = 'precioDeliveryObtenido';
            $r['precio'] = $precio;
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }

        return $r;
    }

    public function detalleCuenta($idCuenta, $montoTotal)
{
    $co = $this->conecta();
    $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $r = array();

    try {
        $cc = $co->prepare("SELECT * FROM cuentas WHERE idCuenta = ?");
        $cc->execute([$idCuenta]);
        $cuenta = $cc->fetch(PDO::FETCH_ASSOC);

        if ($cuenta) {
            $textoACopiar = "Banco: " . $cuenta['nombreBanco'] . "\n" .
                           "Cédula: " . $cuenta['cedulaTitular'] . "\n" .
                           "Teléfono: " . $cuenta['tlfCuenta'] . "\n" .
                           "Nro. de Cuenta: " . $cuenta['numCuenta'] . "\n" .
                           "Monto a pagar: " . $montoTotal;

            $r['resultado'] = 'mostrarDetalleCuenta';
            $r['mensaje'] = $textoACopiar;
        }} catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }

    return $r; 
}

    public function registrarPedidoCliente($datos)
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();

        try {
            $nombre = trim($datos['nombreCli'] ?? '');
            $apellido = trim($datos['apellidoCli'] ?? '');
            $cedula = trim($datos['cedulaCli'] ?? '');
            $telefono = trim($datos['tlfCli'] ?? '');

            $zona = trim($datos['zonaDelivery'] ?? '');
            $calle = trim($datos['calleDelivery'] ?? '');
            $numCasa = filter_var($datos['numCasa'] ?? null, FILTER_VALIDATE_INT);

            $tipoPago = trim($datos['tipoPago'] ?? '');
            $idCuenta = trim($datos['cuentaDestino'] ?? '');
            $referencia = trim($datos['refPago'] ?? '');
            $cedulaPago = trim($datos['cedulaPago'] ?? '');
            $tlfPago = trim($datos['tlfPago'] ?? '');
            $carrito = json_decode($datos['carrito'] ?? '[]', true);

            $co->beginTransaction();

            $buscarCliente = $co->prepare('SELECT idCliente FROM clientes WHERE cedulaCli = :cedula LIMIT 1');
            $buscarCliente->execute(array(':cedula' => $cedula));
            $clienteExistente = $buscarCliente->fetch(PDO::FETCH_ASSOC);

            if ($clienteExistente) {
                $idCliente = $clienteExistente['idCliente'];
                $actualizarCliente = $co->prepare('UPDATE clientes SET nombreCli = :nombre, apellidoCli = :apellido, tlfCli = :telefono WHERE idCliente = :idCliente');
                $actualizarCliente->execute(array(':nombre' => $nombre, ':apellido' => $apellido, ':telefono' => $telefono, ':idCliente' => $idCliente));
            } else {
                $insertarCliente = $co->prepare('INSERT INTO clientes (cedulaCli, nombreCli, apellidoCli, tlfCli) VALUES (:cedula, :nombre, :apellido, :telefono)');
                $insertarCliente->execute(array(':cedula' => $cedula, ':nombre' => $nombre, ':apellido' => $apellido, ':telefono' => $telefono));
                $idCliente = $co->lastInsertId();
            }

            $lineas = array();
            $precioTotal = 0;
            $consultaProducto = $co->prepare('SELECT idProducto, precioProd FROM productos WHERE codigoProd = :codigo LIMIT 1');
            foreach ($carrito as $item) {
                $codigo = trim($item['codigoProd'] ?? '');
                $cantidad = filter_var($item['cantidad'] ?? null, FILTER_VALIDATE_INT);
                if ($codigo === '' || !$cantidad || $cantidad < 1) {
                    throw new Exception('Hay un producto o cantidad inválida en el carrito. Vacíe y vuelva a agregar los productos.');
                }
                $consultaProducto->execute(array(':codigo' => $codigo));
                $producto = $consultaProducto->fetch(PDO::FETCH_ASSOC);
                if (!$producto) {
                    throw new Exception('Uno de los productos del carrito ya no está disponible. Actualice el carrito.');
                }
                $subtotal = (float)$producto['precioProd'] * $cantidad;
                $precioTotal += $subtotal;
                $lineas[] = array('idProducto' => $producto['idProducto'], 'cantidad' => $cantidad, 'subtotal' => $subtotal);
            }

            // Sumar el precio del delivery al total del pedido
            $precioDelivery = 0.00;
            if ($zona !== '') {
                $stmtDeliv = $co->prepare("SELECT precio FROM preciosdelivery WHERE nombreZona = :zona LIMIT 1");
                $stmtDeliv->execute([':zona' => $zona]);
                $resDeliv = $stmtDeliv->fetch(PDO::FETCH_ASSOC);
                if ($resDeliv) {
                    $precioDelivery = (float)$resDeliv['precio'];
                }
            }
            $precioTotal += $precioDelivery;

            $idCuentaGuardar = ($tipoPago === 'Pago Móvil' && $idCuenta !== '') ? $idCuenta : null;
            $referenciaGuardar = ($tipoPago === 'Efectivo') ? null : ($referencia !== '' ? $referencia : null);
            $cedulaPagoGuardar = ($tipoPago === 'Pago Móvil' && $cedulaPago !== '') ? $cedulaPago : null;
            $tlfPagoGuardar = ($tipoPago === 'Pago Móvil' && $tlfPago !== '') ? $tlfPago : null;

            $insertarPedido = $co->prepare("INSERT INTO pedidos (idCliente, idCuenta, precioTotal, numComprobante, tipoPago, estadoPedido, cedulaPago, tlfPago) VALUES (:idCliente, :idCuenta, :total, :referencia, :tipoPago, 'En Proceso', :cedulaPago, :tlfPago)");
            $insertarPedido->execute(array(
                ':idCliente' => $idCliente,
                ':idCuenta' => $idCuentaGuardar,
                ':total' => $precioTotal,
                ':referencia' => $referenciaGuardar,
                ':tipoPago' => $tipoPago,
                ':cedulaPago' => $cedulaPagoGuardar,
                ':tlfPago' => $tlfPagoGuardar
            ));

            $idPedido = $co->lastInsertId();

            $insertarDir = $co->prepare('INSERT INTO dirdelivery (idPedido, zona, calle, numCasa) VALUES (:idPedido, :zona, :calle, :numCasa)');
            $insertarDir->execute(array(
                ':idPedido' => $idPedido,
                ':zona' => $zona,
                ':calle' => $calle,
                ':numCasa' => $numCasa
            ));

            $insertarDetalle = $co->prepare('INSERT INTO pedidosproducto (idPedido, idProducto, cantidadProd, subTotal) VALUES (:idPedido, :idProducto, :cantidad, :subtotal)');
            foreach ($lineas as $linea) {
                $insertarDetalle->execute(array(
                    ':idPedido' => $idPedido,
                    ':idProducto' => $linea['idProducto'],
                    ':cantidad' => $linea['cantidad'],
                    ':subtotal' => $linea['subtotal']
                ));
            }

            $co->commit();
            $r['resultado'] = 'pedidoClienteRegistrado';
            $r['mensaje'] = 'Tu pedido fue registrado correctamente. Número de pedido: #' . $idPedido;
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