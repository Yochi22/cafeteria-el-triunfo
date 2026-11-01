<!DOCTYPE html>
<html lang="es">
<?php require_once("comunes/encabezado.php"); ?>

<body>
    <div class="d-flex min-vh-100 position-relative bg-light">
        <?php require_once("comunes/sidebar.php"); ?>

        <main class="main-content w-100 p-4">

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 gap-3">
                <div class="d-flex align-items-center gap-3">
                    <button class="btn btn-outline-dark d-md-none border-0 p-1"
                        id="btn-toggle-sidebar"
                        data-bs-toggle="collapse"
                        data-bs-target="#sidebarMenu"
                        aria-controls="sidebarMenu"
                        aria-expanded="false"
                        aria-label="Toggle navigation">
                        <i class="bi bi-list" style="font-size: 2rem;"></i>
                    </button>

                    <div>
                        <h2 class="text-dashboard mb-1"><i class="bi bi-graph-up-arrow"></i> Historial de Ventas</h2>
                        <p class="text-muted mb-0">Revisa y gestiona todas las ventas concretadas de tu negocio.</p>
                    </div>
                </div>

                <div>
                    <button class="btn btn-crear d-flex align-items-center gap-2 shadow-sm py-2 px-3" id="incluir" data-bs-toggle="modal" data-bs-target="#modal_venta">
                        <i class="bi bi-plus-circle fs-5"></i>
                        <span>Añadir Venta</span>
                    </button>
                </div>
            </div>

            <hr class="text-secondary mb-4">

            <div class="row mb-4 g-3">
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="input-group bg-white border rounded-3 align-items-center shadow-sm h-100">
                        <span class="input-group-text bg-transparent border-0 text-muted">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" id="buscarVenta" name="buscarVenta" class="form-control border-0 py-2 shadow-none" placeholder="Buscar por cliente o N° de orden...">
                    </div>
                </div>

                <div class="col-12 col-md-4 col-lg-3">
                    <div class="input-group bg-white border rounded-3 align-items-center shadow-sm h-100">

                        <input type="date" id="buscarFecha" name="buscarFecha" class="form-control border-0 py-2 shadow-none text-muted">
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                    <h6 class="text-dashboard mb-1 text-uppercase mb-0" id="fechaAcutal"> </h6>
                </div>
                <div class="card-body p-0 mt-3">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 text-center">
                            <thead class="table-light text-dashboard mb-1">
                                <tr>
                                    <th class="py-3">N° Orden</th>
                                    <th class="py-3">Nombre del Personal</th>
                                    <th class="py-3">Monto Total</th>
                                    <th class="py-3">Tipo de Pago</th>
                                    <th class="py-3">Detalles de Venta</th>
                                    <th class="py-3">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="listaVentas">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <form id="form_venta_completa">
                <input type="hidden" id="accion" name="accion" value="incluir">
                <input type="hidden" id="idVenta" name="idVenta">
                <input type="hidden" id="idDetalle" name="idDetalle">
                <input type="hidden" id="idPedido" name="idPedido">

                <!-- Datos de Venta -->
                <div class="modal fade" id="modal_venta" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 rounded-4 shadow">
                            <div class="modal-header border-0 pb-0">
                                <h5 class="modal-title fw-bold text-dashboard">Formulario de Ventas</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body pt-3">
                                <div class="col mb-3">
                                    <label class="form-label text-muted small fw-bold">Personal</label>
                                    <div class="input-group">
                                        <input type="hidden" id="idpersonal" name="idpersonal">
                                        <input type="text" class="form-control rounded-start-3 border-secondary-subtle" id="personalInfo" placeholder="Seleccione un Personal..." readonly>
                                        <button class="btn btn-primary text-white" type="button" id="listarpersonal" style="background-color: #FF8C00; border-color: #FF8C00;"><i class="bi bi-search"></i> Buscar</button>
                                    </div>
                                </div>

                                <div class="d-grid mt-4">
                                    <button type="button" class="btn btn-crear w-100 mt-3" id="btnSiguienteDetalles">
                                        Procesar Detalles De la Venta <i class="bi bi-arrow-right-square"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Datos del Pedido  -->
                <div class="modal fade" id="modal_detallesdepedido" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content border-0 rounded-4 shadow">
                            <div class="modal-header border-0 pb-0">
                                <h5 class="modal-title fw-bold text-dashboard">Formulario de Detalles de Venta</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <h5 class="fw-bold text-dashboard text-center mb-3">Datos del Pedido</h5>
                                <div class="row mb-4">
                                    <div class="col-md-6 mb-3 mb-md-0">
                                        <label class="form-label text-muted small fw-bold">Cliente</label>
                                        <div class="input-group">
                                            <input type="hidden" id="idCliente" name="idCliente">
                                            <input type="text" class="form-control rounded-start-3 border-secondary-subtle" id="clienteInfo" placeholder="Seleccione un cliente..." readonly>
                                            <button class="btn btn-primary text-white" type="button" id="listarcliente" style="background-color: #FF8C00; border-color: #FF8C00;"><i class="bi bi-search"></i> Buscar</button>
                                        </div>
                                    </div>
                                    <div class="col-md-6 d-flex align-items-end">
                                        <button class="btn btn-success w-100 py-2" type="button" id="listarproducto"><i class="bi bi-cart-plus"></i> Añadir Producto</button>
                                    </div>
                                </div>

                                <div class="table-responsive border rounded-3 mb-3 shadow-sm">
                                    <table class="table table-hover mb-0 text-center align-middle">
                                        <thead class="table-light text-dashboard">
                                            <tr>
                                                <th>Código</th>
                                                <th>Producto</th>
                                                <th>Precio</th>
                                                <th>Cantidad</th>
                                                <th>SubTotal</th>
                                                <th>Quitar</th>
                                            </tr>
                                        </thead>
                                        <tbody id="detallesdeventa">
                                        </tbody>
                                    </table>
                                </div>

                                <div class="d-flex justify-content-end mb-3">
                                    <h4 class="text-dashboard fw-bold mb-0">Total: <span id="totalPrecio" class="text-success">$0.00</span></h4>
                                </div>

                                <div class="d-grid mt-4">
                                    <button type="button" class="btn btn-crear py-2 fw-semibold" id="btnSiguientePago">
                                        <i class="bi bi-check-circle"></i> Procesar Detalles del Pago
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Datos Del Pago -->
                <div class="modal fade" id="modal_detallesdepago" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 rounded-4 shadow">
                            <div class="modal-header border-0 pb-0">
                                <h5 class="modal-title fw-bold text-dashboard">Formulario de Datos del Pago</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body pt-3">
                                <h5 class="fw-bold text-dashboard text-center mb-3">Datos del Pago</h5>

                                <div class="mb-3">
                                    <label class="form-label text-muted small fw-bold">Cuenta Bancaria</label>
                                    <div class="input-group">
                                        <input type="hidden" id="idcuenta" name="idCuenta">
                                        <input type="text" class="form-control rounded-start-3 border-secondary-subtle" id="cuentasInfo" placeholder="Seleccione una cuenta..." readonly>
                                        <button class="btn btn-primary text-white" type="button" id="listarcuentas" style="background-color: #FF8C00; border-color: #FF8C00;"><i class="bi bi-search"></i> Buscar</button>
                                    </div>
                                    <small id="scuentas" class="text-danger fw-semibold"></small>
                                </div>

                                <div class="mb-3">
                                    <label for="tipoPago" class="form-label text-muted small fw-bold">Tipo de Pago</label>
                                    <select class="form-select rounded-3" id="tipoPago" name="tipoPago" required>
                                        <option value="">Seleccione...</option>
                                        <option value="Pago Movil">Pago Móvil</option>
                                        <option value="Transferencia">Transferencia</option>
                                    </select>
                                    <small id="stipoPago" class="text-danger fw-semibold"></small>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="cedulaTitular" class="form-label text-muted small fw-bold">Cédula Titular</label>
                                        <input type="text" class="form-control rounded-3" id="cedulaTitular" name="cedulaTitular" required>
                                        <small id="scedulaTitular" class="text-danger fw-semibold"></small>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="telefonoTitular" class="form-label text-muted small fw-bold">Teléfono Titular</label>
                                        <input type="text" class="form-control rounded-3" id="telefonoTitular" name="telefonoTitular" required>
                                        <small id="stelefonoTitular" class="text-danger fw-semibold"></small>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="referenciaBancaria" class="form-label text-muted small fw-bold">Referencia Bancaria</label>
                                        <input type="text" class="form-control rounded-3" id="referenciaBancaria" name="referenciaBancaria" required>
                                        <small id="sreferenciaBancaria" class="text-danger fw-semibold"></small>
                                    </div>
                                </div>

                                <div class="d-grid mt-4">
                                    <button type="button" class="btn btn-crear w-100 mt-3" id="btnFinalizarVenta">
                                        Finalizar Venta <i class="bi bi-check2-all"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </form>

            <!-- LISTADOS DE SELECCIÓN -->

            <!-- Listado de Personal -->
            <div class="modal fade" id="modal_personal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content rounded-4 shadow">
                        <div class="modal-header border-0 pb-0">
                            <h5 class="modal-title fw-bold text-dashboard">Listado de Personal</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="table-responsive rounded-3 border">
                                <table class="table table-hover text-center align-middle mb-0">
                                    <thead class="table-light text-dashboard">
                                        <tr>
                                            <th>Cédula</th>
                                            <th>Nombre</th>
                                            <th>Apellido</th>
                                        </tr>
                                    </thead>
                                    <tbody id="listaPersonalModal"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Listado de Clientes -->
            <div class="modal fade" id="modal_clientes" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content rounded-4 shadow">
                        <div class="modal-header border-0 pb-0">
                            <h5 class="modal-title fw-bold text-dashboard">Listado de Clientes</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="table-responsive rounded-3 border">
                                <table class="table table-hover text-center align-middle mb-0">
                                    <thead class="table-light text-dashboard">
                                        <tr>
                                            <th>Cédula</th>
                                            <th>Nombre y Apellido</th>
                                            <th>Teléfono</th>
                                        </tr>
                                    </thead>
                                    <tbody id="listaClientesModal"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Listado de Productos -->
            <div class="modal fade" id="modal_productos" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content rounded-4 shadow">
                        <div class="modal-header border-0 pb-0">
                            <h5 class="modal-title fw-bold text-dashboard">Catálogo de Productos</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="table-responsive rounded-3 border">
                                <table class="table table-hover text-center align-middle mb-0">
                                    <thead class="table-light text-dashboard">
                                        <tr>
                                            <th>Código</th>
                                            <th>Producto</th>
                                            <th>Precio</th>
                                        </tr>
                                    </thead>
                                    <tbody id="listaProductosModal"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Listado de Cuentas -->
            <div class="modal fade" id="modal_cuentas" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content rounded-4 shadow">
                        <div class="modal-header border-0 pb-0">
                            <h5 class="modal-title fw-bold text-dashboard">Listado de Cuentas</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="table-responsive rounded-3 border">
                                <table class="table table-hover text-center align-middle mb-0">
                                    <thead class="table-light text-dashboard">
                                        <tr>
                                            <th>Banco</th>
                                            <th>Numero de Cuenta</th>
                                            <th>Cedula Del Titular</th>
                                            <th>Telefono Del Titular</th>
                                            <th>Tipo de Cuenta</th>
                                        </tr>
                                    </thead>
                                    <tbody id="listaCuentasModal"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Ver Detalle -->
            <div class="modal fade" id="modal_ver_detalle" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 rounded-4 shadow p-3">
                        <div class="modal-header border-0 pb-0">
                            <h5 class="modal-title fw-bold text-dashboard"><i class="bi bi-receipt"></i> Detalle de la Venta</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" id="contenidoDetalleVenta">
                        </div>
                    </div>
                </div>
            </div>

        </main>
        <?php require_once("comunes/modal_eliminar.php"); ?>
        <?php require_once("comunes/modal.php"); ?>
    </div>
    <script src="js/ventas.js"></script>
</body>

</html>