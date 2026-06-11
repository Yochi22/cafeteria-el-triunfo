<!DOCTYPE html>
<html lang="es">
    <?php require_once("comunes/encabezado.php"); ?>
<body>
    <div class="d-flex min-vh-100 position-relative">
        <?php require_once("comunes/sidebar.php"); ?>

        <main class="main-content w-100 p-4">
            
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
                    <h2 class="text-dashboard"><i class="bi bi-file-person"></i> Personal</h2>
                    <p class="text-muted">Bienvenido al apartado de Personal de la Cafeteria El Triunfo!</p>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <hr class="border border-secundary">
                </div>
            </div>
            
            
            <div class="col-12 d-flex justify-content-end mb-3">

                <button type="button" id="incluir" class="btn btn-crear" data-bs-toggle="modal" data-bs-target="#modal_personal">
                    <i class="bi bi-plus-circle fs-5"></i> Agregar Personal 
                </button>

                <div class="col-md-4 ms-3">
                    <div class="input-group bg-white border rounded-3 px-2 py-2 align-items-center">
                        <button class="btn p-0 border-0 link-secondary" type="button" id="btnBuscar">
                            <i class="bi bi-search fs-5"></i>
                        </button>
                        <input type="text" id="valorBusqueda" name="valorBusqueda" class="form-control border-0 py-0 py-2 shadow-none" placeholder="Buscar Personal">
                    </div>
                </div>

            </div>

<<<<<<< HEAD
            <table class="table table-hover align-items-center text-center">
                <thead class="text-dashboard border-bottom">
                    <tr>
                        <th>Cédula</th>
                        <th>Nombre y Apellido</th>
                        <th>Contraseña</th>
                        <th>Rol</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="listaPersonal" class="text-center">
                    
                </tbody>
            </table>
=======
            <div class="table-responsive">
                <table class="table table-hover align-items-center text-center table-sm">
                    <thead class="text-dashboard border-bottom">
                        <tr>
                            <th>Cédula</th>
                            <th>Nombre y Apellido</th>
                            <th>Contraseña</th>
                            <th>Rol</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="listaPersonal" class="text-center">
                        
                    </tbody>
                </table>
            </div>

>>>>>>> 9e8588b3a2072d053e5c8a31e02f263071312e4b

            <div class="modal fade" id="modal_personal" tabindex="-1" aria-labelledby="modal_personal_label" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 rounded-4 shadow">
                        
                        <div class="modal-header border-0 pb-0">
                            <h5 class="modal-title fw-bold text-dashboard" id="modal_personal_label">Formulario de Personal</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        
                        <div class="modal-body pt-3">
                            <form id="f">
                                <input type="hidden" id="accion" name="accion">
                                <div class="mb-3">
                                    <label for="cedulaPer" class="form-label text-muted small">Cédula</label>
                                    <input type="text" class="form-control rounded-3" id="cedulaPer" name="cedulaPer" required>
                                    <span id="scedulaPer"></span>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="nombre" class="form-label text-muted small">Nombre</label>
                                    <input type="text" class="form-control rounded-3" id="nombre" name="nombre" required>
                                    <span id="snombre"></span>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="apellido" class="form-label text-muted small">Apellido</label>
                                    <input type="text" class="form-control rounded-3" id="apellido" name="apellido" required>
                                    <span id="sapellido"></span>
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label text-muted small">Contraseña</label>
                                    <input type="password" class="form-control rounded-3" id="password" name="password" required>
                                    <span id="spassword"></span>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="rol" class="form-label text-muted small">Rol</label>
                                    <input type="text" class="form-control rounded-3" id="rol" name="rol" required>
                                    <span id="srol"></span>
                                </div>
                                
                                <div class="d-grid mt-4">
                                    <button type="button" class="btn btn-crear w-100 mt-3" id="btnGuardar">Guardar Personal</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php require_once("comunes/modal_eliminar.php"); ?>
            <?php require_once("comunes/modal.php"); ?>
        </main>
        <script src="js/personal.js"></script>
    </div>
</body>
</html>