<?php include_once 'header.php'; ?>

<!-- CSS de DataTables desde CDN -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

      <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
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
        
        .icon-container {
          position: relative;
          cursor: pointer;
          display: inline-block;
          transition: transform 0.3s ease;
        }

        .icon-container:hover {
          transform: scale(1.1);
        }

        .hand-icon {
          font-size: 2rem;
          color: #64b5f6;
          display: inline-block;
          transition: color 0.3s ease;
        }

        .icon-container:hover .hand-icon {
          color: #1976d2;
        }

        .star {
          position: absolute;
          top: 5%;
          left: 55%;
          transform: translate(-50%, -50%);
          color: gold;
          font-size: 1.2rem;
          opacity: 0;
          pointer-events: none;
        }

        .star.animate {
          animation: pop 0.6s ease-out;
        }

        @keyframes pop {
          0% {
            transform: translate(-50%, -50%) scale(0.5);
            opacity: 0;
          }
          50% {
            transform: translate(-50%, -50%) scale(1.2);
            opacity: 1;
          }
          100% {
            transform: translate(-50%, -50%) scale(1);
            opacity: 0;
          }
        }

        .tooltip-text {
          position: absolute;
          top: -2.5rem;
          left: 50%;
          transform: translateX(-50%);
          background: linear-gradient(135deg, #64b5f6 0%, #1976d2 100%);
          color: #fff;
          padding: 6px 12px;
          border-radius: 8px;
          font-size: 0.85rem;
          white-space: nowrap;
          opacity: 0;
          transition: opacity 0.3s ease;
          pointer-events: none;
          box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .icon-container:hover .tooltip-text {
          opacity: 1;
        }
      </style>
      <main> 
        <div class="modern-page-header">
          <h4>ENLACES A SERVICIOS EMPRESARIALES</h4>
          <p class="text-muted">Accede a los servicios empresariales disponibles</p>
        </div>
        <div class="table-container">
          <div class="table-responsive">  
            <table width="100%" class="table table-striped table-hover" id="employee_data" style="font-size:12px; text-align: center;">  
                <thead> 
                  <tr width="100%" style="font-size: 0.9rem;">
                    <th>NOMBRE SERVICIO</th> 
                    <th>ENLACE SERVICIO</th> 
                  </tr>  
                </thead>
                <tbody>  
                <?php
                  include_once "../cx/bd_scs.php";
                  $q = "SELECT s.ser_txt_nombre, s.ser_txt_enlace FROM plataforma_web.dbo.int_servicio AS s WHERE s.ser_sts_estado =1;";
                  $r = resp_simdim($q);
                  foreach($r as $a){  
                ?>
                      <tr> 
                        <td style="font-size: 0.9rem; vertical-align: middle; text-align: center;"><strong><?php echo htmlspecialchars($a['ser_txt_nombre']); ?></strong></td>
                        <td>
                          <a href="<?php echo htmlspecialchars($a['ser_txt_enlace']); ?>" target="_blank" onmousedown="showStar()" style="text-decoration: none;">
                            <div class="icon-container">
                              <span class="tooltip-text">Pulsa aquí</span>
                              <i class="bi bi-hand-index-thumb-fill hand-icon"></i>
                              <div class="star" id="star"><i class="bi bi-star-fill"></i></div>
                            </div>
                          </a>
                        </td>                                             
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
        document.querySelectorAll('.icon-container').forEach(container => {
          container.addEventListener('mousedown', () => {
            const star = container.querySelector('.star');
            star.classList.remove('animate');
            void star.offsetWidth; // trigger reflow
            star.classList.add('animate');
          });
        });

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