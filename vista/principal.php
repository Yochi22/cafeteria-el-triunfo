<!DOCTYPE html>
<html lang="es">
<?php require_once("comunes/encabezado.php"); ?>

<body>
    <?php require_once("comunes/sidebar.php"); ?>
    <div class="d-flex min-vh-100 position-relative bg-light">

        <main class="main-content w-100 p-4">
            <div class="d-flex align-items-center mb-4">
                <button class="btn btn-outline-dark d-md-none me-3 border-0" id="btn-toggle-sidebar" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="bi bi-list" style="font-size: 2rem;"></i>
                </button>
                <div>
                    <h2 class="text-dashboard mb-0"><i class="bi bi-house-door"></i> Inicio</h2>
                    <p class="text-muted mb-0">¡Hola, bienvenid@ de nuevo al panel de administración!</p>
                </div>
            </div>

            <hr class="text-secondary mb-4">

            <div class="row g-4">
                <div class="col-12 col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                        <div class="card-body text-center d-flex flex-column justify-content-center align-items-center">
                            <i class="bi bi-clock-history fs-1 text-danger mb-3"></i>
                            <h4 class="fw-bold text-dashboard mb-2">Gestión de Horarios</h4>
                            <p class="text-muted small mb-4">Consulte y modifique los horarios de atención para cada día de la semana.</p>
                            <button type="button" class="btn btn-crear px-4 py-2" id="btnAbrirModalHorarios">
                                <i class="bi bi-calendar-week"></i> Consultar
                            </button>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                        <div class="card-body text-center d-flex flex-column justify-content-center align-items-center">
                            <i class="bi bi-currency-dollar fs-1 text-success mb-3"></i>
                            <h4 class="fw-bold text-dashboard mb-2">Gestión de Precios Delivery</h4>
                            <p class="text-muted small mb-4">Actualice las tarifas de envío por zona de forma rápida.</p>
                            <button type="button" class="btn btn-crear px-4 py-2" id="btnAbrirModalPrecios">
                                <i class="bi bi-bicycle"></i> Gestionar
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="col-12 col-md-6 offset-md-3 mt-4">
                    <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                        <div class="card-body text-center d-flex flex-column justify-content-center align-items-center">
                            <i class="bi bi-cash-coin fs-1 text-warning mb-3"></i>
                            <h4 class="fw-bold text-dashboard mb-2">Tasa del día</h4>
                            <p class="text-muted small mb-4">Consulte la tasa oficial del dólar BCV.</p>
                            <button type="button" class="btn btn-crear px-4 py-2" id="btnTasa">
                                <i class="bi bi-coin"></i> Consultar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>


    <div class="modal fade" id="modal_horarios" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content rounded-4 shadow border-0 p-3">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-dashboard">Horarios de Atención por Día</h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive rounded-3 border">
                        <table class="table table-hover text-center align-middle mb-0">
                            <thead class="table-light text-dashboard">
                                <tr>
                                    <th>Día</th>
                                    <th>Apertura</th>
                                    <th>Cierre</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody id="tablaHorariosBody"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_editar_horario" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 shadow border-0 p-3">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-dashboard">Modificar Horario (<span id="spanDiaEditar"></span>)</h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formEditarHorario" autocomplete="off">
                        <input type="hidden" id="editIdHorario">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Hora de Apertura</label>
                            <input type="time" class="form-control bg-light border-0 shadow-none" id="editHoraApertura" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Hora de Cierre</label>
                            <input type="time" class="form-control bg-light border-0 shadow-none" id="editHoraCierre" required>
                        </div>
                        <div class="d-grid mt-4">
                            <button type="button" class="btn btn-crear py-2 fw-semibold rounded-3" id="btnGuardarHorarioIndividual">Guardar Cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_precios_delivery" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 shadow border-0 p-3">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-dashboard">Modificar Precio de Delivery</h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formPreciosDelivery" autocomplete="off">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Zona de Delivery</label>
                            <select class="form-select bg-light border-0 shadow-none" id="selectZona">
                                <option value="Centro">Centro</option>
                                <option value="Este">Zona Este</option>
                                <option value="Oeste">Zona Oeste</option>
                                <option value="Norte">Zona Norte</option>
                                <option value="Sur">Zona Sur</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Nuevo Precio ($)</label>
                            <input type="number" step="0.01" min="0.01" class="form-control bg-light border-0 shadow-none" id="inputPrecioZona" required>
                            <span id="sinputPrecioZona" class="small text-danger"></span>
                        </div>
                        <div class="d-grid mt-4">
                            <button type="button" class="btn btn-crear py-2 fw-semibold rounded-3" id="btnGuardarPrecio">Actualizar Precio</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php require_once("comunes/modal_eliminar.php"); ?>
    <?php require_once("comunes/modal.php"); ?>
    <script src="js/principal.js"></script>
</body>

</html>