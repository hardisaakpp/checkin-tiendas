<?php
// Validar autenticación antes de cargar cualquier contenido
require_once 'auth.php';
requireAuth('admin'); // Requiere rol de administrador

include_once 'header.php'; ?>

<!-- CSS de DataTables desde CDN -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<style>
  .tooltip-text {
    position: absolute;
    top: -2.5rem;
    left: 50%;
    transform: translateX(-50%);
    background-color: #333;
    color: #fff;
    padding: 5px 10px;
    border-radius: 6px;
    font-size: 0.9rem;
    white-space: nowrap;
    opacity: 0;
    transition: opacity 0.3s ease;
    pointer-events: none;
  }

  .icon-container {
    position: relative;
    display: inline-block;
  }

  .icon-container:hover .tooltip-text {
    opacity: 1;
  }

  .modal-content {
    border-radius: 8px;
  }

  .badge-estado {
    font-size: 0.85rem;
    padding: 0.4em 0.6em;
  }

  /* Estilos para Select2 en modo oscuro */
  [data-bs-theme="dark"] .select2-container--bootstrap-5 .select2-selection {
    background-color: #212529 !important;
    border-color: #495057 !important;
    color: #f8f9fa !important;
  }

  [data-bs-theme="dark"] .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
    color: #f8f9fa !important;
  }

  [data-bs-theme="dark"] .select2-container--bootstrap-5 .select2-selection--single .select2-selection__arrow {
    color: #f8f9fa !important;
  }

  [data-bs-theme="dark"] .select2-dropdown {
    background-color: #212529 !important;
    border-color: #495057 !important;
  }

  [data-bs-theme="dark"] .select2-container--bootstrap-5 .select2-results__option {
    background-color: #212529 !important;
    color: #f8f9fa !important;
  }

  [data-bs-theme="dark"] .select2-container--bootstrap-5 .select2-results__option--highlighted {
    background-color: #495057 !important;
    color: #f8f9fa !important;
  }

  [data-bs-theme="dark"] .select2-container--bootstrap-5 .select2-search--dropdown .select2-search__field {
    background-color: #212529 !important;
    border-color: #495057 !important;
    color: #f8f9fa !important;
  }

  [data-bs-theme="dark"] .select2-container--bootstrap-5 .select2-selection--single .select2-selection__placeholder {
    color: #dee2e6 !important;
  }

  /* Asegurar que el texto dentro del dropdown sea claro */
  [data-bs-theme="dark"] .select2-results__option {
    color: #f8f9fa !important;
  }

  [data-bs-theme="dark"] .select2-results__option[aria-selected] {
    color: #f8f9fa !important;
  }

  [data-bs-theme="dark"] .select2-results__option--selectable {
    color: #f8f9fa !important;
  }

  /* Estilos para el modal en modo oscuro */
  [data-bs-theme="dark"] .modal-content {
    background-color: #212529 !important;
    color: #f8f9fa !important;
    border-color: #495057 !important;
  }

  [data-bs-theme="dark"] .modal-header {
    background-color: #212529 !important;
    border-bottom-color: #495057 !important;
    color: #f8f9fa !important;
  }

  [data-bs-theme="dark"] .modal-title {
    color: #f8f9fa !important;
  }

  [data-bs-theme="dark"] .modal-body {
    background-color: #212529 !important;
    color: #f8f9fa !important;
  }

  [data-bs-theme="dark"] .modal-footer {
    background-color: #212529 !important;
    border-top-color: #495057 !important;
  }

  [data-bs-theme="dark"] .form-label {
    color: #f8f9fa !important;
  }

  [data-bs-theme="dark"] .form-label strong {
    color: #f8f9fa !important;
  }

  [data-bs-theme="dark"] .form-control {
    background-color: #212529 !important;
    border-color: #495057 !important;
    color: #f8f9fa !important;
  }

  [data-bs-theme="dark"] .form-control:focus {
    background-color: #212529 !important;
    border-color: #64b5f6 !important;
    color: #f8f9fa !important;
  }

  [data-bs-theme="dark"] .form-select {
    background-color: #212529 !important;
    border-color: #495057 !important;
    color: #f8f9fa !important;
  }

  [data-bs-theme="dark"] .form-select:focus {
    background-color: #212529 !important;
    border-color: #64b5f6 !important;
    color: #f8f9fa !important;
  }

  [data-bs-theme="dark"] .btn-close {
    filter: invert(1) grayscale(100%) brightness(200%);
  }
</style>

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<main>
  <div class="container-fluid">
    <div class="row mb-4">
      <div class="col-md-12">
        <h4 align="center">ADMINISTRACIÓN DE DIRECTORIO TELEFÓNICO</h4>
        <hr>
        <div align="center">
          <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalDirectorio" onclick="abrirModalNuevo()">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-plus-circle-fill" viewBox="0 0 16 16">
              <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z"/>
            </svg> Nuevo Registro
          </button>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <div style="background: #ecf0f1;" class="table-responsive">
          <table align="center" width="100%" class="table table-striped table-bordered" id="tabla_directorio" style="font-size:12px;">
            <thead align="left" style="background: #a6acaf">
              <tr width="100%" align="center" style="font-size: 0.9rem;">
                <td><strong>ÁREA</strong></td>
                <td><strong>COLABORADOR</strong></td>
                <td><strong>EXTENSIÓN</strong></td>
                <td><strong>ESTADO</strong></td>
                <td><strong>FECHA REGISTRO</strong></td>
                <td><strong>ACCIÓN</strong></td>
              </tr>
            </thead>
            <tbody>
              <?php
              include_once "../cx/bd_scs.php";
              
              // Obtener áreas únicas para el dropdown
              $q_areas = "SELECT DISTINCT dir_txt_area 
                         FROM plataforma_web.dbo.int_directorio 
                         WHERE dir_txt_area IS NOT NULL AND dir_txt_area != ''
                         ORDER BY dir_txt_area";
              $areas_list = resp_simdim($q_areas);
              
              $q = "SELECT dir_id, dir_txt_area, dir_txt_colaborador, dir_val_extensión, dir_sts_estado, dir_fec_registro, dir_fec_cambio
                    FROM plataforma_web.dbo.int_directorio 
                    ORDER BY dir_sts_estado DESC, dir_txt_area, dir_txt_colaborador";
              $r = resp_simdim($q);
              foreach ($r as $a) {
                // Manejar estado (puede ser 1, 0, o NULL)
                $estado_valor = isset($a['dir_sts_estado']) ? intval($a['dir_sts_estado']) : 0;
                $estado_texto = $estado_valor == 1 ? 'Activo' : 'Inactivo';
                $estado_badge = $estado_valor == 1 ? 'bg-success' : 'bg-secondary';
                $fecha_registro = date('Y-m-d', strtotime($a['dir_fec_registro']));
              ?>
                <tr align="center" data-id="<?php echo $a['dir_id']; ?>">
                  <td align="center" style="font-size: 0.8rem;"><strong><?php echo htmlspecialchars($a['dir_txt_area']); ?></strong></td>
                  <td align="left" style="font-size: 0.8rem;"><?php echo htmlspecialchars($a['dir_txt_colaborador']); ?></td>
                  <td align="left" style="font-size: 0.8rem;"><?php echo htmlspecialchars($a['dir_val_extensión']); ?></td>
                  <td align="center" style="font-size: 0.8rem;">
                    <span class="badge <?php echo $estado_badge; ?> badge-estado"><?php echo $estado_texto; ?></span>
                  </td>
                  <td align="center" style="font-size: 0.8rem;"><?php echo $fecha_registro; ?></td>
                  <td align="center" style="font-size: 0.8rem;">
                    <button class="btn btn-sm btn-warning" onclick="abrirModalEditar(
                      <?php echo $a['dir_id']; ?>,
                      '<?php echo addslashes($a['dir_txt_area']); ?>',
                      '<?php echo addslashes($a['dir_txt_colaborador']); ?>',
                      '<?php echo addslashes($a['dir_val_extensión']); ?>',
                      <?php echo $a['dir_sts_estado']; ?>
                    )">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
                        <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.5.5 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11z"/>
                      </svg>
                    </button>
                    <button type="button" class="btn btn-sm btn-danger btn-eliminar-directorio" 
                            data-id="<?php echo $a['dir_id']; ?>" 
                            data-colaborador="<?php echo htmlspecialchars($a['dir_txt_colaborador'], ENT_QUOTES, 'UTF-8'); ?>">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                        <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0"/>
                      </svg>
                    </button>
                  </td>
                </tr>
              <?php
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</main>

<!-- Modal para Crear/Editar -->
<div class="modal fade" id="modalDirectorio" tabindex="-1" aria-labelledby="modalDirectorioLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalDirectorioLabel">Gestión de Directorio</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="formDirectorio">
        <div class="modal-body">
          <input type="hidden" name="accion" id="accion" value="crear">
          <input type="hidden" name="dir_id" id="dir_id">
          
          <div class="mb-3">
            <label for="dir_txt_area" class="form-label"><strong>Área/Departamento</strong></label>
            <select class="form-select" id="dir_txt_area" name="dir_txt_area" required>
              <option value="">-- Seleccione un área --</option>
              <?php foreach ($areas_list as $area_item): ?>
                <option value="<?php echo htmlspecialchars($area_item['dir_txt_area']); ?>">
                  <?php echo htmlspecialchars($area_item['dir_txt_area']); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="mb-3">
            <label for="dir_txt_colaborador" class="form-label"><strong>Colaborador</strong></label>
            <input type="text" class="form-control" id="dir_txt_colaborador" name="dir_txt_colaborador" required>
          </div>

          <div class="mb-3">
            <label for="dir_val_extensión" class="form-label"><strong>Extensión</strong></label>
            <input type="text" class="form-control" id="dir_val_extensión" name="dir_val_extensión" required>
          </div>

          <div class="mb-3">
            <label for="dir_sts_estado" class="form-label"><strong>Estado</strong></label>
            <select class="form-select" id="dir_sts_estado" name="dir_sts_estado">
              <option value="1">Activo</option>
              <option value="0">Inactivo</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- DataTables JS desde CDN - Cargar ANTES de Select2 -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
  $(document).ready(function() {
    // Inicializar DataTables
    if (typeof $.fn.DataTable !== 'undefined' && $('#tabla_directorio').length) {
      $('#tabla_directorio').DataTable({
        pageLength: 10,
        language: {
          url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
        },
        order: [[0, 'asc']],
        columnDefs: [
          { orderable: false, targets: -1 } // Deshabilitar orden en columna de acciones
        ]
      });
    } else {
      console.error('DataTables no está disponible o la tabla no existe');
    }

    // Inicializar Select2 en el dropdown de áreas
    $('#dir_txt_area').select2({
      theme: 'bootstrap-5',
      placeholder: '-- Seleccione un área --',
      allowClear: true,
      width: '100%',
      language: {
        noResults: function() {
          return "No se encontraron áreas";
        },
        searching: function() {
          return "Buscando...";
        }
      },
      dropdownParent: $('#modalDirectorio')
    });

    // Ajustar el dropdown cuando se abre el modal
    $('#modalDirectorio').on('shown.bs.modal', function () {
      $('#dir_txt_area').select2({
        dropdownParent: $('#modalDirectorio')
      });
    });

    // Función para actualizar Select2 cuando cambia el tema
    function actualizarSelect2PorTema() {
      const temaActual = document.documentElement.getAttribute('data-bs-theme');
      const esOscuro = temaActual === 'dark' || 
                       (temaActual === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches);
      
      // Forzar actualización de Select2
      $('#dir_txt_area').select2('destroy');
      $('#dir_txt_area').select2({
        theme: 'bootstrap-5',
        placeholder: '-- Seleccione un área --',
        allowClear: true,
        width: '100%',
        language: {
          noResults: function() {
            return "No se encontraron áreas";
          },
          searching: function() {
            return "Buscando...";
          }
        },
        dropdownParent: $('#modalDirectorio')
      });
    }

    // Observar cambios en el atributo data-bs-theme
    const observer = new MutationObserver(function(mutations) {
      mutations.forEach(function(mutation) {
        if (mutation.type === 'attributes' && mutation.attributeName === 'data-bs-theme') {
          // Pequeño delay para asegurar que el CSS se haya aplicado
          setTimeout(actualizarSelect2PorTema, 100);
        }
      });
    });

    // Observar el elemento html
    observer.observe(document.documentElement, {
      attributes: true,
      attributeFilter: ['data-bs-theme']
    });

    // También escuchar cambios en la preferencia del sistema (para modo auto)
    if (window.matchMedia) {
      window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function() {
        const temaActual = document.documentElement.getAttribute('data-bs-theme');
        if (temaActual === 'auto') {
          setTimeout(actualizarSelect2PorTema, 100);
        }
      });
    }

    // Event delegation para el botón de eliminar (funciona con DataTables)
    $(document).on('click', '.btn-eliminar-directorio', function(e) {
      e.preventDefault();
      e.stopPropagation();
      
      const id = $(this).data('id');
      const colaborador = $(this).data('colaborador') || 'este registro';
      
      if (confirm('¿Está seguro de eliminar el registro de ' + colaborador + '?')) {
        eliminarRegistro(id);
      }
    });
  });

  function abrirModalNuevo() {
    $('#accion').val('crear');
    $('#dir_id').val('');
    $('#formDirectorio')[0].reset();
    $('#modalDirectorioLabel').text('Nuevo Registro');
    $('#dir_sts_estado').val('1');
    $('#dir_txt_area').val(null).trigger('change'); // Resetear Select2
  }

  function abrirModalEditar(id, area, colaborador, extension, estado) {
    $('#accion').val('editar');
    $('#dir_id').val(id);
    $('#dir_txt_colaborador').val(colaborador);
    $('#dir_val_extensión').val(extension);
    $('#dir_sts_estado').val(estado);
    $('#modalDirectorioLabel').text('Editar Registro');
    
    let modal = new bootstrap.Modal(document.getElementById('modalDirectorio'));
    modal.show();
    
    // Establecer el valor en Select2 después de que el modal se muestre
    setTimeout(function() {
      $('#dir_txt_area').val(area).trigger('change');
    }, 100);
  }

  function eliminarRegistro(id) {
    $.ajax({
      url: 'crud_directorio.php',
      type: 'POST',
      data: {
        accion: 'eliminar',
        dir_id: id
      },
      dataType: 'json',
      success: function(response) {
        if (response && response.status === 'success') {
          alert('Registro eliminado correctamente');
          location.reload();
        } else {
          const mensaje = response && response.message ? response.message : 'Error desconocido';
          alert('Error: ' + mensaje);
        }
      },
      error: function(xhr, status, error) {
        console.error('Error al eliminar registro:', error);
        
        let mensajeError = 'Error al eliminar el registro';
        try {
          const response = JSON.parse(xhr.responseText);
          if (response && response.message) {
            mensajeError = response.message;
          }
        } catch(e) {
          if (xhr.responseText) {
            mensajeError += ': ' + xhr.responseText.substring(0, 100);
          }
        }
        
        alert(mensajeError);
      }
    });
  }

  $('#formDirectorio').on('submit', function(e) {
    e.preventDefault();
    
    let formData = $(this).serialize();
    
    $.ajax({
      url: 'crud_directorio.php',
      type: 'POST',
      data: formData,
      dataType: 'json',
      success: function(response) {
        if (response && response.status === 'success') {
          // Cerrar el modal primero
          const modal = bootstrap.Modal.getInstance(document.getElementById('modalDirectorio'));
          if (modal) {
            modal.hide();
          }
          
          alert('Registro guardado correctamente. Recargando página...');
          
          // Recargar la página después de un pequeño delay
          setTimeout(function() {
            location.reload();
          }, 300);
        } else {
          const mensaje = response && response.message ? response.message : 'Error desconocido';
          alert('Error: ' + mensaje);
        }
      },
      error: function(xhr, status, error) {
        console.error('Error al guardar registro:', error);
        
        let mensajeError = 'Error al guardar el registro';
        try {
          const response = JSON.parse(xhr.responseText);
          if (response && response.message) {
            mensajeError = response.message;
          }
        } catch(e) {
          if (xhr.responseText) {
            mensajeError += ': ' + xhr.responseText.substring(0, 100);
          }
        }
        alert(mensajeError);
      }
    });
  });

  // Convertir a mayúsculas mientras se escribe (solo colaborador)
  $('#dir_txt_colaborador').on('input', function() {
    this.value = this.value.toUpperCase();
  });
</script>

<?php include_once 'footer.php'; ?>




