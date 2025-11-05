<?php include_once 'header.php'; ?>

<!-- CSS de DataTables desde CDN -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<style>
  .modern-page-header {
    text-align: center;
    margin-bottom: 2rem;
    padding: 1.5rem 0;
  }
  
  .modern-page-header h4 {
    font-size: 2rem;
    font-weight: 600;
    background: linear-gradient(135deg, #64b5f6 0%, #1976d2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 0.5rem;
  }
  
  .table-container {
    background: white;
    border-radius: 16px;
    padding: 1.5rem;
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
    border: 1px solid rgba(0,0,0,0.05);
  }
  
  [data-bs-theme="dark"] .table-container {
    background: #212529;
    border-color: rgba(255,255,255,0.1);
  }
  
  .table {
    margin-bottom: 0;
  }
  
  .table thead {
    background: linear-gradient(135deg, #64b5f6 0%, #1976d2 100%);
    color: white;
  }
  
  .table thead th,
  .table thead td {
    border: none;
    padding: 1rem;
    font-weight: 600;
    font-size: 0.95rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }
  
  .table tbody tr {
    transition: all 0.2s ease;
  }
  
  .table tbody tr:hover {
    background-color: rgba(100, 181, 246, 0.05);
    transform: scale(1.01);
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
  }
  
  .table tbody td {
    padding: 0.875rem 1rem;
    vertical-align: middle;
    border-color: rgba(0,0,0,0.05);
  }
  
  [data-bs-theme="dark"] .table tbody tr:hover {
    background-color: rgba(100, 181, 246, 0.1);
  }
  
  .dataTables_wrapper {
    padding-top: 1rem;
  }
  
  .dataTables_filter input {
    border-radius: 8px;
    border: 1px solid #dee2e6;
    padding: 0.5rem 0.75rem;
    transition: all 0.3s ease;
  }
  
  .dataTables_filter input:focus {
    border-color: #64b5f6;
    box-shadow: 0 0 0 0.2rem rgba(100, 181, 246, 0.25);
    outline: none;
  }
  
  .dataTables_length select {
    border-radius: 8px;
    border: 1px solid #dee2e6;
    padding: 0.5rem 0.75rem;
  }
  
  .dataTables_paginate .paginate_button {
    border-radius: 6px;
    margin: 0 2px;
    transition: all 0.2s ease;
  }
  
  .dataTables_paginate .paginate_button:hover {
    background: linear-gradient(135deg, #64b5f6 0%, #1976d2 100%);
    color: white !important;
    border-color: transparent;
  }
  
  .dataTables_paginate .paginate_button.current {
    background: linear-gradient(135deg, #64b5f6 0%, #1976d2 100%);
    color: white !important;
    border-color: transparent;
  }
</style>

      <main>    
        <div class="modern-page-header">
          <h4>DIRECTORIO EMPRESARIAL</h4>
          <p class="text-muted">Consulta la información de contacto de nuestros colaboradores</p>
        </div>
        <div class="table-container">
          <div class="table-responsive">  
            <table align="center" width="100%" class="table table-striped table-hover" id="employee_data" style="font-size:12px;">  
                <thead align="left">  
                  <tr width="100%" align="center" style="font-size: 0.9rem;">
                    <th>ÁREA</th> 
                    <th>COLABORADOR</th> 
                    <th>EXTENSIÓN</th>    
                  </tr>  
                </thead>  
                <tbody>
              <?php
                include_once "../cx/bd_scs.php";
                $q = "SELECT d.dir_txt_area,d.dir_txt_colaborador,d.dir_val_extensión FROM plataforma_web.dbo.int_directorio AS d WHERE d.dir_sts_estado=1";
                $r = resp_simdim($q);
                foreach($r as $a){  
              ?>
                <tr> 
                  <td align="center" style="font-size: 0.9rem;"><strong><?php echo htmlspecialchars($a['dir_txt_area']); ?></strong></td>
                  <td align="left" style="font-size: 0.9rem;"><?php echo htmlspecialchars($a['dir_txt_colaborador']); ?></td>                                             
                  <td align="left" style="font-size: 0.9rem;"><?php echo htmlspecialchars($a['dir_val_extensión']); ?></td> 
                </tr>  
              <?php
                }                          
              ?>
                </tbody>                              
            </table>
          </div>
        </div>      
      </main>

<!-- DataTables JS desde CDN -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
  $(document).ready(function() {
    if (typeof $.fn.DataTable !== 'undefined' && $('#employee_data').length) {
      $('#employee_data').DataTable({
        pageLength: 10,
        language: {
          url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
        },
        order: [[0, 'asc']]
      });
    }
  });
</script>

<?php include_once 'footer.php'; ?>