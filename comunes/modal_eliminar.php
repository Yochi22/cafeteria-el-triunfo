<div class="modal fade" id="modal_eliminar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 rounded-4 shadow text-center p-3">
            <div class="modal-body">
                <input type="hidden" id="eliminar">
                <i class="bi bi-exclamation-triangle-fill text-danger display-4 mb-2 d-block"></i>
                <h5 class="fw-bold text-dashboard">¿Está Seguro de Eliminar?</h5>
                <p class="text-muted small">Esta acción no se puede deshacer.</p>
                <div class="d-flex justify-content-center gap-2 mt-3">
                    <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger rounded-pill px-3" id="btnEliminar">Confirmar</button>
                </div>
            </div>
        </div>
    </div>
</div>