<!DOCTYPE html>
<html lang="es">
<?php require_once("comunes/encabezado.php"); ?>
<body>
    <div class="d-flex">
        <?php require_once("comunes/sidebar.php"); ?>
        
        <main class="main-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="text-dashboard" id="titulo_seccion"> <i class="bi bi-egg-fried"></i>Productos</h2>
                    <p class="text-muted">¡Hola, esta es la sección de Productos!</p>
                </div>
                <div class="d-flex gap-3">
                    <button class="btn btn-crear d-flex align-items-center gap-2"onclick="nuevoProducto()" id="btn_agregar_prod"> Agregar Producto
                    </button>
                    <button class="btn btn-crear d-flex align-items-center gap-2" style="background-color: #0d6efd !important; display:none;" id="btn_regresar_cat" onclick="cargarCategorias()"> Regresar
                    </button>
                    <a href="?pagina=categorias" class="btn btn-crear d-flex align-items-center gap-2" id="btn_gestionar_cat" style="background-color: #198754  !important;"> Gestionar
                        Categorías
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