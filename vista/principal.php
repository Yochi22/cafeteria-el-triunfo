<!DOCTYPE html>
<html lang="es">
<?php require_once("comunes/encabezado.php"); ?>
<body>
    <div class="d-flex">
        <?php require_once("comunes/sidebar.php"); ?>
        
        <main class="main-content">
            <div class="row mb-4">
                <div class="col-12">
                    <h2>Dashboard</h2>
                    <p class="text-muted">¡Hola, bienvenid@ de nuevo!</p>
                </div>
            </div>
            
            <div class="row mb-5">
                <div class="col-12 text-center">
                    <h5 class="text-dashboard">HOY</h5>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="metric-card">
                        <span class="number"><?= $metricas['total'] ?></span>
                        <span class="label">Total ordenes</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="metric-card">
                        <span class="number"><?= $metricas['enviadas'] ?></span>
                        <span class="label">Enviadas</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="metric-card">
                        <span class="number"><?= $metricas['canceladas'] ?></span>
                        <span class="label">Canceladas</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="metric-card">
                        <span class="number">$<?= $metricas['ganancia'] ?></span>
                        <span class="label">Ganancia</span>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>