<!DOCTYPE html>
<html lang="es">
<?php require_once("comunes/encabezado.php"); ?>

<body>
    <div class="d-flex min-vh-100 position-relative bg-light">
        <?php require_once("comunes/sidebar.php"); ?>

        <main class="main-content w-100 p-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 gap-3">
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
                        <h2 class="text-dashboard">
                            <i class="bi bi-people"></i> Usuarios
                        </h2>
                        <p class="text-muted">
                            ¡Bienvenido al apartado de usuarios de la Cafetería El Triunfo!
                        </p>
                    </div>
                </div>
                <button type="button"
                    id="incluir"
                    class="btn btn-crear"
                    data-bs-toggle="modal"
                    data-bs-target="#modal_usuarios">
                    <i class="bi bi-plus-circle fs-5"></i> Agregar Usuario
                </button>
            </div>
            <hr class="text-secondary mb-4">
            <div class="col-12 d-flex justify-content mb-3">
                <div class="col-md-4 ms-3">
                    <div class="input-group bg-white border rounded-3 align-items-center shadow-sm h-100 px-2 py-1">
                        <button class="btn p-0 border-0 link-secondary"
                            type="button"
                            id="btnBuscar">

                            <i class="bi bi-search"></i>
                        </button>
                        <input type="text"
                            id="valorBusqueda"
                            name="valorBusqueda"
                            class="form-control border-0 py-0 py-2 shadow-none"
                            placeholder="Buscar usuario...">
                    </div>
                </div>
            </div>
            <div class="table-responsive rounded-3 shadow-sm">
                <table class="table table-hover align-items-center text-center">
                    <thead class="text-dashboard border-bottom">
                        <tr>
                            <th>Cédula</th>
                            <th>Nombre y Apellido</th>
                            <th>Usuario</th>
                            <th>Rol</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="listaUsuarios" class="text-center">
                    </tbody>
                </table>
            </div>

            <!-- Modal Usuarios -->
            <div class="modal fade"
                id="modal_usuarios"
                tabindex="-1"
                aria-labelledby="modal_usuarios_label"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 rounded-4 shadow">
                        <div class="modal-header border-0 pb-0">
                            <h5 class="modal-title fw-bold text-dashboard"
                                id="modal_usuarios_label">
                                Formulario de Usuarios
                            </h5>
                            <button type="button"
                                class="btn-close"
                                data-bs-dismiss="modal"
                                aria-label="Close">
                            </button>
                        </div>
                        <div class="modal-body pt-3">
                            <form id="f">
                                <input type="hidden"
                                    id="idUsuario"
                                    name="idUsuario">
                                <div class="mb-3">
                                    <label for="idPersonal"
                                        class="form-label text-muted small">
                                        Personal
                                    </label>
                                    <select class="form-select rounded-3"
                                        id="idPersonal"
                                        name="idPersonal"
                                        required>
                                        <option value="">Seleccione el personal</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="idRol"
                                        class="form-label text-muted small">
                                        Rol
                                    </label>
                                    <select class="form-select rounded-3"
                                        id="idRol"
                                        name="idRol"
                                        required>
                                        <option value="">Seleccione un rol</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="username"
                                        class="form-label text-muted small">
                                        Nombre de usuario
                                    </label>
                                    <input type="text"
                                        class="form-control rounded-3"
                                        id="username"
                                        name="username"
                                        required>
                                    <span id="susername"></span>
                                </div>
                                <div class="mb-3">
                                    <label for="password"
                                        class="form-label text-muted small">
                                        Contraseña
                                    </label>
                                    <input type="password"
                                        class="form-control rounded-3"
                                        id="password"
                                        name="password">
                                    <small class="text-muted" id="mensajePassword">
                                        La contraseña es obligatoria al incluir un usuario.
                                        Al modificar, déjala vacía para conservar la actual.
                                    </small>
                                    <span id="spassword"></span>
                                </div>
                                <div class="d-grid mt-4">
                                    <button type="button"
                                        class="btn btn-crear w-100 mt-3"
                                        id="btnGuardar">
                                        Guardar Usuario
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <?php require_once("comunes/modal_eliminar.php"); ?>
        <?php require_once("comunes/modal.php"); ?>
        <script src="js/usuarios.js"></script>
    </div>
</body>

</html>