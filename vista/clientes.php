<!DOCTYPE html>
<html lang="es">
    <?php require_once("comunes/encabezado.php"); ?>
<body>
    <div>
        <?php require_once("comunes/sidebar.php"); ?>

        <!-- Encabezado de bienvenida -->
        <main class="main-content">
            <h2 class="text-dashboard"><i class="bi bi-file-person"></i> Clientes</h2>
            <p class="text-muted">Bienvenido al apartado de Clientes de la Cafeteria El Triunfo!</p>

            <div class="row">
                <div class="col">
                    <hr class="border border-secundary">
                </div>
            </div>
            
            <!-- boton para registrar -->
            <div class="col-12 d-flex justify-content-end mb-3">
                <button type="button" id="incluir" class="btn text-white rounded fw-bold py-2 px-4" style="background-color: #FF8C00;" data-bs-toggle="modal" data-bs-target="#modal_cliente">
                Añadir Cliente </button>
                
                <div class="col-md-4 ms-3">
                    <div class="input-group bg-white border rounded px-3 py-2">
                        <span class="input-group-text bg-transparent border-0"><i class="bi bi-search"></i></span>
                        <input type="text" id="buscarVenta" name="buscarVenta" class="form-control border-0" placeholder="Buscar Cliente">
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
            <!--Llamada a archivo modal.php, dentro de el hay una sección modal-->
            <?php require_once("comunes/modal.php"); ?>
        </div>
        <!--Llama del java script-->
        <script src="js/clientes.js"></script>
    </div>
</body>
</html>