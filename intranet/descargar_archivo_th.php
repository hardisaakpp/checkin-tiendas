<?php
error_log("Parámetro recibido: " . $fileParam);
error_log("Ruta completa construida: " . $fullPath);
$basePath = '/storage/assets/doc/descarga/TH';
$baseRealPath = realpath($basePath);

$fileParam = isset($_GET['file']) ? $_GET['file'] : '';
$fullPath = realpath($basePath . '/' . $fileParam);

// Validar seguridad y existencia
if (!$fullPath || strpos($fullPath, $baseRealPath) !== 0 || !is_file($fullPath)) {
    die("Archivo no permitido o no encontrado");
}

// Descargar archivo
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . basename($fullPath) . '"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($fullPath));
readfile($fullPath);
exit;
