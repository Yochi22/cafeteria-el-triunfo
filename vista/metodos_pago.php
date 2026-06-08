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
                        <h2 class="text-dashboard mb-0"><i class="bi bi-bank"></i> Datos Bancarios</h2>
                        <p class="text-muted mb-0">Gestiona las cuentas y métodos de pago</p>
                    </div>
                </div>

                <button class="btn d-flex align-items-center gap-2 text-white" style="background-color: #FF8C00;" onclick="nuevo()">
                    <i class="bi bi-plus-circle fs-4"></i>
                    <span class="d-none d-sm-inline">Agregar Método</span>
                </button>
            </div>

            <div class="table-responsive mt-4">
                <table class="table table-hover align-middle bg-white shadow-sm rounded-4 overflow-hidden">
                    <thead class="bg-light">
                        <tr>
                            <th style="display:none;">ID</th>
                            <th class="py-3 px-4">Banco</th>
                            <th class="py-3 px-4">Cédula Titular</th>
                            <th class="py-3 px-4">Teléfono</th>
                            <th class="py-3 px-4">N° de Cuenta</th>
                            <th class="py-3 px-4 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tabla_metodos">
                        </tbody>
                </table>
            </div>

        </main>
    </div>

    <div class="modal fade" id="modal_metodo" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow">

                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-dashboard" id="modal_metodo_label">Nuevo Método de Pago</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body pt-3">
                    <form id="f" autocomplete="off">
                        <input type="hidden" id="idMetodo" name="idMetodo">
                        <input type="hidden" id="accion" name="accion">

                        <div class="mb-3">
                            <label for="nombreBanco" class="form-label text-muted small fw-bold">Nombre del Banco</label>
                            <input type="text" class="form-control rounded-3" id="nombreBanco" name="nombreBanco" placeholder="Ej. Banesco, Mercantil..." required>
                            <span id="snombreBanco" class="small text-danger"></span>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="cedulaTitular" class="form-label text-muted small fw-bold">Cédula Titular</label>
                                <input type="text" class="form-control rounded-3" id="cedulaTitular" name="cedulaTitular" placeholder="V-12345678" required>
                                <span id="scedulaTitular" class="small text-danger"></span>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="telefono" class="form-label text-muted small fw-bold">Teléfono</label>
                                <input type="text" class="form-control rounded-3" id="telefono" name="telefono" placeholder="04141234567" required>
                                <span id="stelefono" class="small text-danger"></span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="cuenta" class="form-label text-muted small fw-bold">Número de Cuenta</label>
                            <input type="text" class="form-control rounded-3" id="cuenta" name="cuenta" placeholder="20 dígitos" required>
                            <span id="scuenta" class="small text-danger"></span>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn text-white py-2" style="background-color: #FF8C00;" id="btn_guardar">Guardar</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <script src="js/metodos_pago.js"></script>
</body>

</html>