<!DOCTYPE html>
<html lang="es">
<?php require_once("comunes/encabezado.php"); ?>
<?php require_once("comunes/menu_usuario.php"); ?>

<body>
    <main class="bg-light">
        <div class="container-fluid p-0 px-md-5 mb-5">
            <div class="row g-0 mb-4">
                <div class="col-12">
                    <div class="hero-banner-single rounded-4 overflow-hidden position-relative shadow-sm" style="background-color: #FF8C00;">
                        <img src="img/imagen1.jpg" class="hero-img img-fluid w-100 rounded-4" style="height: 320px; object-fit: cover; object-position: center bottom;" alt="Banner principal">
                    </div>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-12 col-lg-3">
                    <div class="sticky-top" style="top: 20px;">

                        <div class="d-lg-none mb-3">
                            <button class="btn w-100 d-flex justify-content-between align-items-center rounded-3 py-2 px-3 shadow-sm bg-white"
                                type="button" data-bs-toggle="collapse" data-bs-target="#menuRes" aria-expanded="false" aria-controls="menuRes">
                                <span class="fw-bold text-dashboard"><i class="bi bi-list me-2 fs-5"></i> Categorías</span>
                            </button>
                        </div>

                        <div class="collapse d-lg-block" id="menuRes">
                            <div class="card border-0 shadow-sm rounded-4 p-3 mb-4 bg-white">
                                <h5 class="fw-bold mb-3 px-2 text-dashboard d-none d-lg-block">Categorías</h5>
                                <div class="list-group list-group-flush gap-1" id="contenedor_categorias">

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-12 col-lg-9">

                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3 bg-white p-3 rounded-4 shadow-sm">
                        <div>
                            <h3 id="titulo_categoria_activo" class="fw-bold text-dark mb-1">Todos los productos</h3>
                        </div>

                        <div class="input-group bg-light border rounded-3 px-2 py-1 align-items-center w-auto shadow-none" style="max-width: 280px;">
                            <button class="btn p-0 border-0 link-secondary me-2" type="button" id="btnBuscar">
                                <i class="bi bi-search"></i>
                            </button>
                            <input type="text" id="valorBusqueda" name="valorBusqueda" class="form-control border-0 shadow-none bg-transparent form-control-sm" placeholder="Buscar platillo...">
                        </div>
                    </div>
                    <div id="cuadricula_items" class="d-flex flex-column gap-3">
                    </div>

                </div>

            </div>

        </div>
    </main>

    <div class="modal fade" id="modalDetalleProducto" tabindex="-1" aria-labelledby="modalDetalleLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-dashboard" id="modalDetalleLabel">Detalle del Producto</h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 pt-2">
                    <input type="hidden" id="modal-id-producto">
                    <div class="row g-4 align-items-center">
                        <div class="col-md-6 text-center">
                            <img id="modal-img" src="" class="img-fluid rounded-4 shadow-sm w-100 object-fit-cover" style="max-height: 280px;" alt="Producto">
                        </div>
                        <div class="col-md-6">
                            <h3 id="modal-titulo" class="fw-bold text-dark mb-2">Nombre del Producto</h3>
                            <p id="modal-descripcion" class="text-muted small mb-4">Descripción detallada...</p>

                            <div class="mb-4">
                                <span class="fs-4 fw-bold text-success" id="modal-precioBs">Bs. 0,00</span>
                                <span class="small text-muted fw-bold ms-2" id="modal-precio">($0.00)</span>
                            </div>

                            <div class="d-flex align-items-center gap-3 mb-4">
                                <span class="fw-semibold text-secondary small">Cantidad:</span>
                                <div class="d-flex align-items-center border rounded-pill px-3 py-1 bg-light" style="border-color: #FF8C00 !important;">
                                    <button type="button" class="btn btn-sm p-0 border-0 text-dark" onclick="cambiarCantidadModal(-1)">
                                        <i class="bi bi-dash fs-5" style="color: #FF8C00;"></i>
                                    </button>
                                    <span id="modal-cantidad" class="fw-bold mx-3 fs-5" style="color: #FF8C00;">1</span>
                                    <button type="button" class="btn btn-sm p-0 border-0 text-dark" onclick="cambiarCantidadModal(1)">
                                        <i class="bi bi-plus fs-5" style="color: #FF8C00;"></i>
                                    </button>
                                </div>
                            </div>

                            <button type="button" class="btn w-100 rounded-pill py-2 fw-bold shadow-sm text-white" style="background-color: #FF8C00 !important; border-color: #FF8C00 !important;" id="modal-btn-agregar">
                                <i class="bi bi-cart-plus me-2"></i> Agregar al carrito
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="carritoModal" tabindex="-1" aria-labelledby="carritoModalLabel" aria-hidden="true" style="height: 600px;">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content border-0 rounded-4 shadow-lg h-100">

                <div class="modal-header border-bottom px-4 py-3">
                    <h5 class="modal-title fw-bold" id="carritoModalLabel">
                        <i class="bi bi-cart-fill me-2 text-orange"></i>Tu Carrito de Compras
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4 bg-light modal-dialog-scrollable">
                    <div class="row g-4">

                        <div class="col-lg-8">
                            <div class="row g-3" id="contenedorCarrito">
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="card border-0 rounded-4 shadow-sm p-4 bg-white sticky-lg-top" style="top: 20px;">
                                <h5 class="fw-bold mb-2">Resumen del Pedido</h5>

                                <div class="d-flex align-items-center mb-3">
                                    <i class="bi bi-exclamation me-2"></i>
                                    <span class="text-muted small">Su costo total se calculará con el delivery.</span>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="text-dark fw-bold">Subtotal</span>
                                    <div class="text-end">
                                        <span class="fw-bold text-dark fw-bold" id="subtotalCompraBs">Bs. 0,00</span>
                                        <div class="small text-muted fw-bold" id="subtotalCompra">$0.00</div>
                                    </div>
                                </div>

                                <button class="btn btn-warning text-white btn-lg w-100 rounded-3 fw-bold shadow-sm" style="background-color: #ff8c00; border: none;" data-bs-toggle="modal" data-bs-target="#infoClienteModal">
                                    Pedir!
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <form id="f" autocomplete="off">
        <input type="hidden" id="accion" name="accion">

        <div class="modal fade" id="infoClienteModal" tabindex="-1" aria-labelledby="infoClienteModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content border-0 rounded-4 shadow-lg">

                    <div class="modal-header border-bottom px-4 py-3">
                        <h5 class="modal-title fw-bold d-flex align-items-center" id="infoClienteModalLabel">
                            <button type="button" class="btn p-0 border-0 bg-transparent text-dark me-2 text-decoration-none shadow-none" data-bs-toggle="modal" data-bs-target="#carritoModal" title="Volver al carrito">
                                <i class="bi bi-arrow-left-circle fs-4 text-orange"></i>
                            </button>
                            Procesar Compra
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body px-4 px-md-5 pt-2 pb-4">
                        <p class="small mb-4" style="opacity: 0.5;">Introduzca sus datos personales.</p>
                        <div class="row g-3"></div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nombreCli" class="form-label text-muted small fw-bold">Nombre</label>
                                <input type="text" class="form-control rounded-3" id="nombreCli" name="nombreCli" required>
                                <span id="snombreCli" class="text-danger small"></span>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="apellidoCli" class="form-label text-muted small fw-bold">Apellido</label>
                                <input type="text" class="form-control rounded-3" id="apellidoCli" name="apellidoCli" required>
                                <span id="sapellidoCli" class="text-danger small"></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="cedulaCli" class="form-label text-muted small fw-bold">Cédula</label>
                                <input type="text" class="form-control rounded-3" id="cedulaCli" name="cedulaCli" required>
                                <span id="scedulaCli" class="text-danger small"></span>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="tlfCli" class="form-label text-muted small fw-bold">Teléfono</label>
                                <input type="text" class="form-control rounded-3" id="tlfCli" name="tlfCli" required>
                                <span id="stlfCli" class="text-danger small"></span>
                            </div>

                            <hr>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <p class="small mb-2" style="opacity: 0.5;">Introduzca su dirección de delivery.</p>
                                    <label for="zonaDelivery" class="form-label text-muted small fw-bold">Zona</label>
                                    <select class="form-select rounded-3" id="zonaDelivery" name="zonaDelivery" required>
                                        <option value="">Seleccione...</option>
                                        <option value="Este">Este</option>
                                        <option value="Oeste">Oeste</option>
                                        <option value="Centro">Centro</option>
                                        <option value="Norte">Norte</option>
                                        <option value="Sur">Sur</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="calleDelivery" class="form-label text-muted small fw-bold">Calle</label>
                                    <input type="text" class="form-control rounded-3" id="calleDelivery" name="calleDelivery" required>
                                    <span id="scalleDelivery" class="text-danger small"></span>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="numCasaDelivery" class="form-label text-muted small fw-bold">Num. Casa</label>
                                    <input type="text" class="form-control rounded-3" id="numCasaDelivery" name="numCasa" required>
                                    <span id="snumCasaDelivery" class="text-danger small"></span>
                                </div>
                            </div>

                            <button class="btn btn-warning text-white btn-lg w-100 rounded-3 fw-bold shadow-sm" style="background-color: #ff8c00; border: none;" data-bs-toggle="modal" data-bs-target="#pagoModal">
                                Procesar Pago
                            </button>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="pagoModal" tabindex="-1" aria-labelledby="pagoModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content border-0 rounded-4 shadow-lg">

                    <div class="modal-header border-bottom px-4 py-3">
                        <h5 class="modal-title fw-bold d-flex align-items-center" id="pagoModalLabel">
                            <button type="button" class="btn p-0 border-0 bg-transparent text-dark me-2 text-decoration-none shadow-none" data-bs-toggle="modal" data-bs-target="#infoClienteModal" title="Volver a datos del cliente">
                                <i class="bi bi-arrow-left-circle fs-4 text-orange"></i>
                            </button>
                            Procesar Compra
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body px-4 px-md-5 pt-2 pb-4">

                        <div class="row justify-content-center mb-4">
                            <div class="col-12 col-md-8">
                                <div class="border border-success rounded-4 p-3 bg-white shadow-sm mt-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-success fw-bold fs-5">Monto Total</span>
                                        <div class="text-end">
                                            <span class="fw-bold text-success fs-5" id="montoTotalBs">Bs. 0,00</span>
                                            <div class="small text-success fw-bold" id="montoTotal">$0.00</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <p class="small mb-4" style="opacity: 0.5;">Elija su metodo de pago.</p>
                        <div class="row g-3"></div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="tipoPago" class="form-label text-muted small fw-bold">Método de pago</label>
                                <select class="form-select rounded-3" id="tipoPago" name="tipoPago" required>
                                    <option value="">Seleccione...</option>
                                    <option value="Efectivo">Efectivo</option>
                                    <option value="Pago Móvil">Pago Móvil</option>
                                    <option value="Tarjeta">Tarjeta</option>
                                </select>
                            </div>
                        </div>
                        <div id="datosPagoMovil" style="display:none;">
                            <p class="small mb-3 text-muted">Para Pago Móvil, seleccione la cuenta destino e indique los datos de la persona que realizó el pago.</p>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="cuentaDestino" class="form-label">Cuenta destino</label>
                                    <div class="input-group">
                                        <select class="form-select" id="cuentaDestino" name="cuentaDestino">
                                            <option value="">Seleccione...</option>
                                        </select>
                                        <button type="button" class="btn" style="color: #ff8c00; border: 1px solid #ff8c00;" id="btnCopiarDatos" title="Copiar datos" disabled>
                                            <i class="bi bi-copy"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="refPago" class="form-label text-muted small fw-bold">Referencia del pago (Ultimos 4 Numeros)</label>
                                    <input type="text" class="form-control rounded-3" id="refPago" name="refPago" maxlength="4">
                                    <span id="srefPago" class="text-danger small"></span>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="cedulaPago" class="form-label text-muted small fw-bold">Cédula de quien pagó</label>
                                    <input type="text" class="form-control rounded-3" id="cedulaPago" name="cedulaPago" maxlength="12">
                                    <span id="scedulaPago" class="text-danger small"></span>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="tlfPago" class="form-label text-muted small fw-bold">Teléfono de quien pagó</label>
                                    <input type="text" class="form-control rounded-3" id="tlfPago" name="tlfPago" maxlength="12">
                                    <span id="stlfPago" class="text-danger small"></span>
                                </div>
                            </div>
                        </div>
                        <div class="d-grid mt-4">
                            <button type="button" class="btn btn-crear w-100 mt-3" id="btnGuardar">Hacer Pedido!</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="modal fade" id="modalCuentaCli" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content border-0 rounded-4 shadow text-center p-3 position-relative">
                <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                <i class="bi bi-bank fs-1" style="color: #ff8c00;"></i>
                <h6 class="fw-bold text-dashboard mt-2">Datos de la Cuenta</h6>
                <div class="modal-body text-start" id="modalCuentaCliCont"> </div>
            </div>
        </div>
    </div>

    <?php require_once("comunes/modal_eliminar.php"); ?>
    <?php require_once("comunes/modal.php"); ?>

    <script src="js/scriptTasa.js"></script>
    <script src="js/catalogo.js"></script>
</body>

</html>