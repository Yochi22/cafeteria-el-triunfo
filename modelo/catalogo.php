<?php

require_once("modelo/datos.php");

class catalogo extends datos
{
    private function renderizarListaProductos($prods, $emptyIcon, $emptyTitle, $emptySubtitle = '')
    {
        if (!$prods) {
            $subtitleHtml = $emptySubtitle !== ''
                ? '<h6 class="text-dashboard text-secondary small mb-0">' . htmlspecialchars($emptySubtitle, ENT_QUOTES, 'UTF-8') . '</h6>'
                : '';

            return '
            <div class="col-12 text-start text-muted py-5">
                <div class="d-flex align-items-center gap-3">
                    <span><i class="bi ' . htmlspecialchars($emptyIcon, ENT_QUOTES, 'UTF-8') . ' fs-1" style="color: #FF8C00;"></i></span>
                    <div class="text-start">
                        <h5 class="text-dashboard mb-1">' . htmlspecialchars($emptyTitle, ENT_QUOTES, 'UTF-8') . '</h5>
                        ' . $subtitleHtml . '
                    </div>
                </div>
            </div>';
        }

        $html = '';

        foreach ($prods as $p) {
            $fotoProd = !empty($p['fotoProd']) ? $p['fotoProd'] : 'img/principal.jpg';
            $nombreProd = (string)$p['nombreProd'];
            $descProd = (string)$p['descProd'];
            $precioProd = (float)$p['precioProd'];
            $codigoProd = (string)$p['codigoProd'];

            $fotoAttr = htmlspecialchars($fotoProd, ENT_QUOTES, 'UTF-8');
            $nombreHtml = htmlspecialchars($nombreProd, ENT_QUOTES, 'UTF-8');
            $descHtml = htmlspecialchars($descProd, ENT_QUOTES, 'UTF-8');
            $codigoJs = htmlspecialchars(addslashes($codigoProd), ENT_QUOTES, 'UTF-8');
            $nombreJs = htmlspecialchars(addslashes($nombreProd), ENT_QUOTES, 'UTF-8');
            $descJs = htmlspecialchars(addslashes($descProd), ENT_QUOTES, 'UTF-8');
            $fotoJs = htmlspecialchars(addslashes($fotoProd), ENT_QUOTES, 'UTF-8');

            $html .= '
            <div class="card border-0 rounded-4 shadow-sm mb-3 p-3 bg-white dynamic-card text-start">
                <div class="d-flex flex-row align-items-center justify-content-between gap-2 gap-sm-3">
                    <div class="d-flex align-items-center gap-2 gap-sm-3 flex-grow-1 text-start" style="min-width: 0;">
                        <img src="' . $fotoAttr . '" alt="' . $nombreHtml . '" class="img-fluid rounded-3 flex-shrink-0" style="height: 76px; width: 76px; object-fit: cover;">
                        <div class="flex-grow-1 text-start" style="min-width: 0;">
                            <h5 class="text-dashboard fw-bold mb-1 text-truncate">' . $nombreHtml . '</h5>
                            <p class="text-muted small mb-1 text-truncate">' . $descHtml . '</p>
                            <p class="text-success fw-bold mb-0">$' . number_format($precioProd, 2) . '</p>
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                        <button type="button" class="btn btn-sm rounded-pill px-3 py-2 fw-bold shadow-sm text-nowrap" style="color: #FF8C00; border: 1px solid #FF8C00;" onclick="abrirModalDetalle(\'' . $codigoJs . '\', \'' . $nombreJs . '\', \'' . $descJs . '\', ' . $precioProd . ', \'' . $fotoJs . '\')">
                            <i class="bi bi-cart2"></i> <span class="d-none d-sm-inline ms-1">Agregar al Carrito</span><span class="d-inline d-sm-none ms-1">Agregar</span>
                        </button>
                    </div>
                </div>
            </div>';
        }

        return $html;
    }

    function obtenerCategorias()
    {
        $r = array();
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $stmt = $co->query("SELECT * FROM categorias");
            $cats = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $html = '';
            $html .= '
            <a href="#" class="list-group-item list-group-item-action rounded-3 border-0 fw-semibold text-dark text-start mb-1 active-category" onclick="cargarProductos(); return false;">
                <i class="bi bi-grid me-2"></i> Todos los productos
            </a>';

            if ($cats) {
                foreach ($cats as $c) {
                    $idCat = (int)$c['idCategoria'];
                    $nombreCat = (string)$c['nombreCat'];
                    $nombreCatHtml = htmlspecialchars($nombreCat, ENT_QUOTES, 'UTF-8');
                    $nombreCatJs = htmlspecialchars(addslashes($nombreCat), ENT_QUOTES, 'UTF-8');

                    $html .= '
                    <a href="#" class="list-group-item list-group-item-action rounded-3 border-0 text-secondary text-start mb-1" onclick="listarProductos(' . $idCat . ', \'' . $nombreCatJs . '\'); return false;">
                        <i class="bi bi-tag me-2"></i> ' . $nombreCatHtml . '
                    </a>';
                }
            } else {
                $html .= '<div class="text-start text-muted py-3 small">No hay categorías</div>';
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

            $html = $this->renderizarListaProductos(
                $prods,
                'bi-box-seam',
                'No hay productos registrados.'
            );

            $r['resultado'] = 'cargarProductos';
            $r['mensaje'] = $html;
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }

        return $r;
    }

    function listarProductos($idCategoria)
    {
        $r = array();
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $stmt = $co->prepare("SELECT * FROM productos WHERE idCategoria = ?");
            $stmt->execute([$idCategoria]);
            $prods = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $html = $this->renderizarListaProductos(
                $prods,
                'bi-folder2-open',
                'No hay productos en esta categoría.'
            );

            $r['resultado'] = 'listarProductos';
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

            $html = $this->renderizarListaProductos(
                $prods,
                'bi-search',
                'No se encuentran productos',
                'Intenta con otra búsqueda.'
            );

            $r['resultado'] = 'buscar';
            $r['mensaje'] = $html;
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }

        return $r;
    }

    public function obtenerTasaHoy()
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();

        try {
            date_default_timezone_set('America/Caracas');

            $fecha = date('Y-m-d');

            $resultado = $co->prepare("SELECT precioTasa FROM tasadiaria WHERE fechaTasa = :fechaTasa");
            $resultado->bindParam(':fechaTasa', $fecha);
            $resultado->execute();
            $precioTasa = $resultado->fetchColumn();

            if ($precioTasa !== false) {
                $r['resultado'] = 'tasaActual';
                $r['precio'] = floatval($precioTasa);
            } else {
                $r['resultado'] = 'error';
                $r['mensaje'] = 'No hay tasas registradas en el sistema.';
            }
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
                $precioProd = (float)$p['precioProd'];
                $cantidadProd = (int)$p['cantidad'];
                $fotoProd = !empty($p['fotoProd']) ? (string)$p['fotoProd'] : 'img/principal.jpg';
                $nombreProd = (string)$p['nombreProd'];

                $subtotalProd = $precioProd * $cantidadProd;
                $subtotalGeneral += $subtotalProd;

                $fotoAttr = htmlspecialchars($fotoProd, ENT_QUOTES, 'UTF-8');
                $nombreHtml = htmlspecialchars($nombreProd, ENT_QUOTES, 'UTF-8');

                $html .= '
                    <div class="col-12 col-md-6 carritoContenido" data-precio="' . $precioProd . '" data-cantidad="' . $cantidadProd . '">
                        <div class="card border-0 rounded-4 shadow-sm h-100 text-start">
                            <div class="card-body d-flex flex-row align-items-center justify-content-between gap-2 p-3">
                                <div class="d-flex align-items-center gap-2 gap-sm-3 flex-grow-1 text-start" style="min-width: 0;">
                                    <img src="' . $fotoAttr . '" alt="' . $nombreHtml . '" class="img-fluid rounded-3 shadow-sm flex-shrink-0" style="height: 76px; width: 76px; object-fit: cover;">
                                    <div class="flex-grow-1 text-start" style="min-width: 0;">
                                        <h6 class="fw-bold mb-1 text-truncate">' . $nombreHtml . '</h6>
                                        <div class="small fw-bold mb-2 text-success text-start">
                                            <i class="bi bi-star-fill text-warning"></i> <span class="precioProdBs">Bs. 0,00</span>
                                            <span class="text-muted ms-2 fw-bold" style="font-size: 0.8rem;">($' . number_format($precioProd, 2) . ')</span>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-light text-danger border px-2 py-1 shadow-sm btn-eliminar" data-index="' . (int)$index . '">
                                            <i class="bi bi-trash me-1"></i>Eliminar
                                        </button>
                                    </div>
                                </div>
                                <div class="d-flex flex-column align-items-center qty-controls p-1 flex-shrink-0">
                                    <button type="button" class="btn btn-sm text-orange p-0 px-2" onclick="cambiarCantidadLocal(' . (int)$index . ', 1)"><i class="bi bi-plus"></i></button>
                                    <span class="fw-bold small my-1">' . $cantidadProd . '</span>
                                    <button type="button" class="btn btn-sm text-secondary p-0 px-2" onclick="cambiarCantidadLocal(' . (int)$index . ', -1)"><i class="bi bi-dash"></i></button>
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
            $r['mensaje']  = '<div class="col-12 text-start py-4 text-dashboard">
                                <div class="d-flex align-items-center gap-3">
                                    <i class="bi bi-cart-x fs-1" style="color: #FF8C00;"></i>
                                    <p class="mb-0 fw-semibold">Tu carrito está vacío.</p>
                                </div>
                            </div>';
            $r['subtotal'] = '0.00';
        }

        return $r;
    }

    public function autocompletarCliente($cedula)
    {
        $r = array();
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $cedulaBuscar = trim($cedula);

            $stmt = $co->prepare("SELECT * FROM clientes WHERE cedulaCli = :cedula LIMIT 1");
            $stmt->bindParam(":cedula", $cedulaBuscar);
            $stmt->execute();

            $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($cliente) {
                $r["cliente"] = $cliente;
            } else {
                $r["cliente"] = null;
            }

            $r["resultado"] = "clienteEncontrado";
            $r["mensaje"] = '¡Bienvenido de nuevo!';
        } catch (Exception $e) {
            $r["resultado"] = "error";
            $r["mensaje"] = $e->getMessage();
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
                $html .= '<option value="' . htmlspecialchars((string)$c['idCuenta'], ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars((string)$c['nombreBanco'], ENT_QUOTES, 'UTF-8') . '</option>';
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
                $bancoHtml = htmlspecialchars((string)$cuenta['nombreBanco'], ENT_QUOTES, 'UTF-8');
                $cedulaHtml = htmlspecialchars((string)$cuenta['cedulaTitular'], ENT_QUOTES, 'UTF-8');
                $tlfHtml = htmlspecialchars((string)$cuenta['tlfCuenta'], ENT_QUOTES, 'UTF-8');
                $numHtml = htmlspecialchars((string)$cuenta['numCuenta'], ENT_QUOTES, 'UTF-8');
                $montoHtml = htmlspecialchars((string)$montoTotal, ENT_QUOTES, 'UTF-8');

                $html = '
                <div class="text-start">
                    <div class="alert alert-light border p-3 rounded-3 mb-3 position-relative">
                        <button type="button" class="btn btn-secondary-sm position-absolute top-0 end-0 m-2" id="btnCopiarDatos" title="Copiar datos">
                            <i class="bi bi-copy"></i>
                        </button>
                        <p class="mb-1" id="copiarBanco"><strong>Banco:</strong> ' . $bancoHtml . '</p>
                        <p class="mb-1" id="copiarCedula"><strong>Cédula:</strong> ' . $cedulaHtml . '</p>
                        <p class="mb-0" id="copiarTlfn"><strong>Teléfono:</strong> ' . $tlfHtml . '</p>
                        <p class="mb-1" id="copiarNum"><strong>Nro. de Cuenta:</strong> <span class="text-dark fw-bold">' . $numHtml . '</span></p>
                    </div>
                    <span class="text-muted small d-block">Monto a pagar:</span>
                    <h4 class="text-success fw-bold mb-0">' . $montoHtml . '</h4>
                </div>';

                $r['resultado'] = 'mostrarDetalleCuenta';
                $r['mensaje'] = $html;
            } else {
                $r['resultado'] = 'error';
                $r['mensaje'] = 'No se encontró la información de la cuenta.';
            }
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = 'Error en base de datos: ' . $e->getMessage();
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
            $numCasa = trim($datos['numCasa'] ?? '');

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