<?php include_once 'partials/header.php'; ?>
<!-- Barra de búsqueda y controles -->
<div class="card shadow-sm mb-4">
    <div class="card-body py-3">
        <div class="row align-items-center">
            <div class="col-xl-4 col-lg-5 col-md-6 mb-2 mb-md-0">
                <h5 class="card-title mb-0 text-primary">
                    Gestión de Denuncias
                </h5>
            </div>
            <div class="col-xl-5 col-lg-4 col-md-6 mb-2 mb-md-0">
                <div class="input-group">
                    <span class="input-group-text bg-light">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" class="form-control" id="inputBusqueda" 
                           placeholder="Buscar por título, ciudadano o ubicación...">
                    <button class="btn btn-outline-secondary" type="button" id="btnLimpiar" title="Limpiar búsqueda">
                        <i class="bi bi-x-circle"></i>
                    </button>
                </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-12 text-lg-end text-md-start">
                <button class="btn btn-success w-100 w-md-auto" data-bs-toggle="modal" data-bs-target="#denunciaModal" data-id="">
                    <i class="bi bi-plus-circle"></i> Nueva Denuncia
                </button>
            </div>
        </div>
        
        <!-- Filtros adicionales -->
        <div class="row mt-3">
            <div class="col-md-6">
                <div class="d-flex align-items-center flex-wrap">
                    <label class="form-label mb-0 me-2 text-muted">Mostrar:</label>
                    <select class="form-select form-select-sm me-3" id="selectRegistros" style="width: auto;">
                        <option value="5"selected>5 denuncias</option>
                        <option value="10">10 denuncias</option>
                        <option value="15">15 denuncias</option>
                        <option value="20">20 denuncias</option>
                        <option value="50">50 denuncias</option>
                    </select>
                    <span class="text-muted small" id="infoRegistros">Cargando...</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-container">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Título</th>
                        <th>Descripción</th>
                        <th>Ubicación</th>
                        <th>Ciudadano</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th class="text-center pe-3">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tablaDenuncias">
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Cargando...</span>
                            </div>
                            <p class="mt-2 text-muted">Cargando denuncias...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Paginación -->
        <div class="card-footer bg-white border-top-0 pt-3">
            <nav aria-label="Paginación">
                <ul class="pagination justify-content-center mb-0" id="paginacion">

                </ul>
            </nav>
        </div>
    </div>
</div>

<?php include_once 'partials/modal_denuncia.php'; ?>
<?php include_once 'partials/modal_confirm.php'; ?>
<?php include_once 'partials/footer.php'; ?>