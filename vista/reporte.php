<!DOCTYPE html>
<html lang="es">
<?php require_once("comunes/encabezado.php"); ?>
<body>
    <div class="d-flex">
        <?php require_once("comunes/sidebar.php"); ?>
        
        <main class="main-content">
            <div class="d-flex justify-content-between align-items-center mb-5">
                <div>
                    <h2 class="text-dashboard">Reportes</h2>
                    <p class="text-muted">Bienvenid@ al Apartado de Reportes de la Cefeteria El Triunfo</p>
                </div>
                <button class="btn btn-crear d-flex align-items-center gap-2">
                    <i class="bi bi-calendar-event fs-5"></i> Filtrar por fecha
                </button>
            </div>
            
            <h5 class="text-dashboard mb-4">Reporte de ventas</h5>
            
            <div class="row g-4 mb-5">
                <div class="col-md-4">
                    <div class="card p-3 border shadow-sm">
                        <small class="text-muted">Cantidad de pedidos <i class="bi bi-info-circle"></i></small>
                        <h4 class="mt-2" id="stat_cantidad">0 <small class="text-muted fs-6">--%</small></h4>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-3 border shadow-sm">
                        <small class="text-muted">Ventas totales <i class="bi bi-info-circle"></i></small>
                        <h4 class="mt-2" id="stat_total">US$ 0.00 <small class="text-muted fs-6">--%</small></h4>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-3 border shadow-sm">
                        <small class="text-muted">Ticket promedio <i class="bi bi-info-circle"></i></small>
                        <h4 class="mt-2" id="stat_promedio">US$ 0.00 <small class="text-muted fs-6">--%</small></h4>
                    </div>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card p-4 border shadow-sm h-100">
                        <h6 class="text-dashboard mb-4">Análisis de pedidos</h6>
                        <div style="height: 300px;">
                            <canvas id="chartPedidos"></canvas>
                        </div>
                        <p class="text-center text-muted small mt-3">Cantidad de pedidos<br>Sin datos en el periodo seleccionado</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card p-4 border shadow-sm h-100">
                        <h6 class="text-dashboard mb-4">Métodos de pago <i class="bi bi-info-circle"></i></h6>
                        <div style="height: 300px;">
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
