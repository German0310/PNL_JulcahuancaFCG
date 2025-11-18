class DenunciasManager {
    constructor() {
        this.paginaActual = 1;
        this.busquedaActual = '';
        this.registrosPorPagina = 5;
        this.accionConfirmacion = null;
        this.datosConfirmacion = null;
        this.init();
    }

    init() {
        this.cargarDenuncias();
        this.setupEventListeners();
        this.setupFormValidation();
    }

    setupEventListeners() {
        // Búsqueda con debounce
        $('#inputBusqueda').on('input', this.debounce(() => {
            this.buscarDenuncias();
        }, 500));
        
        $('#btnLimpiar').click(() => this.limpiarBusqueda());

        // Selector de registros por página
        $('#selectRegistros').change(() => {
            this.registrosPorPagina = parseInt($('#selectRegistros').val());
            this.cargarDenuncias(1, this.busquedaActual);
        });

        // Modal de denuncia
        $('#denunciaModal').on('show.bs.modal', (e) => {
            const button = $(e.relatedTarget);
            const id = button.data('id') || '';
            this.abrirModalDenuncia(id);
        });

        $('#denunciaModal').on('hidden.bs.modal', () => {
            this.limpiarFormulario();
        });

        // Contadores de caracteres
        $('#titulo').on('input', () => this.actualizarContador('titulo'));
        $('#descripcion').on('input', () => this.actualizarContador('descripcion'));

        // Formulario de denuncia
        $('#denunciaForm').on('submit', (e) => this.enviarFormulario(e));

        // Modal de confirmación unificado
        $('#btnConfirmAction').click(() => this.ejecutarAccionConfirmada());

        // Exportar datos
        $('#btnExportar').click(() => this.exportarDatos());
    }

    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    setupFormValidation() {
        // Validación en tiempo real
        $('input, textarea, select').on('blur', function() {
            $(this).removeClass('is-invalid is-valid');
            if ($(this).prop('required')) {
                if ($(this).val().trim()) {
                    $(this).addClass('is-valid');
                } else {
                    $(this).addClass('is-invalid');
                }
            }
        });
    }

    actualizarContador(tipo) {
        const input = $(`#${tipo}`);
        const contador = $(`#contador${tipo.charAt(0).toUpperCase() + tipo.slice(1)}`);
        const longitud = input.val().length;
        const maxLength = input.attr('maxlength');
        
        contador.text(longitud);
        contador.removeClass('text-warning text-danger');
        
        if (longitud > maxLength * 0.8 && longitud <= maxLength) {
            contador.addClass('text-warning');
        } else if (longitud > maxLength) {
            contador.addClass('text-danger');
            input.addClass('is-invalid');
        } else {
            input.removeClass('is-invalid');
        }
    }

    async cargarDenuncias(pagina = 1, busqueda = '') {
        try {
            $('#tablaDenuncias').addClass('loading');
            
            const params = new URLSearchParams({
                pagina: pagina,
                busqueda: busqueda,
                registros_por_pagina: this.registrosPorPagina
            });

            const response = await fetch(`../controllers/ApiController.php?${params}`);
            const data = await response.json();

            if (data.success) {
                this.mostrarDenuncias(data.denuncias);
                this.mostrarPaginacion(data.paginacion);
                this.actualizarInfoRegistros(data.paginacion);
                this.paginaActual = pagina;
                this.busquedaActual = busqueda;
            } else {
                this.mostrarError('Error al cargar las denuncias');
            }
        } catch (error) {
            console.error('Error:', error);
            this.mostrarError('Error de conexión con el servidor');
        } finally {
            $('#tablaDenuncias').removeClass('loading');
        }
    }

    mostrarDenuncias(denuncias) {
    const tbody = $('#tablaDenuncias');
    
    if (denuncias.length === 0) {
        tbody.html(`
            <tr class="fade-in">
                <td colspan="7" class="text-center py-5">
                    <div class="empty-state">
                        <i class="bi bi-inbox display-4 text-muted"></i>
                        <h5 class="mt-3 text-muted">No se encontraron denuncias</h5>
                        <p class="text-muted">No hay denuncias que coincidan con tu búsqueda</p>
                    </div>
                </td>
            </tr>
        `);
        return;
    }

    const html = denuncias.map((denuncia, index) => `
        <tr class="fade-in">
            <td class="ps-3">
                <div class="title-container">
                    <span class="status-indicator status-${denuncia.estado.replace(' ', '-')}"></span>
                    <div class="title-text">
                        <strong class="denuncia-title text-break line-height-normal">
                            ${this.escapeHtml(denuncia.titulo)}
                        </strong>
                    </div>
                </div>
            </td>
            <td>
                <div class="denuncia-desc text-break line-height-normal">
                    ${this.escapeHtml(denuncia.descripcion)}
                </div>
            </td>
            <td>
                <div class="denuncia-ubicacion text-break line-height-normal">
                    ${this.escapeHtml(denuncia.ubicacion)}
                </div>
            </td>
            <td>
                <div class="denuncia-ciudadano text-break line-height-normal">
                    ${this.escapeHtml(denuncia.ciudadano)}
                </div>
            </td>
            <td>
                <span class="badge badge-estado badge-${denuncia.estado.replace(' ', '-')}">
                    ${denuncia.estado}
                </span>
            </td>
            <td>
                <small class="denuncia-fecha text-break line-height-normal">
                    ${this.formatearFecha(denuncia.fecha_registro)}
                </small>
            </td>
            <td class="text-center pe-3">
                <div class="btn-group btn-group-sm" role="group">
                    <button class="btn btn-outline-warning btn-action" 
                            data-bs-toggle="modal" 
                            data-bs-target="#denunciaModal"
                            data-id="${denuncia.id}"
                            title="Editar denuncia">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <button class="btn btn-outline-danger btn-action" 
                            onclick="denunciasManager.solicitarEliminacion(${denuncia.id})"
                            title="Eliminar denuncia">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');

    tbody.html(html);
}

    mostrarPaginacion(paginacion) {
        const nav = $('#paginacion');
        const { pagina_actual, total_paginas, total_registros } = paginacion;

        if (total_paginas <= 1) {
            nav.html(`
                <li class="page-item disabled">
                    <span class="page-link">Página 1 de 1</span>
                </li>
            `);
            return;
        }

        let html = '';
        
        // Botón anterior
        html += `<li class="page-item ${pagina_actual === 1 ? 'disabled' : ''}">
                    <a class="page-link" href="#" onclick="denunciasManager.cambiarPagina(${pagina_actual - 1})">
                        <i class="bi bi-chevron-left"></i>
                    </a>
                 </li>`;

        // Páginas
        const paginasMostrar = this.generarRangoPaginas(pagina_actual, total_paginas);
        
        paginasMostrar.forEach(pagina => {
            if (pagina === '...') {
                html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
            } else {
                html += `<li class="page-item ${pagina === pagina_actual ? 'active' : ''}">
                            <a class="page-link" href="#" onclick="denunciasManager.cambiarPagina(${pagina})">${pagina}</a>
                         </li>`;
            }
        });

        // Botón siguiente
        html += `<li class="page-item ${pagina_actual === total_paginas ? 'disabled' : ''}">
                    <a class="page-link" href="#" onclick="denunciasManager.cambiarPagina(${pagina_actual + 1})">
                        <i class="bi bi-chevron-right"></i>
                    </a>
                 </li>`;

        nav.html(html);
    }

    actualizarInfoRegistros(paginacion) {
        const { pagina_actual, total_paginas, total_registros } = paginacion;
        const inicio = ((pagina_actual - 1) * this.registrosPorPagina) + 1;
        const fin = Math.min(pagina_actual * this.registrosPorPagina, total_registros);
        
        let info = `Mostrando ${inicio}-${fin} de ${total_registros} denuncias`;
        if (this.busquedaActual) {
            info += ` (filtradas)`;
        }
        
        $('#infoRegistros').text(info);
    }

    generarRangoPaginas(paginaActual, totalPaginas) {
        const paginas = [];
        const rango = 1;
        
        for (let i = 1; i <= totalPaginas; i++) {
            if (i === 1 || i === totalPaginas || (i >= paginaActual - rango && i <= paginaActual + rango)) {
                paginas.push(i);
            } else if (i === paginaActual - rango - 1 || i === paginaActual + rango + 1) {
                if (!paginas.includes('...')) {
                    paginas.push('...');
                }
            }
        }
        
        return paginas;
    }

    cambiarPagina(pagina) {
        this.cargarDenuncias(pagina, this.busquedaActual);
        if (window.innerWidth < 768) {
            $('html, body').animate({ scrollTop: 0 }, 300);
        }
    }

    buscarDenuncias() {
        const busqueda = $('#inputBusqueda').val().trim();
        this.cargarDenuncias(1, busqueda);
    }

    limpiarBusqueda() {
        $('#inputBusqueda').val('');
        this.cargarDenuncias(1, '');
    }

    async abrirModalDenuncia(id = '') {
        this.limpiarFormulario();

        if (id) {
            $('#modalTitle').html('<i class="bi bi-pencil"></i> Editar Denuncia');
            await this.cargarDatosDenuncia(id);
        } else {
            $('#modalTitle').html('<i class="bi bi-plus-circle"></i> Nueva Denuncia');
            $('#denunciaId').val('');
        }
    }

    async cargarDatosDenuncia(id) {
        try {
            const response = await fetch(`../controllers/ApiController.php?id=${id}`);
            const data = await response.json();

            if (data.success) {
                const denuncia = data.denuncia;
                $('#denunciaId').val(denuncia.id);
                $('#titulo').val(denuncia.titulo);
                $('#descripcion').val(denuncia.descripcion);
                $('#ubicacion').val(denuncia.ubicacion);
                $('#estado').val(denuncia.estado);
                $('#ciudadano').val(denuncia.ciudadano);
                
                // Actualizar contadores
                this.actualizarContador('titulo');
                this.actualizarContador('descripcion');
            } else {
                this.mostrarError('Error al cargar los datos de la denuncia');
            }
        } catch (error) {
            console.error('Error:', error);
            this.mostrarError('Error de conexión al cargar los datos');
        }
    }

    limpiarFormulario() {
        const form = $('#denunciaForm')[0];
        if (form) {
            form.reset();
            form.classList.remove('was-validated');
            $('.invalid-feedback').hide();
            $('.is-valid, .is-invalid').removeClass('is-valid is-invalid');
            $('#contadorTitulo').text('0').removeClass('text-warning text-danger');
            $('#contadorDesc').text('0').removeClass('text-warning text-danger');
        }
    }

    async enviarFormulario(e) {
        e.preventDefault();
        
        const form = $('#denunciaForm')[0];
        if (!form.checkValidity()) {
            e.stopPropagation();
            form.classList.add('was-validated');
            this.mostrarError('Por favor complete todos los campos requeridos correctamente');
            return;
        }

        try {
            const formData = new FormData(form);
            const id = $('#denunciaId').val();
            const accion = id ? 'editar' : 'crear';

            // Mostrar loading en el botón
            const btnSubmit = $('#btnSubmit');
            const originalText = btnSubmit.html();
            btnSubmit.prop('disabled', true).html(`
                <span class="spinner-border spinner-border-sm" role="status"></span>
                ${id ? 'Actualizando...' : 'Creando...'}
            `);

            const response = await fetch(`../controllers/DenunciaController.php?accion=${accion}`, {
                method: 'POST',
                body: formData
            });

            if (response.ok) {
                $('#denunciaModal').modal('hide');
                this.mostrarMensaje(
                    id ? 'Denuncia actualizada correctamente' : 'Denuncia creada correctamente', 
                    'success'
                );
                this.cargarDenuncias(this.paginaActual, this.busquedaActual);
            } else {
                throw new Error('Error en la respuesta del servidor');
            }
        } catch (error) {
            console.error('Error:', error);
            this.mostrarError('Error al guardar la denuncia. Por favor intente nuevamente.');
        } finally {
            $('#btnSubmit').prop('disabled', false).html(`
                <i class="bi bi-check-circle"></i> ${$('#denunciaId').val() ? 'Actualizar' : 'Guardar'} Denuncia
            `);
        }
    }

    solicitarEliminacion(id) {
        this.accionConfirmacion = 'eliminar';
        this.datosConfirmacion = { id };
        $('#confirmMessage').html(`
            <p>¿Está seguro de que desea <strong>eliminar</strong> esta denuncia?</p>
            <p class="text-danger small mb-0"><i class="bi bi-exclamation-triangle"></i> Esta acción no se puede deshacer.</p>
        `);
        $('#btnConfirmAction')
            .removeClass('btn-primary btn-warning')
            .addClass('btn-danger')
            .html('<i class="bi bi-trash"></i> Eliminar');
        $('#confirmModal').modal('show');
    }

    async ejecutarAccionConfirmada() {
        if (!this.accionConfirmacion || !this.datosConfirmacion) return;

        try {
            $('#btnConfirmAction').prop('disabled', true).html(`
                <span class="spinner-border spinner-border-sm" role="status"></span>
                Procesando...
            `);

            if (this.accionConfirmacion === 'eliminar') {
                const response = await fetch(`../controllers/ApiController.php?id=${this.datosConfirmacion.id}`, {
                    method: 'DELETE'
                });

                const data = await response.json();

                if (data.success) {
                    $('#confirmModal').modal('hide');
                    this.mostrarMensaje(data.message, 'success');
                    this.cargarDenuncias(this.paginaActual, this.busquedaActual);
                } else {
                    throw new Error(data.message);
                }
            }
        } catch (error) {
            console.error('Error:', error);
            this.mostrarError('Error al procesar la acción. Por favor intente nuevamente.');
        } finally {
            this.accionConfirmacion = null;
            this.datosConfirmacion = null;
            $('#btnConfirmAction').prop('disabled', false).html('<i class="bi bi-check-circle"></i> Confirmar');
        }
    }

    exportarDatos() {
        this.mostrarMensaje('Función de exportación en desarrollo', 'info');
    }

    // Utilidades
    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    formatearFecha(fecha) {
        return new Date(fecha).toLocaleDateString('es-ES', {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    mostrarMensaje(mensaje, tipo = 'success') {
        $('.alert-dismissible').alert('close');
        
        const alertClass = tipo === 'success' ? 'alert-success' : 
                          tipo === 'error' ? 'alert-danger' : 
                          tipo === 'warning' ? 'alert-warning' : 'alert-info';
        const icon = tipo === 'success' ? 'bi-check-circle' : 
                    tipo === 'error' ? 'bi-exclamation-triangle' : 
                    tipo === 'warning' ? 'bi-exclamation-triangle' : 'bi-info-circle';
        
        const alert = $(`
            <div class="alert ${alertClass} alert-dismissible fade show">
                <i class="bi ${icon} me-2"></i>
                ${mensaje}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `);
        
        $('.container').prepend(alert);
        
        setTimeout(() => {
            alert.alert('close');
        }, 5000);
    }

    mostrarError(mensaje) {
        this.mostrarMensaje(mensaje, 'error');
    }
}

// Inicializar la aplicación
$(document).ready(function() {
    window.denunciasManager = new DenunciasManager();
    
    // Tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});