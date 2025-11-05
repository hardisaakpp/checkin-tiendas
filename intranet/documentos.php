<?php
  if (isset($_GET['descargar']) && is_numeric($_GET['descargar'])) {
    include_once "../cx/bd_scs.php";
    $doc_id = intval($_GET['descargar']);

    $q = "SELECT d.doc_txt_nombre, t.tip_txt_extension, a.acc_txt_ruta
          FROM plataforma_web.dbo.int_documento AS d
          INNER JOIN plataforma_web.dbo.int_doc_tipo AS t ON t.tip_id=d.tip_id
          INNER JOIN plataforma_web.dbo.int_doc_accion AS a ON a.acc_id=d.acc_id
          WHERE d.doc_id = $doc_id";

    $r = resp_simdim($q);
    if (!$r || count($r) === 0) {
      die("Documento no encontrado");
    }

    $doc = $r[0];
    $nombreArchivo = $doc['doc_txt_nombre'] . $doc['tip_txt_extension'];
    $rutaRelativa = "../assets/doc/" . $doc['acc_txt_ruta'] . $doc_id . $doc['tip_txt_extension'];
    $rutaAbsoluta = realpath($rutaRelativa);

    if (!$rutaAbsoluta || !file_exists($rutaAbsoluta)) {
      die("Archivo no encontrado");
    }

    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($nombreArchivo) . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($rutaAbsoluta));
    readfile($rutaAbsoluta);
    exit;
  }
?>

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
      z-index: 100;
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .icon-container {
      position: relative;
      display: inline-block;
      transition: transform 0.3s ease;
    }

    .icon-container:hover {
      transform: scale(1.1);
    }

    .icon-container:hover .tooltip-text {
      opacity: 1;
    }

    .icon-container svg {
      transition: all 0.3s ease;
      color: #64b5f6;
    }

    .icon-container:hover svg {
      color: #1976d2;
      transform: scale(1.1);
    }

    .btn-outline-primary {
      border-radius: 8px;
      transition: all 0.3s ease;
    }

    .btn-outline-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(100, 181, 246, 0.3);
    }
  </style>
  <main>
    <div class="modern-page-header">
      <h4>DOCUMENTACIÓN</h4>
      <p class="text-muted">Explora y descarga documentos corporativos</p>
    </div>
    <div class="table-container">
      <div class="table-responsive">
        <table align="center" width="100%" class="table table-striped table-hover" id="employee_data" style="font-size:12px;">
          <thead align="left">
            <tr width="100%" align="center" style="font-size: 0.9rem;">
              <th>NOMBRE ARCHIVO</th>
              <th>DEPARTAMENTO</th>
              <th>CATEGORÍA</th>
              <th>TIPO</th>
              <th>ACCIÓN</th>
            </tr>
          </thead>
          <tbody>
        <?php
          include_once "../cx/bd_scs.php";
          $q = "SELECT d.doc_id,d.doc_txt_nombre,e.dep_txt_nombre,c.cat_txt_nombre,t.tip_txt_nombre,t.tip_txt_extension,a.acc_id,a.acc_txt_ruta
                FROM plataforma_web.dbo.int_documento AS d
                INNER JOIN plataforma_web.dbo.int_loc_departamento AS e on e.dep_id=d.dep_id
                INNER JOIN plataforma_web.dbo.int_doc_categoria AS c ON c.cat_id=d.cat_id
                INNER JOIN plataforma_web.dbo.int_doc_tipo AS t ON t.tip_id=d.tip_id
                INNER JOIN plataforma_web.dbo.int_doc_accion AS a ON a.acc_id=d.acc_id
                WHERE d.doc_sts_estado=1 AND e.dep_sts_estado=1 AND c.cat_sts_estado=1 AND t.tip_sts_estado=1 AND a.acc_sts_estado=1 AND e.dep_id!=5
                ORDER BY a.acc_id;";
          $r = resp_simdim($q);
          foreach($r as $a){
        ?>
        <tr>
          <td style="font-size: 0.9rem;"><strong><?php echo htmlspecialchars($a['doc_txt_nombre']); ?></strong></td>
          <td style="font-size: 0.9rem;" align="center"><?php echo htmlspecialchars($a['dep_txt_nombre']); ?></td>
          <td style="font-size: 0.9rem;" align="center"><?php echo htmlspecialchars($a['cat_txt_nombre']); ?></td>
          <td style="font-size: 0.9rem;" align="center"><?php echo htmlspecialchars($a['tip_txt_nombre']); ?></td>
          <td style="font-size: 0.9rem;" align="center">
            <?php if($a['acc_id']==1){ ?>
              <div class="icon-container">
                <span class="tooltip-text">Ver documento</span>
                <a href="visualizador.php?MG$=ABC2880<?php echo htmlspecialchars($a['acc_txt_ruta'].$a['doc_id']); ?>?809ID=465">
                  <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                    <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
                    <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
                  </svg>
                </a>
              </div>
            <?php } elseif($a['acc_id']==2){ ?>
              <div class="icon-container">
                <span class="tooltip-text">Descargar documento</span>
                <a href="?descargar=<?php echo $a['doc_id']; ?>">
                  <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-cloud-download-fill" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M8 0a5.53 5.53 0 0 0-3.594 1.342c-.766.66-1.321 1.52-1.464 2.383C1.266 4.095 0 5.555 0 7.318 0 9.366 1.708 11 3.781 11H7.5V5.5a.5.5 0 0 1 1 0V11h4.188C14.502 11 16 9.57 16 7.773c0-1.636-1.242-2.969-2.834-3.194C12.923 1.999 10.69 0 8 0m-.354 15.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 14.293V11h-1v3.293l-2.146-2.147a.5.5 0 0 0-.708.708z"/>
                  </svg>
                </a>
              </div>
            <?php } ?>
          </td>
        </tr>
        <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
    
    <div style="margin-top: 2rem; text-align: center;">
      <div class="icon-container">
        <span class="tooltip-text">Abrir carpeta</span>
        <button class="btn btn-outline-primary d-inline-flex align-items-center gap-2" onclick="abrirCarpeta()">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-folder-fill" viewBox="0 0 16 16">
            <path d="M9.828 4a.5.5 0 0 1 .354.146l1.5 1.5A.5.5 0 0 1 11.5 6H14a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h5.172a.5.5 0 0 1 .354.146l1.5 1.5A.5.5 0 0 1 9.5 3H14a2 2 0 0 1 2 2v1H9.828z"/>
          </svg>
          TALENTO HUMANO
        </button>
      </div>
    </div>

  </main>

  <!-- DataTables JS desde CDN -->
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

  <script>
  function abrirCarpeta() {
      // Redirige a una vista de exploración de carpetas (puedes crear una página tipo file browser)
      window.location.href = "explorador_carpetas_th.php";
    }

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