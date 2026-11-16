<!DOCTYPE html>
<html lang="es">
<?php require_once("comunes/encabezado.php"); ?>

<body>
    <?php require_once("comunes/sidebar.php"); ?>
    <div class="d-flex min-vh-100 position-relative bg-light">

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
                        <h2 class="text-dashboard mb-1" id="titulo_seccion"><i class="bi bi-box-seam"></i> Productos</h2>
                        <p class="text-muted mb-0" id="subtitulo_seccion">¡Gestiona los productos de tu inventario fácilmente!</p>
                    </div>
                </div>

                <!-- Botón Agregar / Botones dinámicos de navegación -->
                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-crear d-flex align-items-center gap-2 shadow-sm py-2 px-3 rounded-3" id="incluir" data-bs-toggle="modal" data-bs-target="#modal_producto">
                        <i class="bi bi-plus-circle fs-5"></i>
                        <span class="d-none d-sm-inline"> Nuevo Producto</span>
                    </button>
                    <div id="contenedor_botones_header" class="d-flex"></div>
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
                        <input type="text" id="valorBusqueda" name="valorBusqueda" class="form-control border-0 py-2 shadow-none bg-transparent" placeholder="Buscar producto...">
                    </div>
                </div>
            </div>

            <!-- Contenedor Dinámico -->
            <div id="contenedor_productos" class="mt-4">
                <div class="row g-4" id="cuadricula_items">
                </div>
            </div>

            <!-- Modal Producto -->
            <div class="modal fade" id="modal_producto" tabindex="-1" aria-labelledby="modal_producto_label" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 rounded-4 shadow-lg">

                        <div class="modal-header border-bottom-0 pb-0 px-4 pt-4">
                            <h5 class="modal-title fw-bold text-dashboard" id="modal_producto_label">Formulario de Producto</h5>
                            <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body p-4">
                            <form id="f" autocomplete="off">
                                <input type="hidden" id="codigoOriginal" name="codigoOriginal">
                                <input type="hidden" id="accion" name="accion">

                                <div class="mb-3">
                                    <label for="idCategoria" class="form-label text-muted small fw-bold">Categoría</label>
                                    <div class="input-group bg-light rounded-3">
                                        <span class="input-group-text bg-transparent border-0 text-muted"><i class="bi bi-tags"></i></span>
                                        <select class="form-select bg-transparent border-0 shadow-none ps-0" id="idCategoria" name="idCategoria" required>
                                        </select>
                                    </div>
                                    <span id="sidCategoria" class="small text-danger"></span>
                                </div>

                                <div class="mb-3">
                                    <label for="codigoProd" class="form-label text-muted small fw-bold">Código del Producto</label>
                                    <div class="input-group bg-light rounded-3">
                                        <span class="input-group-text bg-transparent border-0 text-muted"><i class="bi bi-upc-scan"></i></span>
                                        <input type="text" class="form-control bg-transparent border-0 shadow-none ps-0" id="codigoProd" name="codigoProd" placeholder="Ej. PROD01, PROD02..." required>
                                    </div>
                                    <span id="scodigoProd" class="small text-danger"></span>
                                </div>

                                <div class="mb-3">
                                    <label for="nombreProd" class="form-label text-muted small fw-bold">Nombre del Producto</label>
                                    <div class="input-group bg-light rounded-3">
                                        <span class="input-group-text bg-transparent border-0 text-muted"><i class="bi bi-fonts"></i></span>
                                        <input type="text" class="form-control bg-transparent border-0 shadow-none ps-0" id="nombreProd" name="nombreProd" placeholder="Ej. Hamburguesa, Refresco..." required>
                                    </div>
                                    <span id="snombreProd" class="small text-danger"></span>
                                </div>

                                <div class="mb-3">
                                    <label for="precioProd" class="form-label text-muted small fw-bold">Precio ($)</label>
                                    <div class="input-group bg-light rounded-3">
                                        <span class="input-group-text bg-transparent border-0 text-muted"><i class="bi bi-currency-dollar"></i></span>
                                        <input type="number" step="0.01" class="form-control bg-transparent border-0 shadow-none ps-0" id="precioProd" name="precioProd" placeholder="0.00" required>
                                    </div>
                                    <span id="sprecioProd" class="small text-danger"></span>
                                </div>

                                <div class="mb-3">
                                    <label for="descProd" class="form-label text-muted small fw-bold">Descripción</label>
                                    <textarea class="form-control bg-light border-0 shadow-none rounded-3" id="descProd" name="descProd" rows="3" placeholder="Breve descripción..." required></textarea>
                                    <span id="sdescProd" class="small text-danger"></span>
                                </div>

                                <div class="mb-4">
                                    <label for="fotoProd" class="form-label text-muted small fw-bold">URL de la Foto (Opcional)</label>
                                    <div class="input-group bg-light rounded-3">
                                        <span class="input-group-text bg-transparent border-0 text-muted"><i class="bi bi-image"></i></span>
                                        <input type="text" class="form-control bg-transparent border-0 shadow-none ps-0" id="fotoProd" name="fotoProd" placeholder="https://ejemplo.com/imagen.jpg">
                                    </div>
                                </div>

                                <div class="d-grid mt-2">
                                    <button type="button" class="btn btn-crear py-2 fw-semibold rounded-3 w-100" id="btnGuardar">
                                        Guardar Producto
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Fin de sección modal -->
        </main>
    </div>

    <!-- Llamada del archivo JavaScript -->
    <script src="js/productos.js"></script>
    <script src="js/sweetalert.js"></script>
</body>

</html>