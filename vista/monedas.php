<!DOCTYPE html>
<html lang="es">
<?php require_once("comunes/encabezado.php"); ?>

<body>
    <div class="d-flex min-vh-100 position-relative">

        <?php require_once("comunes/sidebar.php"); ?>

        <main class="main-content w-100 p-4">

            <div class="row align-items-center mb-4 g-3">

                <div class="col-12 col-md-5 d-flex align-items-center">
                    <button class="btn btn-outline-dark d-md-none me-2 border-0 p-1"
                        id="btn-toggle-sidebar"
                        data-bs-toggle="collapse"
                        data-bs-target="#sidebarMenu"
                        aria-controls="sidebarMenu"
                        aria-expanded="false"
                        aria-label="Toggle navigation">
                        <i class="bi bi-list" style="font-size: 1.8rem;"></i>
                    </button>
                    <div>
                        <h2 class="text-dashboard mb-0 fs-3"><i class="bi bi-tags"></i> Monedas</h2>
                        <p class="text-muted mb-0 small d-none d-sm-block">¡Gestiona las Monedas de tus Productos!</p>
                    </div>
                </div>

                <div class="col-12 col-md-7">
                    <div class="row g-2 justify-content-md-end align-items-center flex-nowrap">

                        <div class="col">
                            <div class="input-group bg-white border rounded-3 px-2 py-1 align-items-center">
                                <button class="btn p-0 border-0 link-secondary" type="button" id="btnBuscar">
                                    <i class="bi bi-search fs-5"></i>
                                </button>
                                <input type="text" id="valorBusqueda" name="valorBusqueda" class="form-control border-0 py-0 py-2 shadow-none" placeholder="Buscar...">
                            </div>
                        </div>

                        <div class="col-auto">
                            <button class="btn btn-crear d-flex align-items-center gap-2 py-2" id="incluir">
                                <i class="bi bi-plus-circle fs-4"></i>
                                <span class="d-none d-sm-inline">Agregar</span>
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

            <div id="contenedor_monedas" class="mt-4">
                <div class="row" id="cuadricula_monedas">
                </div>
            </div>

        </main>
    </div>

    <div class="modal fade" id="modal_moneda" tabindex="-1" aria-labelledby="modal_moneda_label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow">

                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-dashboard" id="modal_moneda_label">Datos de la Moneda</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body pt-3">
                    <form id="f" autocomplete="off">
                        <input type="hidden" id="codigoOriginal" name="codigoOriginal">
                        <input type="hidden" id="accion" name="accion">

                        <div class="mb-3">
                            <label for="codigoMon" class="form-label text-muted small fw-bold">Código de la Moneda</label>
                            <input type="text" class="form-control rounded-3" id="codigoMon" name="codigoMon" placeholder="Ej. MON01, MON02..." required>
                            <span id="scodigoMon" class="small red"></span>
                        </div>

                        <div class="mb-3">
                            <label for="nombreMon" class="form-label text-muted small fw-bold">Nombre de la Moneda</label>
                            <input type="text" class="form-control rounded-3" id="nombreMon" name="nombreMon" placeholder="Ej. Dólares, Euros..." required>
                            <span id="snombreMon" class="small red"></span>
                        </div>

                        <div class="mb-3">
                            <label for="simboloMon" class="form-label text-muted small fw-bold">Símbolo</label>
                            <textarea class="form-control rounded-3" id="simboloMon" name="simboloMon" rows="3" placeholder="Símbolo de la moneda..." required></textarea>
                            <span id="ssimboloMon" class="small red"></span>
                        </div>

                        <div class="mb-3">
                            <label for="tasaMon" class="form-label text-muted small fw-bold">Tasa del Día</label>
                            <input type="text" class="form-control rounded-3" id="tasaMon" name="tasaMon" placeholder="Tasa diaria...">
                        </div>

                        <div class="d-grid mt-4">
                            <button type="button" class="btn btn-crear py-2" id="btnGuardar">Incluir</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <?php require_once("comunes/modal_eliminar.php"); ?>
    <?php require_once("comunes/modal.php"); ?>
    </div>
    <script src="js/monedas.js"></script>
    </div>
</body>

</html>