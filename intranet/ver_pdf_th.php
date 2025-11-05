<?php
$basePath = '/storage/assets/doc/descarga/TH';
$baseRealPath = realpath($basePath);

$fileRel = isset($_GET['file']) ? $_GET['file'] : '';
$filePath = realpath($basePath . '/' . $fileRel);

if (!$filePath || strpos($filePath, $baseRealPath) !== 0 || !file_exists($filePath)) {
    http_response_code(404);
    exit('Archivo no encontrado o acceso denegado.');
}

header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="' . basename($filePath) . '"');
header('Content-Length: ' . filesize($filePath));
readfile($filePath);
exit;
?>
