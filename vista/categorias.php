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
                        <h2 class="text-dashboard mb-0 fs-3"><i class="bi bi-tags"></i> Categorías</h2>
                        <p class="text-muted mb-0 small d-none d-sm-block">¡Gestiona las Categorías de tus Productos!</p>
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

            <div id="contenedor_categorias" class="mt-4">
                <div class="row" id="cuadricula_categorias">
                </div>
            </div>

        </main>
    </div>

    <div class="modal fade" id="modal_categoria" tabindex="-1" aria-labelledby="modal_categoria_label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow">

                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-dashboard" id="modal_categoria_label">Datos de la Categoría</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body pt-3">
                    <form id="f" autocomplete="off">
                        <input type="hidden" id="codigoOriginal" name="codigoOriginal">
                        <input type="hidden" id="accion" name="accion">

                        <div class="mb-3">
                            <label for="codigoCat" class="form-label text-muted small fw-bold">Código de la Categoría</label>
                            <input type="text" class="form-control rounded-3" id="codigoCat" name="codigoCat" placeholder="Ej. CAT01, CAT02..." required>
                            <span id="scodigoCat" class="small red"></span>
                        </div>

                        <div class="mb-3">
                            <label for="nombreCat" class="form-label text-muted small fw-bold">Nombre de la Categoría</label>
                            <input type="text" class="form-control rounded-3" id="nombreCat" name="nombreCat" placeholder="Ej. Bebidas, Postres..." required>
                            <span id="snombreCat" class="small red"></span>
                        </div>

                        <div class="mb-3">
                            <label for="descCat" class="form-label text-muted small fw-bold">Descripción</label>
                            <textarea class="form-control rounded-3" id="descCat" name="descCat" rows="3" placeholder="Breve descripción de los productos de esta categoría..." required></textarea>
                            <span id="sdescCat" class="small red"></span>
                        </div>

                        <div class="mb-3">
                            <label for="fotoCat" class="form-label text-muted small fw-bold">URL de la Foto (Opcional)</label>
                            <input type="text" class="form-control rounded-3" id="fotoCat" name="fotoCat" placeholder="https://ejemplo.com/imagen.jpg">
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
    <script src="js/categorias.js"></script>
</body>

</html>