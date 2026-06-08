<!-- Modal de confirmacion de Eliminacion de cliente -->
            <div class="modal fade" id="modal_eliminar" tabindex="-1" aria-labelledby="modal_eliminar_label" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 rounded-4 shadow">
                        
                        <div class="modal-header border-0 pb-0 text-center">
                            <h5 class="modal-title fw-bold text-dashboard" id="modal_eliminar_label">Confirmar Eliminacion</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        
                        <div class="modal-body text-center pt-3 pb-4">
                            <div class="mb-3 text-danger">
                                <i class="bi bi-exclamation-triangle-fill" style="font-size: 3rem;"></i>
                            </div>
                            <h5 class="fw-bold mb-2">¿Está Seguro de Eliminar este Cliente?</h5>
                            <p class="text-muted small px-3">Esta Acción es Permanente.</p>
                            
                            <input type="hidden" id="cedulaEliminar">
                        </div>
                        
                        <div class="modal-footer border-0 pt-0 d-flex justify-content-center gap-2">
                            <button type="button" class="btn btn-light rounded-3 px-4 fw-bold text-muted" data-bs-dismiss="modal">No, Cancelar</button>
                            <button type="button" id="btnEliminar" class="btn text-white rounded-3 px-4 fw-bold" style="background-color: #FF8C00;">Sí, Eliminar</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- fin del modal de Eliminacion -->