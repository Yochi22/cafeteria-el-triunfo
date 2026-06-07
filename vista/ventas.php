<!DOCTYPE html>
<html lang="es">
<?php require_once("comunes/encabezado.php"); ?>
<body>
    <div class="d-flex">
        <?php require_once("comunes/sidebar.php"); ?>

        <main class="main-content">
            <h2 class="text-dashboard"><i class="bi bi-graph-up-arrow"></i> Ventas</h2>
            <p class="text-muted"> Hola, Esta es tu Seccion de Ventas, aqui se registran todas tus ventas!</p>

            <div class="row">
                <div class="col">
                    <hr class="border border-secundary">
                </div>
            </div>

            <div class="row mb-4 mt-4 g-3">
                <div class="col-md-4">
                    <div class="input-group bg-white border rounded px-3 py-2">
                        <span class="input-group-text bg-transparent border-0"><i class="bi bi-search"></i></span>
                        <input type="text" id="buscarVenta" name="buscarVenta" class="form-control border-0" placeholder="Buscar ventas">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="input-group bg-white border rounded px-3 py-2" style="background-color: #FF8C00 !important;">
                        <span class="input-group-text bg-transparent border-0"></span>
                        <input type="date" id="buscarFecha" name="buscarFecha" class="form-control border-0 bg-transparent text-white" placeholder="Buscar Por Fecha">
                    </div>
                </div>
            </div>

            <div class="table-responsive bg-gray p-5 rounded shadow-sm">
                <div class="row">
                    <div class="col">
                        <p class="text-dashboard" id="fechaVenta">fecha de venta</p>
                    </div>
                </div>
                <table class="table table-hover align-items-center text-center">
                    <thead class="text-dashboard border-bottom">
                        <tr>
                            <th>N° Orden</th>
                            <th>Nombre del Cliente</th>
                            <th>Cantidad de Productos</th>
                            <th>Monto de Pago</th>
                            <th>Metodo de Pago</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="listaVentas" class="text-center">
                        
                    </tbody>
                </table>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div id="paginas" class="text-muted">Pagina 1 de 5</div>
                    <div class="d-flex gap-4">
                        <button class="btn btn-link text-decoration-none text-muted"><i class="bi bi-arrow-left-circle"></i> Anterior</button>
                        <button class="btn btn-link text-decoration-none text-muted">Siguiente <i class="bi bi-arrow-right-circle"></i></button>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <hr class="border border-secundary">
                </div>
            </div>

            <div class="mt-2">
                <h4 class="text-dashboard">Total Ganancias</h4>
                <h3 class="text-dashboard" id="totalGanancias"><i class="bi bi-currency-dollar"></i> 0.00</h3>
            </div>
        </main>
    </div>

    <script src="js/ventas.js"></script>
</body>
</html>