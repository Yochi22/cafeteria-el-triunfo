<!DOCTYPE html>
<html lang="es">
<?php require_once("comunes/encabezado.php"); ?>
<body>
    <div class="d-flex min-vh-100 position-relative">
        <?php require_once("comunes/sidebar.php"); ?>

        <main class="main-content w-100 p-4">

            <div class="row align-items-center mb-4 g-3">
                <div class="col-9 col-md-5 d-flex align-items-center">
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
                        <h2 class="text-dashboard"><i class="bi bi-graph-up-arrow"></i> Ventas</h2>
                        <p class="text-muted"> Hola, Esta es tu Seccion de Ventas, aqui se registran todas tus ventas!</p>
                    </div>
                </div>
                <div class="col-3 col-md-7">
                    <div class="row g-2 justify-content-md-end align-items-center flex-nowrap">
                        <div class="col-auto">
                            <button class="btn btn-crear d-flex align-items-center gap-2 py-2" id="incluir">
                                <i class="bi bi-plus-circle fs-4"></i>
                                <span class="d-none d-sm-inline"> Añadir Venta</span>
                            </button>
                        </div>
                    </div>
                </div>
                
            </div>

            <div class="row">
                <div class="col">
                    <hr class="border border-secundary">
                </div>
            </div>

            <div class="row mb-4 mt-4 g-3">
                <div class="col-6 col-md-4">
                    <div class="input-group bg-white border rounded px-3 py-2">
                        <span class="input-group-text bg-transparent border-0"><i class="bi bi-search"></i></span>
                        <input type="text" id="buscarVenta" name="buscarVenta" class="form-control border-0" placeholder="Buscar ventas">
                    </div>
                </div>

                <div class="col-5 col-md-3">
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
                        <tr>
                            <td> 001 </td>
                            <td> luis alvarado </td>
                            <td> 5 </td>
                            <td> 20 </td>
                            <td> pago movil </td>
                            <td> <button type="button" class="btn text-white w-80 small-width m-1" style="background-color: #FF8C00";><i class="bi bi-pencil-square"></i> consultar</button></td>
                        </tr>
                    </tbody>
                </table>
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