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
                        <h2 class="text-dashboard mb-1"><i class="bi bi-bank"></i> Cuentas</h2>
                        <p class="text-muted mb-0">¡Bienvenido al apartado de cuentas de la Cafetería El Triunfo!</p>
                    </div>
                </div>

                <!-- Botón Agregar -->
                <div class="d-grid d-md-block">
                    <button type="button" id="incluir" class="btn btn-crear d-flex align-items-center justify-content-center gap-2 shadow-sm py-2 px-3 rounded-3" data-bs-toggle="modal" data-bs-target="#modal_cuentas">
                        <i class="bi bi-plus-circle fs-5"></i>
                        <span class="d-none d-md-inline">Agregar Cuenta</span>
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
                        <input type="text" id="valorBusqueda" name="valorBusqueda" class="form-control border-0 py-2 shadow-none bg-transparent" placeholder="Buscar cuenta...">
                    </div>
                </div>
            </div>

            <!-- Contenedor Dinámico de Cuentas -->
            <div class="table-responsive rounded-3 shadow-sm bg-white p-3">
                <table id="tblCuentas" class="table table-hover align-items-center text-center w-100">
                    <thead class="text-dashboard border-bottom">
                        <tr>
                            <th class="text-center">Banco</th>
                            <th class="text-center">Cédula Titular</th>
                            <th class="text-center">Teléfono</th>
                            <th class="text-center">Tipo de Cuenta</th>
                            <th class="text-center">N° de Cuenta</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>

            <!-- Modal Cuentas -->
            <div class="modal fade" id="modal_cuentas" tabindex="-1" aria-labelledby="modal_cuentas_label" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 rounded-4 shadow-lg">

                        <div class="modal-header border-bottom-0 pb-0 px-4 pt-4">
                            <h5 class="modal-title fw-bold text-dashboard" id="modal_cuentas_label">Formulario de Cuenta</h5>
                            <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body p-4">
                            <form id="f" autocomplete="off">
                                <input type="hidden" id="accion" name="accion">

                                <div class="mb-3">
                                    <label for="nombreBanco" class="form-label text-muted small fw-bold">Nombre del Banco</label>
                                    <div class="input-group bg-light rounded-3">
                                        <input type="text" class="form-control bg-transparent border-0 shadow-none rounded-3" id="nombreBanco" name="nombreBanco" required autocomplete="off">
                                    </div>
                                    <span id="snombreBanco" class="small text-danger"></span>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="cedulaTitular" class="form-label text-muted small fw-bold">Cédula Titular</label>
                                        <div class="input-group bg-light rounded-3">
                                            <input type="text" class="form-control bg-transparent border-0 shadow-none rounded-3" id="cedulaTitular" name="cedulaTitular" required autocomplete="off">
                                        </div>
                                        <span id="scedulaTitular" class="small text-danger"></span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="tlfCuenta" class="form-label text-muted small fw-bold">Teléfono</label>
                                        <div class="input-group bg-light rounded-3">
                                            <input type="text" class="form-control bg-transparent border-0 shadow-none rounded-3" id="tlfCuenta" name="tlfCuenta" required autocomplete="off">
                                        </div>
                                        <span id="stlfCuenta" class="small text-danger"></span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <label for="tipoCuenta" class="form-label text-muted small fw-bold">Tipo de Cuenta</label>
                                        <select class="form-select bg-light border-0 shadow-none rounded-3" name="tipoCuenta" id="tipoCuenta" required>
                                            <option value="" disabled selected>Seleccione...</option>
                                            <option value="Ahorro">Ahorro</option>
                                            <option value="Corriente">Corriente</option>
                                        </select>
                                        <span id="stipoCuenta" class="small text-danger"></span>
                                    </div>

                                    <div class="col-md-6 mb-4">
                                        <label for="numCuenta" class="form-label text-muted small fw-bold">Número de Cuenta</label>
                                        <div class="input-group bg-light rounded-3">
                                            <input type="text" class="form-control bg-transparent border-0 shadow-none rounded-3" id="numCuenta" name="numCuenta" required autocomplete="off">
                                        </div>
                                        <span id="snumCuenta" class="small text-danger"></span>
                                    </div>
                                </div>

                                <div class="d-grid mt-2">
                                    <button type="button" class="btn btn-crear py-2 fw-semibold rounded-3 w-100" id="btnGuardar">
                                        Guardar Cuenta
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
    <script src="js/cuentas.js"></script>
    <script src="js/datatables.js"></script>
    <script src="js/sweetalert.js"></script>
</body>

</html>