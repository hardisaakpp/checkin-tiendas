<?php
$basePath = '/storage/assets/doc/descarga/TH';
$baseRealPath = realpath($basePath);

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$relPath = isset($_GET['path']) ? trim($_GET['path']) : '';
$currentPath = realpath($basePath . '/' . $relPath);

// Validación de seguridad
if ($currentPath === false || strpos($currentPath, $baseRealPath) !== 0) {
    die("Acceso no permitido");
}

// Función para buscar recursivamente desde la raíz
function buscarRecursivo($path, $searchTerm, $baseRealPath) {
    $resultados = [];
    $items = scandir($path);
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') continue;
        $fullPath = realpath($path . DIRECTORY_SEPARATOR . $item);
        if ($fullPath === false) continue;

        $esDirectorio = is_dir($fullPath);
        $rutaRelativa = ltrim(str_replace($baseRealPath, '', $fullPath), '/');

        if (stripos($item, $searchTerm) !== false) {
            $resultados[] = [
                'nombre' => $item,
                'es_directorio' => $esDirectorio,
                'ruta_relativa' => $rutaRelativa,
                'ruta_completa' => $fullPath
            ];
        }

        if ($esDirectorio) {
            $subresultados = buscarRecursivo($fullPath, $searchTerm, $baseRealPath);
            $resultados = array_merge($resultados, $subresultados);
        }
    }
    return $resultados;
}

// Función para obtener ícono según extensión
function obtenerIconoPorExtension($nombreArchivo) {
    $ext = strtolower(pathinfo($nombreArchivo, PATHINFO_EXTENSION));
    $iconos = [
        'pdf'  => 'https://cdn-icons-png.flaticon.com/512/337/337946.png',
        'doc'  => 'https://cdn-icons-png.flaticon.com/512/281/281760.png',
        'docx' => 'https://cdn-icons-png.flaticon.com/512/281/281760.png',
        'xls'  => 'https://cdn-icons-png.flaticon.com/512/732/732220.png',
        'xlsx' => 'https://cdn-icons-png.flaticon.com/512/732/732220.png',
        'jpg'  => 'https://cdn-icons-png.flaticon.com/512/136/136524.png',
        'jpeg' => 'https://cdn-icons-png.flaticon.com/512/136/136524.png',
        'png'  => 'https://cdn-icons-png.flaticon.com/512/136/136524.png',
        'gif'  => 'https://cdn-icons-png.flaticon.com/512/136/136524.png',
        'zip'  => 'https://cdn-icons-png.flaticon.com/512/888/888879.png',
        'rar'  => 'https://cdn-icons-png.flaticon.com/512/888/888879.png',
        'txt'  => 'https://cdn-icons-png.flaticon.com/512/3022/3022255.png',
        
        // Audio
        'mp3' => 'https://cdn-icons-png.flaticon.com/512/727/727245.png',
        'wav' => 'https://cdn-icons-png.flaticon.com/512/727/727245.png',
        'ogg' => 'https://cdn-icons-png.flaticon.com/512/727/727245.png',

        // Video
        'mp4' => 'https://cdn-icons-png.flaticon.com/512/727/727248.png',
        'avi' => 'https://cdn-icons-png.flaticon.com/512/727/727248.png',
        'mov' => 'https://cdn-icons-png.flaticon.com/512/727/727248.png',
        'mkv' => 'https://cdn-icons-png.flaticon.com/512/727/727248.png',
        'webm'=> 'https://cdn-icons-png.flaticon.com/512/727/727248.png',

        // Otros
        'default' => 'https://cdn-icons-png.flaticon.com/512/833/833524.png',
    ];

    return $iconos[$ext] ?? $iconos['default'];
}

$items = [];

if ($search !== '') {
    $items = buscarRecursivo($baseRealPath, $search, $baseRealPath);
} else {
    $items = [];
    foreach (scandir($currentPath) as $item) {
        if ($item === '.' || $item === '..') continue;
        $fullPath = realpath($currentPath . DIRECTORY_SEPARATOR . $item);
        if ($fullPath === false) continue;
        $items[] = [
            'nombre' => $item,
            'es_directorio' => is_dir($fullPath),
            'ruta_relativa' => ltrim(str_replace($baseRealPath, '', $fullPath), '/'),
            'ruta_completa' => $fullPath
        ];
    }
}
?>
<?php include_once 'header.php'; ?>
<style>
    .folder-icon, .file-icon {
        width: 24px;
        margin-right: 8px;
    }
    .breadcrumb {
        background-color: #f8f9fa;
    }
    form.search-form {
        max-width: 400px;
        margin-bottom: 20px;
    }
</style>
<body class="p-4">
<div class="container">
    <h3>📁 Explorador de Carpetas - TH</h3>

    <!-- Formulario de búsqueda -->
    <form class="search-form d-flex" method="get">
        <input type="text" name="search" class="form-control me-2" placeholder="Buscar archivo o carpeta..." value="<?php echo htmlspecialchars($search); ?>">
        <button class="btn btn-outline-secondary" type="submit">Buscar</button>
        <?php if ($search): ?>
            <a href="explorador_carpetas_th.php" class="btn btn-outline-danger ms-2">Limpiar</a>
        <?php endif; ?>
    </form>

    <!-- Breadcrumb -->
    <?php if (!$search): ?>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="?">Raíz</a></li>
                <?php
                $pathParts = explode('/', trim($relPath, '/'));
                $pathSoFar = '';
                foreach ($pathParts as $part) {
                    if ($part === '') continue;
                    $pathSoFar .= $part . '/';
                    echo '<li class="breadcrumb-item"><a href="?path=' . urlencode($pathSoFar) . '">' . htmlspecialchars($part) . '</a></li>';
                }
                ?>
            </ol>
        </nav>
    <?php endif; ?>

    <!-- Tabla de resultados -->
    <table class="table table-hover table-sm">
        <thead class="table-light">
        <tr>
            <th>Nombre</th>
            <th>Ruta</th>
            <th>Tipo</th>
            <th>Acción</th>
        </tr>
        </thead>
        <tbody>
        <?php if (empty($items)): ?>
            <tr><td colspan="4" class="text-center text-muted">No se encontraron resultados.</td></tr>
        <?php else: ?>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td>
                        <?php if ($item['es_directorio']): ?>
                            <img src="https://cdn-icons-png.flaticon.com/512/715/715676.png" class="folder-icon">
                            <?php echo htmlspecialchars($item['nombre']); ?>
                        <?php else: ?>
                            <?php $isPdf = strtolower(pathinfo($item['nombre'], PATHINFO_EXTENSION)) === 'pdf'; ?>
                            <img src="<?php echo obtenerIconoPorExtension($item['nombre']); ?>"
                                class="file-icon"
                                style="cursor: <?php echo $isPdf ? 'pointer' : 'default'; ?>"
                                onclick="<?php if ($isPdf) echo "openPDFModal('ver_pdf_th.php?file=" . urlencode($item['ruta_relativa']) . "', '" . htmlspecialchars($item['nombre']) . "')"; ?>">
                            <?php if ($isPdf): ?>
                                <span style="cursor:pointer; color:#007bff; text-decoration:underline;"
                                    onclick="openPDFModal('ver_pdf_th.php?file=<?php echo urlencode($item['ruta_relativa']); ?>', '<?php echo htmlspecialchars($item['nombre']); ?>')">
                                    <?php echo htmlspecialchars($item['nombre']); ?>
                                </span>
                            <?php else: ?>
                                <?php echo htmlspecialchars($item['nombre']); ?>
                            <?php endif; ?>
                        <?php endif; ?>
                    </td>

                    <td class="text-muted"><?php echo htmlspecialchars($item['ruta_relativa']); ?></td>
                    <td><?php echo $item['es_directorio'] ? 'Carpeta' : 'Archivo'; ?></td>
                    <td>
                        <?php if ($item['es_directorio']): ?>
                            <a class="btn btn-sm btn-outline-primary" href="?path=<?php echo urlencode($item['ruta_relativa']); ?>">Abrir</a>
                        <?php else: ?>
                            <a class="btn btn-sm btn-success" href="descargar_archivo_th.php?file=<?php echo urlencode($item['ruta_relativa']); ?>">Descargar</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal Visor PDF -->
<div id="pdfModal" class="modal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background-color: rgba(0,0,0,0.8); z-index:1000;">
  <div style="position:relative; width:90%; height:90%; margin:5% auto; background:#fff; border-radius:8px; overflow:hidden;">
    <div style="display:flex; justify-content:space-between; align-items:center; background:#343a40; color:#fff; padding:10px;">
      <span id="modalFilename" style="font-weight:bold;"></span>
      <div>
        <button onclick="printPDF()" class="btn btn-sm btn-light me-2">🖨 Imprimir</button>
        <a id="downloadLink" class="btn btn-sm btn-light me-2" download target="_blank">⬇ Descargar</a>
        <button onclick="closeModal()" class="btn btn-sm btn-danger">✖ Cerrar</button>
      </div>
    </div>
    <iframe id="pdfViewer" style="width:100%; height:100%;" frameborder="0"></iframe>
  </div>
</div>

<script>
    function openPDFModal(pdfUrl, filename) {
        document.getElementById('pdfViewer').src = pdfUrl;
        document.getElementById('downloadLink').href = pdfUrl;
        document.getElementById('modalFilename').innerText = filename;
        document.getElementById('pdfModal').style.display = 'block';
    }

    function closeModal() {
        document.getElementById('pdfModal').style.display = 'none';
        document.getElementById('pdfViewer').src = '';
    }

    function printPDF() {
        const iframe = document.getElementById('pdfViewer');
        iframe.contentWindow.focus();
        iframe.contentWindow.print();
    }
</script>



</body>
<?php include_once 'footer.php'; ?>
