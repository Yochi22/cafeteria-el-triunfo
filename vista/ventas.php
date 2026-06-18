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
                    <button class="btn btn-crear d-flex align-items-center gap-2 shadow-sm py-2 px-3" id="incluir">
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
                        <span class="input-group-text bg-transparent border-0 text-warning">
                            <i class="bi bi-calendar-event-fill"></i>
                        </span>
                        <input type="date" id="buscarFecha" name="buscarFecha" class="form-control border-0 py-2 shadow-none text-muted">
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                    <h6 class="text-dashboard mb-1 text-uppercase mb-0" id="fechaVenta"><i class="bi bi-calendar3"></i> Ventas del día: 18 de Junio, 2026</h6>
                </div>
                <div class="card-body p-0 mt-3">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 text-center">
                            <thead class="table-light text-dashboard mb-1">
                                <tr>
                                    <th class="py-3">N° Orden</th>
                                    <th class="py-3">Nombre del Cliente</th>
                                    <th class="py-3">Cant. Productos</th>
                                    <th class="py-3">Monto Total</th>
                                    <th class="py-3">Cuenta</th>
                                    <th class="py-3">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="listaVentas">
                                <tr>
                                    <td class="fw-bold">#001</td>
                                    <td class="fw-semibold">Luis Alvarado</td>
                                    <td>5</td>
                                    <td class="fw-bold text-success">$20.00</td>
                                    <td class="fw-semibold">Corriente</td>
                                    <td>
                                        <button type="button" class="btn btn-outline-primary btn-sm d-inline-flex align-items-center gap-1" title="Consultar detalle">
                                            <i class="bi bi-eye-fill"></i> Consultar
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">#002</td>
                                    <td class="fw-semibold">Sofía Vergara</td>
                                    <td>2</td>
                                    <td class="fw-bold text-success">$15.50</td>
                                    <td class="fw-semibold">Ahorro</td>
                                    <td>
                                        <button type="button" class="btn btn-outline-primary btn-sm d-inline-flex align-items-center gap-1" title="Consultar detalle">
                                            <i class="bi bi-eye-fill"></i> Consultar
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">#003</td>
                                    <td class="fw-semibold">Miguel Sánchez</td>
                                    <td>1</td>
                                    <td class="fw-bold text-success">$8.00</td>
                                    <td class="fw-semibold">Corriente</td>
                                    <td>
                                        <button type="button" class="btn btn-outline-primary btn-sm d-inline-flex align-items-center gap-1" title="Consultar detalle">
                                            <i class="bi bi-eye-fill"></i> Consultar
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="js/ventas.js"></script>
</body>

</html>