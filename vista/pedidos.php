<!DOCTYPE html>
<html lang="es">
<?php require_once("comunes/encabezado.php"); ?>

<body>
    <div class="d-flex min-vh-100 position-relative bg-light">
        <?php require_once("comunes/sidebar.php"); ?>

        <main class="main-content w-100 p-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 gap-3">
                <div class="d-flex align-items-center gap-3">
                    <button class="btn btn-outline-dark d-md-none border-0 p-1" id="btn-toggle-sidebar" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-expanded="false"><i class="bi bi-list" style="font-size: 2rem;"></i></button>
                    <div>
                        <h2 class="text-dashboard mb-1"><i class="bi bi-journal-check"></i> Gestión de Pedidos</h2>
                        <p class="text-muted mb-0">Revisa, registra y modifica los pedidos de tus clientes.</p>
                    </div>
                </div>
                <div>
                    <button type="button" id="incluir" class="btn btn-crear d-flex align-items-center gap-2 shadow-sm">
                        <i class="bi bi-plus-circle fs-5"></i>
                        <span>Crear Pedido</span>
                    </button>
                </div>
            </div>

            <hr class="text-secondary mb-4">

            <div class="d-flex flex-column gap-3" id="contenedor_pedidos">
            </div>
        </main>
    </div>

    <div class="modal fade" id="modal_pedido" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-dashboard">Formulario de Pedido</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-3">
                    <form id="f">
                        <input type="hidden" id="numPedido" name="numPedido">

                        <div class="row mb-4">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label class="form-label text-muted small fw-bold">Cliente</label>
                                <div class="input-group">
                                    <input type="hidden" id="idCliente" name="idCliente">
                                    <input type="text" class="form-control rounded-start-3 bg-light border-secondary-subtle" id="clienteInfo" placeholder="Seleccione un cliente..." readonly required>
                                    <button class="btn btn-primary text-white" type="button" id="btnBuscarCliente" style="background-color: #FF8C00; border-color: #FF8C00;"><i class="bi bi-search"></i> Buscar</button>
                                </div>
                            </div>
                            <div class="col-md-6 d-flex align-items-end">
                                <button class="btn btn-success w-100 py-2" type="button" id="btnBuscarProducto"><i class="bi bi-cart-plus"></i> Añadir Producto al Pedido</button>
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
                            <h4 class="text-dashboard fw-bold mb-0">Total: <span id="totalGeneral" class="text-success">$0.00</span></h4>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="button" class="btn btn-crear py-2 fw-semibold" id="btnGuardar" data-accion="incluir">
                                <i class="bi bi-check-circle"></i> Procesar Pedido
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

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

    <?php require_once("comunes/modal_eliminar.php"); ?>
    <?php require_once("comunes/modal.php"); ?>
    <script src="js/pedidos.js"></script>
</body>

</html>