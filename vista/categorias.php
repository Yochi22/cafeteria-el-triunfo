<!DOCTYPE html>
<html lang="es">
<?php require_once("comunes/encabezado.php"); ?>

<body>
    <div class="d-flex min-vh-100 position-relative">
        <?php require_once("comunes/sidebar.php"); ?>

        <main class="main-content w-100 p-4">

            <!-- Encabezado principal -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
                <div class="d-flex align-items-center gap-3">
                    <!-- Menú hamburguesa -->
                    <button class="btn btn-outline-dark d-md-none border-0 p-1"
                        id="btn-toggle-sidebar"
                        data-bs-toggle="collapse"
                        data-bs-target="#sidebarMenu"
                        aria-controls="sidebarMenu"
                        aria-expanded="false"
                        aria-label="Toggle navigation">
                        <i class="bi bi-list" style="font-size: 2rem;"></i>
                    </button>

                    <!-- Título -->
                    <div>
                        <h2 class="text-dashboard mb-1"><i class="bi bi-tags-fill"></i> Categorías</h2>
                        <p class="text-muted mb-0">¡Gestiona las categorías de tus productos fácilmente!</p>
                    </div>
                </div>

                <!-- Botón Agregar -->
                <div>
                    <button class="btn btn-crear d-flex align-items-center gap-2 shadow-sm py-2 px-3 rounded-3" id="incluir" data-bs-toggle="modal" data-bs-target="#modal_categoria">
                        <i class="bi bi-plus-circle fs-5"></i>
                        <span>Nueva Categoría</span>
                    </button>
                </div>
            </div>

            <hr class="text-secondary mb-4">

            <!-- Barra de Búsqueda -->
            <div class="row mb-4">
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="input-group bg-white border rounded-3 align-items-center shadow-sm h-100 px-2 py-1">
                        <button class="btn p-0 border-0 link-secondary ms-2" type="button" id="btnBuscar">
                            <i class="bi bi-search"></i>
                        </button>
                        <input type="text" id="valorBusqueda" name="valorBusqueda" class="form-control border-0 py-2 shadow-none bg-transparent" placeholder="Buscar categoría...">
                    </div>
                </div>
            </div>

            <!-- Contenedor Dinámico de Categorías -->
            <div id="contenedor_categorias" class="mt-4">
                <div class="row g-4" id="cuadricula_categorias">
                </div>
            </div>

        </main>
    </div>

    <!-- Modal Categoría -->
    <div class="modal fade" id="modal_categoria" tabindex="-1" aria-labelledby="modal_categoria_label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow-lg">

                <div class="modal-header border-bottom-0 pb-0 px-4 pt-4">
                    <h5 class="modal-title fw-bold text-dashboard" id="modal_categoria_label">Datos de la Categoría</h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">
                    <form id="f" autocomplete="off">
                        <input type="hidden" id="codigoOriginal" name="codigoOriginal">
                        <input type="hidden" id="accion" name="accion">

                        <div class="mb-3">
                            <label for="codigoCat" class="form-label text-muted small fw-bold">Código de la Categoría</label>
                            <div class="input-group bg-light rounded-3">
                                <span class="input-group-text bg-transparent border-0 text-muted"><i class="bi bi-upc-scan"></i></span>
                                <input type="text" class="form-control bg-transparent border-0 shadow-none ps-0" id="codigoCat" name="codigoCat" placeholder="Ej. CAT01, CAT02..." required>
                            </div>
                            <span id="scodigoCat" class="small text-danger"></span>
                        </div>

                        <div class="mb-3">
                            <label for="nombreCat" class="form-label text-muted small fw-bold">Nombre de la Categoría</label>
                            <div class="input-group bg-light rounded-3">
                                <span class="input-group-text bg-transparent border-0 text-muted"><i class="bi bi-fonts"></i></span>
                                <input type="text" class="form-control bg-transparent border-0 shadow-none ps-0" id="nombreCat" name="nombreCat" placeholder="Ej. Bebidas, Postres..." required>
                            </div>
                            <span id="snombreCat" class="small text-danger"></span>
                        </div>

                        <div class="mb-3">
                            <label for="descCat" class="form-label text-muted small fw-bold">Descripción</label>
                            <textarea class="form-control bg-light border-0 shadow-none rounded-3" id="descCat" name="descCat" rows="3" placeholder="Breve descripción de los productos..." required></textarea>
                            <span id="sdescCat" class="small text-danger"></span>
                        </div>

                        <div class="mb-4">
                            <label for="fotoCat" class="form-label text-muted small fw-bold">URL de la Foto (Opcional)</label>
                            <div class="input-group bg-light rounded-3">
                                <span class="input-group-text bg-transparent border-0 text-muted"><i class="bi bi-image"></i></span>
                                <input type="text" class="form-control bg-transparent border-0 shadow-none ps-0" id="fotoCat" name="fotoCat" placeholder="https://ejemplo.com/imagen.jpg">
                            </div>
                        </div>

                        <div class="d-grid mt-2">
                            <button type="button" class="btn btn-crear py-2 fw-semibold rounded-3" id="btnGuardar">
                                <i class="bi bi-save me-2"></i> Guardar Categoría
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <!-- Archivos PHP y Scripts -->
    <?php require_once("comunes/modal_eliminar.php"); ?>
    <?php require_once("comunes/modal.php"); ?>
    <script src="js/categorias.js"></script>
</body>

</html>