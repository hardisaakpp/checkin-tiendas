<?php include_once 'header.php'; ?>

<!-- CSS de DataTables desde CDN -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

      <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
      <style>
        .icon-container {
          position: relative;
          cursor: pointer;
          display: inline-block;
          animation: shake 0.6s infinite;
          transform-origin: center;
        }

        @keyframes shake {
          0% { transform: rotate(0deg); }
          25% { transform: rotate(-5deg); }
          50% { transform: rotate(5deg); }
          75% { transform: rotate(-5deg); }
          100% { transform: rotate(0deg); }
        }

        .hand-icon {
          font-size: 2rem;
          color: #007bff;
          display: inline-block;
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

        .icon-container:hover .tooltip-text {
          opacity: 1;
        }
      </style>
      <main> 
        <h4 align="center">ENLACES A SERVICIOS EMPRESARIALES</h4>       
        <div style="background: #ecf0f1;" class="table-responsive">  
            <table width="100%" class="table table-striped table-bordered" id="employee_data" style="font-size:12px; text-align: center;">  
                <thead style="background: #a6acaf;" > 
                  <tr width="100%" style="font-size: 0.9rem;">
                    <td><strong>NOMBRE SERVICIO</strong></td> 
                    <td><strong>ENLACE SERVICIO</strong></td> 
                  </tr>  
                </thead>  
                <?php
                  include_once "../cx/bd_scs.php";
                  $q = "SELECT s.ser_txt_nombre, s.ser_txt_enlace FROM plataforma_web.dbo.int_servicio AS s WHERE s.ser_sts_estado =1;";
                  $r = resp_simdim($q);
                  foreach($r as $a){  
                ?>
                      <tr> 
                        <td style="font-size: 0.8rem; vertical-align: middle; text-align: center;"><strong><?php echo $a['ser_txt_nombre']; ?></strong></td>
                        <td>
                          <a href="<?php echo $a['ser_txt_enlace']; ?>" target="_blank" onmousedown="showStar()" style="text-decoration: none;">
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
            </table>
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