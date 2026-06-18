<!DOCTYPE html>
<html lang="es">
<?php require_once("comunes/encabezado.php"); ?>

<body>
    <div class="d-flex min-vh-100 position-relative bg-light">
        <?php require_once("comunes/sidebar.php"); ?>

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
                        <h2 class="text-dashboard mb-1"><i class="bi bi-chart-bar-fill text-primary"></i> Panel de Reportes</h2>
                        <p class="text-muted mb-0">Bienvenid@ al Apartado de Reportes de la Cafetería El Triunfo</p>
                    </div>
                </div>

                <div>
                    <button class="btn btn-white border text-secondary d-flex align-items-center gap-2 shadow-sm py-2 px-3 rounded-3 bg-white">
                        <i class="bi bi-calendar-range text-primary"></i>
                        <span>Filtrar por fecha</span>
                        <i class="bi bi-chevron-down small text-muted"></i>
                    </button>
                </div>
            </div>

            <hr class="text-secondary mb-4">

            <h5 class="text-dashboard fw-bold mb-4 text-uppercase tracking-wider small text-muted">Resumen de ventas (Últimos 30 días)</h5>

            <div class="row g-4 mb-5">
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 p-3 position-relative overflow-hidden bg-white">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <small class="text-muted fw-semibold text-uppercase small">Cantidad de pedidos</small>
                                <h3 class="fw-bold mt-2 mb-1" id="stat_cantidad">1,420</h3>
                            </div>
                            <div class="p-2 bg-primary bg-opacity-10 text-primary rounded-3">
                                <i class="bi bi-bag-check-fill fs-4"></i>
                            </div>
                        </div>
                        <div class="mt-2">
                            <span class="badge bg-success-subtle text-success rounded-pill px-2 py-1 small">
                                <i class="bi bi-arrow-up-short"></i> +12.5%
                            </span>
                            <span class="text-muted small ms-1">vs mes anterior</span>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 p-3 position-relative overflow-hidden bg-white">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <small class="text-muted fw-semibold text-uppercase small">Ventas totales</small>
                                <h3 class="fw-bold mt-2 mb-1" id="stat_total">$ 3,840.50</h3>
                            </div>
                            <div class="p-2 bg-success bg-opacity-10 text-success rounded-3">
                                <i class="bi bi-cash-stack fs-4"></i>
                            </div>
                        </div>
                        <div class="mt-2">
                            <span class="badge bg-success-subtle text-success rounded-pill px-2 py-1 small">
                                <i class="bi bi-arrow-up-short"></i> +8.2%
                            </span>
                            <span class="text-muted small ms-1">vs mes anterior</span>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 p-3 position-relative overflow-hidden bg-white">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <small class="text-muted fw-semibold text-uppercase small">Ticket promedio</small>
                                <h3 class="fw-bold mt-2 mb-1" id="stat_promedio">$ 2.70</h3>
                            </div>
                            <div class="p-2 bg-warning bg-opacity-10 text-warning rounded-3">
                                <i class="bi bi-calculator-fill fs-4"></i>
                            </div>
                        </div>
                        <div class="mt-2">
                            <span class="badge bg-danger-subtle text-danger rounded-pill px-2 py-1 small">
                                <i class="bi bi-arrow-down-short"></i> -1.4%
                            </span>
                            <span class="text-muted small ms-1">vs mes anterior</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-12 col-lg-7">
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h6 class="text-dashboard fw-bold mb-1">Análisis de pedidos</h6>
                                <p class="text-muted small mb-0">Evolución de órdenes recibidas por semana</p>
                            </div>
                            <i class="bi bi-info-circle text-muted" data-bs-toggle="tooltip" title="Muestra el volumen de órdenes procesadas exitosamente."></i>
                        </div>
                        <div class="position-relative style-chart-wrapper" style="height: 300px;">
                            <canvas id="chartPedidos"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-5">
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h6 class="text-dashboard fw-bold mb-1">Cuentas</h6>
                                <p class="text-muted small mb-0">Distribución de ingresos según canal</p>
                            </div>
                            <i class="bi bi-info-circle text-muted" data-bs-toggle="tooltip" title="Porcentaje de uso de cada método de pago habilitado."></i>
                        </div>
                        <div class="position-relative style-chart-wrapper" style="height: 300px;">
                            <canvas id="chartMetodos"></canvas>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>

    <script src="js/reporte.js"></script>
</body>

</html>