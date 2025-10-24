<?php
if ($argc < 2) {
    echo "Uso: php reporte_ventas_byl.php [CODIGO_ALMACEN] [FECHA_OPCIONAL]\n";
    echo "Ejemplos:\n";
    echo "  php reporte_ventas_byl.php BYL-QSN\n";
    echo "  php reporte_ventas_byl.php BYL-QSN 2025-10-15\n";
    echo "Almacenes válidos: BYL-SCA, BYL-SMA, BYL-QSN\n";
    exit(1);
}

require_once(__DIR__ . '/../cx/bd_scs.php');
date_default_timezone_set('America/Guayaquil');

$whsCode = strtoupper($argv[1]);
$fechaConsulta = isset($argv[2]) ? $argv[2] : date('Y-m-d');

// Mapeo de tiendas (extendido del archivo original)
$mapaTiendas = [
    'BYL-SCA' => ['codigo' => 'EC04', 'nombre' => 'Santo Domingo'],
    'BYL-SMA' => ['codigo' => 'EC05', 'nombre' => 'Samborondón'],
    'BYL-QSN' => ['codigo' => 'EC06', 'nombre' => 'Quinsaloma'],
];

if (!isset($mapaTiendas[$whsCode])) {
    echo "Código de almacén no válido: $whsCode\n";
    echo "Almacenes válidos: " . implode(', ', array_keys($mapaTiendas)) . "\n";
    exit(1);
}

$tienda = $mapaTiendas[$whsCode];

// Usar la función del archivo original pero modificada para fechas
function generarConsultaVentasReporte(string $whsCode, string $fecha): string {
    $whsCodeForSql = str_replace("'", "''", $whsCode);
    return "
        SELECT 
            Z.\"channel_order_id\",
            Z.\"order_date\" AS order_date,
            Z.\"return_id\",
            Z.\"ean\",
            CAST(Z.\"quantity\" AS INTEGER) AS quantity,
            CAST(Z.\"amount_vat_lcy\" AS DECIMAL(18,2)) AS amount_vat_lcy,
            CAST(Z.\"amount_no_vat_lcy\" AS DECIMAL(18,2)) AS amount_no_vat_lcy
        FROM OPENQUERY([MYHANADB], '
            SELECT * FROM (
                --------------------------------------------------------------------
                -- FACTURAS (OINV)
                --------------------------------------------------------------------
                SELECT
                    (T0.\"U_SER_EST\" || ''-'' || T0.\"U_SER_PE\" || RIGHT(''000000000'' || T0.\"FolioNum\", 9)) AS \"channel_order_id\",
                    TO_VARCHAR(T0.\"DocDate\", ''DD/MM/YYYY'') AS \"order_date\",
                    (T0.\"U_SER_EST\" || ''-'' || T0.\"U_SER_PE\" || ''-'' || RIGHT(''000000000'' || T0.\"U_NUM_FAC_REL\", 9)) AS \"return_id\",
                    T4.\"CodeBars\" AS \"ean\",
                    T1.\"Quantity\" AS \"quantity\",

                    TO_VARCHAR(SUM((T1.\"PriceBefDi\" * T1.\"Quantity\") - 
                                    ((T1.\"PriceBefDi\" * T1.\"DiscPrcnt\") / 100) * T1.\"Quantity\" + 
                                    (T1.\"VatPrcnt\" * ((T1.\"PriceBefDi\" * T1.\"Quantity\") - 
                                    ((T1.\"PriceBefDi\" * T1.\"DiscPrcnt\") / 100) * T1.\"Quantity\")) / 100)) 
                    AS \"amount_vat_lcy\",

                    TO_VARCHAR(SUM((T1.\"PriceBefDi\" * T1.\"Quantity\") - 
                                    ((T1.\"PriceBefDi\" * T1.\"DiscPrcnt\") / 100) * T1.\"Quantity\"))
                    AS \"amount_no_vat_lcy\"
                                
                FROM SBO_MABEL_PROD.OINV T0 
                INNER JOIN SBO_MABEL_PROD.INV1 T1 ON T0.\"DocEntry\" = T1.\"DocEntry\" 
                INNER JOIN SBO_MABEL_PROD.OSLP T2 ON T0.\"SlpCode\" = T2.\"SlpCode\" 
                INNER JOIN SBO_MABEL_PROD.OWHS T3 ON T1.\"WhsCode\" = T3.\"WhsCode\" 
                INNER JOIN SBO_MABEL_PROD.OITM T4 ON T1.\"ItemCode\" = T4.\"ItemCode\"
                WHERE T3.\"WhsCode\" = ''" . $whsCodeForSql . "'' 
                  AND CAST(T0.\"DocDate\" AS DATE) = ''" . $fecha . "''
                GROUP BY T0.\"DocDate\", T0.\"U_SER_EST\", T0.\"U_SER_PE\", T0.\"FolioNum\", T0.\"U_NUM_FAC_REL\",
                         T4.\"CodeBars\", T1.\"Quantity\", T0.\"DocEntry\", T4.\"ItemCode\", T3.\"WhsCode\"

                UNION ALL
                --------------------------------------------------------------------
                -- NOTAS DE CRÉDITO (ORIN)
                --------------------------------------------------------------------
                SELECT
                    (T0.\"U_SER_EST\" || ''-'' || T0.\"U_SER_PE\" || RIGHT(''000000000'' || T0.\"FolioNum\", 9)) AS \"channel_order_id\",
                    TO_VARCHAR(T0.\"DocDate\", ''DD/MM/YYYY'') AS \"order_date\",
                    (T0.\"U_SER_EST\" || ''-'' || T0.\"U_SER_PE\" || ''-'' || RIGHT(''000000000'' || T0.\"U_NUM_FAC_REL\", 9)) AS \"return_id\",
                    T4.\"CodeBars\" AS \"ean\",
                    -T1.\"Quantity\" AS \"quantity\",

                    TO_VARCHAR(-SUM((T1.\"PriceBefDi\" * T1.\"Quantity\") - 
                                     ((T1.\"PriceBefDi\" * T1.\"DiscPrcnt\") / 100) * T1.\"Quantity\" + 
                                     (T1.\"VatPrcnt\" * ((T1.\"PriceBefDi\" * T1.\"Quantity\") - 
                                     ((T1.\"PriceBefDi\" * T1.\"DiscPrcnt\") / 100) * T1.\"Quantity\")) / 100)) 
                    AS \"amount_vat_lcy\",

                    TO_VARCHAR(-SUM((T1.\"PriceBefDi\" * T1.\"Quantity\") - 
                                     ((T1.\"PriceBefDi\" * T1.\"DiscPrcnt\") / 100) * T1.\"Quantity\"))
                    AS \"amount_no_vat_lcy\"
                                
                FROM SBO_MABEL_PROD.ORIN T0 
                INNER JOIN SBO_MABEL_PROD.RIN1 T1 ON T0.\"DocEntry\" = T1.\"DocEntry\" 
                INNER JOIN SBO_MABEL_PROD.OSLP T2 ON T0.\"SlpCode\" = T2.\"SlpCode\" 
                INNER JOIN SBO_MABEL_PROD.OWHS T3 ON T1.\"WhsCode\" = T3.\"WhsCode\" 
                INNER JOIN SBO_MABEL_PROD.OITM T4 ON T1.\"ItemCode\" = T4.\"ItemCode\"
                WHERE T3.\"WhsCode\" = ''" . $whsCodeForSql . "'' 
                  AND CAST(T0.\"DocDate\" AS DATE) = ''" . $fecha . "''
                GROUP BY T0.\"DocDate\", T0.\"U_SER_EST\", T0.\"U_SER_PE\", T0.\"FolioNum\", T0.\"U_NUM_FAC_REL\",
                         T4.\"CodeBars\", T1.\"Quantity\", T0.\"DocEntry\", T4.\"ItemCode\", T3.\"WhsCode\"
            ) Z
            ORDER BY Z.\"channel_order_id\" ASC
        ') AS Z
    ";
}

try {
    echo "=== GENERANDO REPORTE CSV VENTAS BYL ===\n";
    echo "Almacén: $whsCode ({$tienda['nombre']})\n";
    echo "Fecha: $fechaConsulta\n";
    echo "Procesando...\n\n";

    // Ejecutar consulta (misma del archivo original)
    $query = generarConsultaVentasReporte($whsCode, $fechaConsulta);
    $ventas = resp_simdim($query);
    
    if (!$ventas || count($ventas) == 0) {
        echo "⚠️  No se encontraron datos para la fecha especificada.\n";
        echo "Posibles causas:\n";
        echo "• No hay ventas registradas para esa fecha\n";
        echo "• Error de conexión a la base de datos\n";
        echo "• Problema con bd_scs.php (revisar línea 86)\n";
        exit(0);
    }
    
    // Crear archivo CSV
    $fechaArchivo = str_replace('-', '', $fechaConsulta);
    $nombreArchivo = "reporte_ventas_{$whsCode}_{$fechaArchivo}.csv";
    $rutaCompleta = __DIR__ . "/" . $nombreArchivo;
    
    $archivo = fopen($rutaCompleta, 'w');
    
    // BOM para UTF-8 (para Excel)
    fwrite($archivo, "\xEF\xBB\xBF");
    
    // Encabezados
    fputcsv($archivo, [
        'TIENDA',
        'CODIGO_TIENDA', 
        'FECHA_REPORTE',
        'ID_ORDEN',
        'FECHA_VENTA',
        'ID_RETORNO',
        'EAN',
        'CANTIDAD',
        'MONTO_CON_IVA',
        'MONTO_SIN_IVA',
        'TIPO'
    ], ';');
    
    // Variables para resumen
    $totalRegistros = 0;
    $totalVentas = 0;
    $totalDevoluciones = 0;
    $montoTotalConIVA = 0;
    $montoTotalSinIVA = 0;
    
    // Escribir datos
    foreach ($ventas as $venta) {
        $tipo = $venta['quantity'] > 0 ? 'VENTA' : 'DEVOLUCION';
        
        fputcsv($archivo, [
            $tienda['nombre'],
            $tienda['codigo'],
            $fechaConsulta,
            $venta['channel_order_id'],
            $venta['order_date'],
            $venta['return_id'],
            $venta['ean'],
            $venta['quantity'],
            number_format($venta['amount_vat_lcy'], 2, '.', ''),
            number_format($venta['amount_no_vat_lcy'], 2, '.', ''),
            $tipo
        ], ';');
        
        $totalRegistros++;
        if ($venta['quantity'] > 0) {
            $totalVentas++;
        } else {
            $totalDevoluciones++;
        }
        $montoTotalConIVA += floatval($venta['amount_vat_lcy']);
        $montoTotalSinIVA += floatval($venta['amount_no_vat_lcy']);
    }
    
    // Resumen
    fputcsv($archivo, [], ';');
    fputcsv($archivo, ['=== RESUMEN ==='], ';');
    fputcsv($archivo, ['Total Registros', $totalRegistros], ';');
    fputcsv($archivo, ['Ventas', $totalVentas], ';');
    fputcsv($archivo, ['Devoluciones', $totalDevoluciones], ';');
    fputcsv($archivo, ['Total Con IVA', '$' . number_format($montoTotalConIVA, 2)], ';');
    fputcsv($archivo, ['Total Sin IVA', '$' . number_format($montoTotalSinIVA, 2)], ';');
    
    fclose($archivo);
    
    echo "✅ REPORTE GENERADO EXITOSAMENTE\n";
    echo "📁 Archivo: $nombreArchivo\n";
    echo "📊 Registros: $totalRegistros\n";
    echo "🛒 Ventas: $totalVentas | 🔄 Devoluciones: $totalDevoluciones\n";
    echo "💰 Total: $" . number_format($montoTotalConIVA, 2) . "\n";
    echo "📍 Ubicación: $rutaCompleta\n";
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "Verifique:\n";
    echo "• Conexión a base de datos\n";
    echo "• Archivo bd_scs.php\n";
    echo "• Formato de fecha (YYYY-MM-DD)\n";
    exit(1);
}
?>