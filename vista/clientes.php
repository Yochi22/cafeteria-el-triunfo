<!DOCTYPE html>
<html lang="es">
    <?php require_once("comunes/encabezado.php"); ?>
<body>
    <div class="d-flex min-vh-100 position-relative">
        <?php require_once("comunes/sidebar.php"); ?>

        <main class="main-content w-100 p-4">
            
            <div class="d-flex align-items-center">
                
                <!-- menu hamburguesa -->
                <button class="btn btn-outline-dark d-md-none me-3 border-0"
                    id="btn-toggle-sidebar"
                    data-bs-toggle="collapse"
                    data-bs-target="#sidebarMenu"
                    aria-controls="sidebarMenu"
                    aria-expanded="false"
                    aria-label="Toggle navigation">
                    <i class="bi bi-list" style="font-size: 2rem;"></i>
                </button>

                <!-- Encabezado de bienvenida -->
                <div>
                    <h2 class="text-dashboard"><i class="bi bi-file-person"></i> Clientes</h2>
                    <p class="text-muted">Bienvenido al apartado de Clientes de la Cafeteria El Triunfo!</p>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <hr class="border border-secundary">
                </div>
            </div>
            
            
            <div class="col-12 d-flex justify-content-end mb-3">

                <!-- boton para registrar -->
                <button type="button" id="incluir" class="btn btn-crear;" data-bs-toggle="modal" data-bs-target="#modal_cliente">
                    <i class="bi bi-plus-circle fs-5"></i> Agregar Cliente 
                </button>

                <!-- boton de busqueda -->    
                <div class="col-md-4 ms-3">
                    <div class="input-group bg-white border rounded-3 px-2 py-2 align-items-center">
                        <button class="btn p-0 border-0 link-secondary" type="button" id="btnBuscar">
                            <i class="bi bi-search fs-5"></i>
                        </button>
                        <input type="text" id="valorBusqueda" name="valorBusqueda" class="form-control border-0 py-0 py-2 shadow-none" placeholder="Buscar Cliente">
                    </div>
                </div>

            </div>

            <!-- lista de Clientes -->
            <table class="table table-hover align-items-center text-center">
                <thead class="text-dashboard border-bottom">
                    <tr>
                        <th>Cédula</th>
                        <th>Nombre y Apellido</th>
                        <th>Teléfono</th>
                        <th>Dirección</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="listaClientes" class="text-center">
                    
                </tbody>
            </table>

            <!-- seccion del modal -->
            <div class="modal fade" id="modal_cliente" tabindex="-1" aria-labelledby="modal_cliente_label" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 rounded-4 shadow">
                        
                        <!-- Encabezado del modal -->
                        <div class="modal-header border-0 pb-0">
                            <h5 class="modal-title fw-bold text-dashboard" id="modal_cliente_label">Formulario de Cliente</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        
                        <!-- Cuerpo del modal -->
                        <div class="modal-body pt-3">
                            <form id="f">
                                <input type="hidden" id="accion" name="accion">
                                <!-- DATOS DEL CLIENTE -->
                                <div class="mb-3">
                                    <label for="cedulaCliente" class="form-label text-muted small">Cédula</label>
                                    <input type="text" class="form-control rounded-3" id="cedulaCliente" name="cedulaCliente" required>
                                    <span id="scedulaCliente"></span>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="nombreCliente" class="form-label text-muted small">Nombre</label>
                                    <input type="text" class="form-control rounded-3" id="nombreCliente" name="nombreCliente" required>
                                    <span id="snombreCliente"></span>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="apellidoCliente" class="form-label text-muted small">Apellido</label>
                                    <input type="text" class="form-control rounded-3" id="apellidoCliente" name="apellidoCliente" required>
                                    <span id="sapellidoCliente"></span>
                                </div>

                                <div class="mb-3">
                                    <label for="tlfCliente" class="form-label text-muted small">Teléfono</label>
                                    <input type="text" class="form-control rounded-3" id="tlfCliente" name="tlfCliente" required>
                                    <span id="stlfCliente"></span>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="dirCliente" class="form-label text-muted small">Dirección</label>
                                    <input type="text" class="form-control rounded-3" id="dirCliente" name="dirCliente" required>
                                    <span id="sdirCliente"></span>
                                </div>
                                
                                <div class="d-grid mt-4">
                                    <button type="button" class="btn btn-crear w-100 mt-3" id="btnGuardar">Guardar Cliente</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!--fin de seccion modal-->

            <!-- Llamada del modal de confirmacion se eliminacion -->
            <?php require_once("comunes/modal_eliminar.php"); ?>
            <!--Llamada a archivo modal.php, dentro de el hay una sección modal-->
            <?php require_once("comunes/modal.php"); ?>
        </div>
        <!--Llama del java script-->
        <script src="js/clientes.js"></script>
    </div>
</body>
</html>