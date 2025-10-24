<?php  
if ($argc < 2) {
    echo "Debe proporcionar el parámetro de ejecución (Ej: run_historial o run_dm)\n";
    exit(1);
}

require_once(__DIR__ . '/../cx/bd_scs_211.php'); // conexión DB
date_default_timezone_set('America/Guayaquil');

$param = $argv[1] ?? null;
$logFile = '/storage/tareas/syn_put_sc_repositorio.log';
$msg = '';

if ($param === "run_historial") {
    resp_oneval("EXEC TEMPORALES.transicion.SP_Insertar_Modelo_Abastecimiento_Historial;");
    $msg = "SP TEMPORALES.transicion.SP_Insertar_Modelo_Abastecimiento_Historial ejecutado con éxito";
} elseif ($param === "run_dm") {
    resp_oneval("EXEC [MODULOS_SC].[reposicion].[SP_Insertar_DM_Temp_Procesados];");
    resp_oneval("EXEC [MODULOS_SC].[reposicion].[SP_Insertar_DM_Procesados];");
    resp_oneval("EXEC [MODULOS_SC].[reposicion].[SP_Poblar_ProcesadosCache];");
    $msg = "SP MODULOS_SC.reposicion.SP_Insertar_DM_Procesados y MODULOS_SC.reposicion.SP_Poblar_ProcesadosCache ejecutado con éxito";
} else {
    echo "Parámetro inválido. Use: run_historial o run_dm\n";
    exit(1);
}

// Registrar log
$logEntry = date('Y-m-d H:i:s') . " - $msg\n";
file_put_contents($logFile, $logEntry, FILE_APPEND);

echo "✅ $msg\n";
?>