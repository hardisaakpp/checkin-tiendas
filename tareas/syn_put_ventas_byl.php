<?php  
    if ($argc < 2) {
        echo "Debe proporcionar el código de almacén (Ej: BYL-QSN)\n";
        exit(1);
    }

    require_once(__DIR__ . '/../cx/bd_scs.php'); // conexión DB
    require_once(__DIR__ . '/../cws/f_consumo_byl.php'); // consumo API
    date_default_timezone_set('America/Guayaquil');

    // Mapeo de whsCode a location_code
    $mapaTiendas = [
        'BYL-SCA' => 'EC04',
        'BYL-SMA' => 'EC05',
        'BYL-QSN' => 'EC06',
    ];

    $whsCode = strtoupper($argv[1]);

    if (!isset($mapaTiendas[$whsCode])) {
        echo "Código de almacén no válido: $whsCode\n";
        exit(1);
    }

    $location_code = $mapaTiendas[$whsCode];

  // Función interna
function generarConsultaVentas(string $whsCode, string $location_code): string {
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
                  AND CAST(T0.\"DocDate\" AS DATE) = CURRENT_DATE
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
                  AND CAST(T0.\"DocDate\" AS DATE) = CURRENT_DATE
                GROUP BY T0.\"DocDate\", T0.\"U_SER_EST\", T0.\"U_SER_PE\", T0.\"FolioNum\", T0.\"U_NUM_FAC_REL\",
                         T4.\"CodeBars\", T1.\"Quantity\", T0.\"DocEntry\", T4.\"ItemCode\", T3.\"WhsCode\"
            ) Z
            WHERE Z.\"channel_order_id\" IS NOT NULL
            ORDER BY Z.\"channel_order_id\" ASC
        ') AS Z
    ";
}

    //BETWEEN ''2025-08-07'' AND ''2025-08-07''

    
    // Generar la consulta SQL
    $query = generarConsultaVentas($whsCode, $location_code);

    // Ejecutar la consulta
    $ventas = resp_simdim($query);

    // Enviar a API remota
    consumirApiRemoto($ventas, $location_code);


    // Registrar log
    file_put_contents('/storage/tareas/syn_put_ventas_byl.log', date('Y-m-d H:i:s') . " - $whsCode procesado con éxito\n", FILE_APPEND);
?>