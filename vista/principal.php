<!DOCTYPE html>
<html lang="es">
<?php require_once("comunes/encabezado.php"); ?>

<body>
    <div class="d-flex min-vh-100 position-relative">

        <?php require_once("comunes/sidebar.php"); ?>

        <main class="main-content w-100 p-4">

            <div class="d-flex align-items-center mb-4">
                <button class="btn btn-outline-dark d-md-none me-3 border-0"
                    id="btn-toggle-sidebar"
                    data-bs-toggle="collapse"
                    data-bs-target="#sidebarMenu"
                    aria-controls="sidebarMenu"
                    aria-expanded="false"
                    aria-label="Toggle navigation">
                    <i class="bi bi-list" style="font-size: 2rem;"></i>
                </button>
                <div>
                    <h2 class="text-dashboard mb-0"><i class="bi bi-house-door"></i> Dashboard</h2>
                    <p class="text-muted mb-0">¡Hola, bienvenid@ de nuevo!</p>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-12 text-center">
                    <h5 class="text-dashboard fw-bold tracking-wide">HOY</h5>
                    <div style="width: 40px; height: 3px; background-color: var(--card-brown); margin: 8px auto 0; border-radius: 2px;"></div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="metric-card shadow-sm transition-hover">
                        <span class="number"><?= $metricas['total'] ?? 0 ?></span>
                        <span class="label"><i class="bi bi-journal-text me-1"></i> Total Órdenes</span>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="metric-card shadow-sm transition-hover">
                        <span class="number"><?= $metricas['enviadas'] ?? 0 ?></span>
                        <span class="label"><i class="bi bi-truck me-1"></i> Enviadas</span>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="metric-card shadow-sm transition-hover">
                        <span class="number"><?= $metricas['canceladas'] ?? 0 ?></span>
                        <span class="label"><i class="bi bi-x-circle me-1"></i> Canceladas</span>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="metric-card shadow-sm transition-hover">
                        <span class="number">$<?= number_format($metricas['ganancias'] ?? 0, 2) ?></span>
                        <span class="label"><i class="bi bi-cash-coin me-1"></i> Ganancia</span>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>

</html>