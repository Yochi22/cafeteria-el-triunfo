<!DOCTYPE html>
<html lang="es">
<?php require_once("comunes/encabezado.php"); ?>
<body>
    <div class="d-flex">
        <?php require_once("comunes/sidebar.php"); ?>
        
        <main class="main-content w-100 p-4">
            
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
                
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
                        <h2 class="text-dashboard mb-0" id="titulo_seccion"><i class="bi bi-egg-fried"></i> Productos</h2>
                        <p class="text-muted mb-0">¡Hola, esta es la sección de Productos!</p>
                    </div>
                </div>

                <div class="d-flex flex-wrap gap-2 justify-content-md-end">
                    <button class="btn btn-crear d-flex align-items-center gap-2" onclick="nuevoProducto()" id="btn_agregar_prod">
                        <i class="bi bi-plus-circle fs-5"></i>
                        <span class="d-none d-sm-inline">Agregar Producto</span>
                    </button>
                    
                    <button class="btn btn-crear d-flex align-items-center gap-2" style="background-color: #0d6efd !important; display:none;" id="btn_regresar_cat" onclick="cargarCategorias()">
                        <i class="bi bi-arrow-left-circle fs-5"></i>
                        <span class="d-none d-sm-inline">Regresar</span>
                    </button>
                    
                    <a href="?pagina=categorias" class="btn btn-crear d-flex align-items-center gap-2" id="btn_gestionar_cat" style="background-color: #198754 !important;">
                        <i class="bi bi-tags fs-5"></i>
                        <span class="d-none d-sm-inline">Gestionar Categorías</span>
                    </a>
                </div>
            </div>
            
            <div id="contenedor_secundario" class="mt-4">
                <h5 class="text-dashboard mb-4" id="subtitulo_seccion">Categorías</h5>
                <div class="row" id="cuadricula_items">
                  
                </div>
            </div>
        </main>
    </div>
     <div class="modal fade" id="modal_producto" tabindex="-1" aria-labelledby="modal_producto_label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-dashboard" id="modal_producto_label">Nuevo Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-3">
                    <form id="f_producto">
                        <input type="hidden" id="idProducto" name="idProducto">
                        <input type="hidden" id="accion" name="accion">
                        
                        <div class="mb-3">
                            <label for="idCategoria" class="form-label text-muted small">Categoría</label>
                            <select class="form-select rounded-3" id="idCategoria_select" name="idCategoria" required></select>
                        </div>
                        <div class="mb-3">
                            <label for="nombre" class="form-label text-muted small">Nombre del producto</label>
                            <input type="text" class="form-control rounded-3" id="nombre" name="nombre" required>
                        </div>

                        <div class="mb-3">
                            <label for="precio" class="form-label text-muted small">Precio</label>
                            <input type="number" step="0.01" class="form-control rounded-3" id="precio" name="precio" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="descripcion" class="form-label text-muted small">Descripción</label>
                            <textarea class="form-control rounded-3" id="descripcion" name="descripcion" rows="3" required></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="foto" class="form-label text-muted small">URL de la foto (opcional)</label>
                            <input type="text" class="form-control rounded-3" id="foto" name="foto" placeholder="https://ejemplo.com/imagen.jpg">
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-crear py-2" style="background-color: #198754 !important;" id="btn_guardar_prod">Guardar Producto</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="js/productos.js"></script>
</body>
</html>