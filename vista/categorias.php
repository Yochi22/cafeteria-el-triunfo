<!DOCTYPE html>
<html lang="es">
<?php require_once("comunes/encabezado.php"); ?>
<body>
    <div class="d-flex min-vh-100 position-relative">

        <?php require_once("comunes/sidebar.php"); ?>

        <main class="main-content w-100 p-4">

            <div class="d-flex justify-content-between align-items-center mb-4 gap-2">
                <div class="d-flex align-items-center">
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
                        <h2 class="text-dashboard mb-0"><i class="bi bi-tags"></i> Categorías</h2>
                        <p class="text-muted mb-0">Gestiona las Categorías de tus Productos</p>
                    </div>
                </div>

                <button class="btn btn-crear d-flex align-items-center gap-2" onclick="nuevo()">
                    <i class="bi bi-plus-circle fs-4"></i>
                    <span class="d-none d-sm-inline">Agregar Categoría</span>
                </button>
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
                    <h5 class="modal-title fw-bold text-dashboard" id="modal_categoria_label">Nueva Categoría</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body pt-3">
                    <form id="f" autocomplete="off">
                        <input type="hidden" id="idCategoria" name="idCategoria">
                        <input type="hidden" id="accion" name="accion">

                        <div class="mb-3">
                            <label for="nombre" class="form-label text-muted small fw-bold">Nombre de la Categoría</label>
                            <input type="text" class="form-control rounded-3" id="nombre" name="nombre" placeholder="Ej. Bebidas, Postres..." required>
                            <span id="snombre" class="small red"></span>
                        </div>

                        <div class="mb-3">
                            <label for="descripcion" class="form-label text-muted small fw-bold">Descripción</label>
                            <textarea class="form-control rounded-3" id="descripcion" name="descripcion" rows="3" placeholder="Breve descripción de los productos de esta categoría..." required></textarea>
                            <span id="sdescripcion" class="small red"></span>
                        </div>

                        <div class="mb-3">
                            <label for="foto" class="form-label text-muted small fw-bold">URL de la Foto (Opcional)</label>
                            <input type="text" class="form-control rounded-3" id="foto" name="foto" placeholder="https://ejemplo.com/imagen.jpg">
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-crear py-2" id="btn_guardar">Guardar Categoría</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <script src="js/categorias.js"></script>
</body>

</html>