<?php include_once 'header.php'; ?>

<!-- CSS de DataTables desde CDN -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

      <main>    
        <h4 align="center">DIRECTORIO EMPRESARIAL</h4>    
        <div style="background: #ecf0f1;" class="table-responsive">  
          <table align="center" width="100%" class="table table-striped table-bordered" id="employee_data" style="font-size:12px;">  
              <thead align="left" style="background: #a6acaf" >  
                <tr width="100%" align="center" style="font-size: 0.9rem;">
                  <td><strong>ÁREA</strong></td> 
                  <td><strong>COLABORADOR</strong></td> 
                  <td><strong>EXTENSIÓN</strong></td>    
                </tr>  
              </thead>  
              <?php
                include_once "../cx/bd_scs.php";
                $q = "SELECT d.dir_txt_area,d.dir_txt_colaborador,d.dir_val_extensión FROM plataforma_web.dbo.int_directorio AS d WHERE d.dir_sts_estado=1";
                $r = resp_simdim($q);
                foreach($r as $a){  
              ?>
                <tr align="center"> 
                  <td align="center" style="font-size: 0.8rem;"><strong><?php echo $a['dir_txt_area']; ?></strong></td>
                  <td align="left" style="font-size: 0.8rem;"><?php echo $a['dir_txt_colaborador']; ?></td>                                             
                  <td align="left" style="font-size: 0.8rem;"><?php echo $a['dir_val_extensión']; ?></td> 
                </tr>  
              <?php
                }                          
              ?>                                
          </table>
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