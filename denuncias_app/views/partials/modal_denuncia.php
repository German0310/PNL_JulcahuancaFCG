
<div class="modal fade" id="denunciaModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalTitle">
                    <i class="bi bi-plus-circle"></i> Nueva Denuncia
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="denunciaForm" method="POST">
                <input type="hidden" id="denunciaId" name="id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="titulo" class="form-label fw-semibold">Título *</label>
                                <input type="text" class="form-control" id="titulo" name="titulo" required maxlength="100"
                                       placeholder="Ingrese un título descriptivo">
                                <div class="invalid-feedback">Por favor ingrese un título para la denuncia.</div>
                                <div class="form-text">
                                    <span class="char-count" id="contadorTitulo">0</span>/100 caracteres
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="ciudadano" class="form-label fw-semibold">Ciudadano *</label>
                                <input type="text" class="form-control" id="ciudadano" name="ciudadano" required maxlength="100"
                                       placeholder="Nombre del ciudadano que reporta">
                                <div class="invalid-feedback">Por favor ingrese el nombre del ciudadano.</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="descripcion" class="form-label fw-semibold">Descripción *</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="4" required maxlength="255"
                                  placeholder="Describa detalladamente el problema..."></textarea>
                        <div class="invalid-feedback">Por favor ingrese una descripción detallada.</div>
                        <div class="form-text">
                            <span class="char-count" id="contadorDesc">0</span>/255 caracteres
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="ubicacion" class="form-label fw-semibold">Ubicación *</label>
                                <input type="text" class="form-control" id="ubicacion" name="ubicacion" required maxlength="150"
                                       placeholder="Dirección exacta donde ocurre el problema">
                                <div class="invalid-feedback">Por favor ingrese la ubicación del problema.</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="estado" class="form-label fw-semibold">Estado</label>
                                <select class="form-select" id="estado" name="estado" required>
                                    <option value="pendiente">Pendiente</option>
                                    <option value="en proceso">En Proceso</option>
                                    <option value="resuelto">Resuelto</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary" id="btnSubmit">
                        <i class="bi bi-check-circle"></i> Guardar Denuncia
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>