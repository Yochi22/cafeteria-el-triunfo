<!DOCTYPE html>
<html lang="es">
<?php require_once("comunes/encabezado.php"); ?>

<body>
    <?php require_once("comunes/sidebar.php"); ?>
    <div class="d-flex min-vh-100 position-relative bg-light">

        <main class="main-content w-100 p-4">

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
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
                        <h2 class="text-dashboard mb-1"><i class="bi bi-file-earmark-bar-graph"></i> Panel de Reportes</h2>
                    </div>
                </div>

                <div class="d-grid d-md-block">
                    <button type="button" id="btnGenerarPDF" class="btn btn-primary d-flex align-items-center justify-content-center gap-2 shadow-sm py-2 px-3 rounded-3">
                        <i class="bi bi-file-earmark-pdf-fill fs-5"></i>
                        <span class="d-none d-md-inline">Exportar Reporte PDF</span>
                    </button>
                </div>
            </div>

            <hr class="text-secondary mb-4">

            <!-- Formulario de filtro por fechas -->
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                <h5 class="text-dashboard fw-bold mb-3"><i class="bi bi-filter"></i> Filtrar Reporte por Fechas</h5>
                <form id="formFiltroFechas" class="row g-3 align-items-end">
                    <div class="col-12 col-md-4">
                        <label class="form-label small fw-semibold text-muted">Fecha Desde:</label>
                        <input type="date" class="form-control" id="fecIni" name="fecIni">
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label small fw-semibold text-muted">Fecha Hasta:</label>
                        <input type="date" class="form-control" id="fecFin" name="fecFin">
                    </div>
                    <div class="col-12 col-md-4 d-flex gap-2">
                        <button type="button" id="btnFiltrar" class="btn btn-dark w-100"><i class="bi bi-search"></i> Consultar</button>
                        <button type="button" id="btnLimpiar" class="btn btn-outline-secondary"><i class="bi bi-arrow-clockwise"></i></button>
                    </div>
                </form>
            </div>

            <!-- Tarjetas de métricas -->
            <div class="row g-4 mb-4">
                <!-- Total de ventas -->
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <small class="text-muted fw-semibold text-uppercase small">Total de ventas</small>
                                <h3 class="fw-bold mt-2 mb-1 text-success" id="totalVenBs">Bs. 0,00</h3>
                                <h5 class="fw-semibold mb-0 text-muted" id="totalVen">$ 0.00</h5>
                            </div>
                            <div class="p-3 bg-success bg-opacity-10 text-success rounded-3">
                                <i class="bi bi-cash-stack fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Promedio de ganancias -->
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <small class="text-muted fw-semibold text-uppercase small">Promedio Ganancias</small>
                                <h3 class="fw-bold mt-2 mb-1 text-primary" id="promGananciasBs">Bs. 0,00</h3>
                                <h5 class="fw-semibold mb-0 text-muted" id="promGanancias">$ 0.00</h5>
                            </div>
                            <div class="p-3 bg-primary bg-opacity-10 text-primary rounded-3">
                                <i class="bi bi-graph-up-arrow fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tasa del dólar y fecha -->
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <small class="text-muted fw-semibold text-uppercase small">Tasa del Dólar (Día)</small>
                                <h3 class="fw-bold mt-2 mb-1 text-warning" id="tasaPeriodo">Bs. 0,00</h3>
                                <small class="text-muted" id="fechaTasaPeriodo">Fecha: --/--/----</small>
                            </div>
                            <div class="p-3 bg-warning bg-opacity-10 text-warning rounded-3">
                                <i class="bi bi-coin fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pedidos finalizados -->
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <small class="text-muted fw-semibold text-uppercase small">Pedidos Finalizados</small>
                                <h3 class="fw-bold mt-2 mb-1 text-success" id="pedFinalizados">0</h3>
                            </div>
                            <div class="p-3 bg-success bg-opacity-10 text-success rounded-3">
                                <i class="bi bi-check-circle-fill fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pedidos en proceso -->
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <small class="text-muted fw-semibold text-uppercase small">Pedidos en Proceso</small>
                                <h3 class="fw-bold mt-2 mb-1 text-warning" id="pedProceso">0</h3>
                            </div>
                            <div class="p-3 bg-warning bg-opacity-10 text-warning rounded-3">
                                <i class="bi bi-clock-history fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pedidos cancelados -->
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <small class="text-muted fw-semibold text-uppercase small">Pedidos Cancelados</small>
                                <h3 class="fw-bold mt-2 mb-1 text-danger" id="pedCancelados">0</h3>
                            </div>
                            <div class="p-3 bg-danger bg-opacity-10 text-danger rounded-3">
                                <i class="bi bi-x-circle-fill fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>

    <script src="js/reporte.js"></script>

</body>

</html>