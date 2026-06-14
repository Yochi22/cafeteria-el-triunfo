<!DOCTYPE html>
<html lang="es">
<?php require_once("comunes/encabezado.php"); ?>

<body>
    <div class="d-flex min-vh-100 position-relative">
        <?php require_once("comunes/sidebar.php"); ?>

        <main class="main-content w-100 p-4">

            <div class="d-flex align-items-center mb-2">

                <!--sidebar-->
                <div class="d-flex align-items-center mb-2">
                    <button class="btn btn-outline-dark d-md-none me-3 border-0"
                        id="btn-toggle-sidebar"
                        data-bs-toggle="collapse"
                        data-bs-target="#sidebarMenu"
                        aria-controls="sidebarMenu"
                        aria-expanded="false"
                        aria-label="Toggle navigation">
                        <i class="bi bi-list" style="font-size: 2rem;"></i>
                    </button>

                    <!--Encabezado del modulo-->
                    <div>
                        <h2 class="text-dashboard"><i class="bi bi-bank"></i> Métodos de Pago</h2>
                        <p class="text-muted">Bienvenido a la gestión de Métodos de Pago de la Cafeteria El Triunfo!</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <hr class="border border-secundary">
                </div>
            </div>

            <div class="col-12 d-flex justify-content-end mb-3">
                <!--botones-->

                <!--Registro-->
                <button type="button" id="incluir" class="btn btn-crear" data-bs-toggle="modal" data-bs-target="#modal_metodo">
                    <i class="bi bi-plus-circle fs-4"></i>
                    <span class="d-none d-sm-inline">Agregar Método</span>
                </button>


                <!--boton de busqueda-->
                <div class="col-md-4 ms-3">
                    <div class="input-group bg-white border rounded-3 px-2 py-2 align-items-center">
                        <button class="btn p-0 border-0 link-secondary" type="button" id="btnBuscar">
                            <i class="bi bi-search fs-5"></i>
                        </button>
                        <input type="text" id="valorBusqueda" name="valorBusqueda" class="form-control border-0 py-0 py-2 shadow-none" placeholder="Buscar Cliente">
                    </div>
                </div>
            </div>

            <!--lista -->
            <div class="table-responsive bg-gray p-5 rounded shadow-sm">
                <table class="table table-hover align-items-center text-center">
                    <thead class="text-dashboard border-bottom">
                        <tr>
                            <th>Banco</th>
                            <th>Cédula Titular</th>
                            <th>Teléfono</th>
                            <th>Tipo de Cuenta</th>
                            <th>N° de Cuenta</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="listaMetodos" class="text-center">

                    </tbody>
                </table>
            </div>
    </div>

    <div class="modal fade" id="modal_metodo" tabindex="-1" aria-labelledby="modal_metodo_label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow">

                <!--Encabezado Modal-->
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-dashboard" id="modal_metodo_label">Nuevo Método de Pago</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>


                <!--Cuerpo Modal-->
                <div class="modal-body pt-3">
                    <form id="f">
                        <input type="hidden" id="accion" name="accion">
                        <!--Campos-->
                        <div class="mb-3">
                            <label for="nombreBanco" class="form-label text-muted small fw-bold">Nombre del Banco</label>
                            <input type="text" class="form-control rounded-3" id="nombreBanco" name="nombreBanco" required>
                            <span id="snombreBanco" class="small text-danger"></span>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="cedulaTitular" class="form-label text-muted small fw-bold">Cédula Titular</label>
                                <input type="text" class="form-control rounded-3" id="cedulaTitular" name="cedulaTitular" required>
                                <span id="scedulaTitular" class="small text-danger"></span>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="tlfCuenta" class="form-label text-muted small fw-bold">Teléfono</label>
                                <input type="text" class="form-control rounded-3" id="tlfCuenta" name="tlfCuenta" required>
                                <span id="stlfCuenta" class="small text-danger"></span>
                            </div>
                        </div>

                        <div class="row">
                            
                            <div class="col-md-6 mb-3">
                                <label for="tipoCuenta" class="form-label text-muted small fw-bold">Tipo de Cuenta</label>
                                <select class="form-select" aria-label="Default select example" name="tipoCuenta" id="tipoCuenta" required>
                                    <option value="Ahorro">Ahorro</option>
                                    <option value="Corriente">Corriente</option>
                                    <span id="stipoCuenta" class="small text-danger"></span>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="numCuenta" class="form-label text-muted small fw-bold">Número de Cuenta</label>
                                <input type="text" class="form-control rounded-3" id="numCuenta" name="numCuenta" required>
                                <span id="snumCuenta" class="small text-danger"></span>
                            </div>

                        </div> 
                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-crear w-100 mt-3" style="background-color: #FF8C00;" id="btnGuardar">Guardar</button>
                            </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <!--definicion de input oculto para evitar errores al borrar-->
    <input type="hidden" id="cuentaEliminar" value="">
    <!--Fin de Modal-->

    <!-- Llamada del modal de confirmacion se eliminacion -->
    <?php require_once("comunes/modal_eliminar.php"); ?>
    <!--Llamada a archivo modal.php, dentro de el hay una sección modal-->
    <?php require_once("comunes/modal.php"); ?>

    <!--llamada a java-->
    <script src="js/metodos_pago.js"></script>

</body>

</html>