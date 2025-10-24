<?php
    require_once(__DIR__ . '/../cx/bd_scs.php'); // debe contener la función cx() para conectar
    require_once(__DIR__ . '/../cws/f_put_asistencias.php'); // debe contener la función generarAsistenciasJson()
    require_once('f_tareas.php');
    date_default_timezone_set('America/Guayaquil');

    $query = "
        DECLARE @hoy DATE = CAST(GETDATE() AS DATE);
        DECLARE @hace7 DATE = DATEADD(DAY, -7, @hoy);

        -- CTE: registros únicos de asistencia (por tarjeta y datetime exacto)
        WITH AsistenciasUnicas AS (
            SELECT DISTINCT
                USERID,
                Fecha = CONVERT(DATE, CHECKTIME),
                Hora = CONVERT(TIME, CHECKTIME)
            FROM ElRayoBiometricos.dbo.VistaRegistrosT
            WHERE CHECKTIME BETWEEN @hace7 AND @hoy
        )

        -- Consulta principal
        SELECT
            asesor_nombre   = k.kli_txt_nombre,
            asesor_cedula   = k.kli_txt_cedula,
            asesor_cargo    = k.kli_txt_cargo,
            asesor_correo   = '',
            fecha           = CONVERT(VARCHAR, A.Fecha, 112),
            hora            = CONVERT(VARCHAR, A.Hora, 108),
            local_nombre    = k.kli_txt_tienda,
            local_codigo    = k.id_tienda
        FROM AsistenciasUnicas A
        JOIN BDDNOMINAMABEL19..Tbl_DatosPersonales P ON A.USERID COLLATE SQL_Latin1_General_CP1_CI_AS = P.strDPe_numtarjeta COLLATE SQL_Latin1_General_CP1_CI_AS
        INNER JOIN plataforma_web.dbo.tmp_kliente AS k ON k.kli_txt_cedula COLLATE SQL_Latin1_General_CP1_CI_AS = P.strDPe_Cedula AND k.kli_sts_estado=1
        WHERE
            k.kli_txt_cargo IN ('ASESOR DE VENTAS', 'ASESOR VARIOS')
            AND k.id_empresa = 1
            AND k.id_tienda IS NOT NULL
        ORDER BY
            k.id_tienda,
            k.kli_txt_cedula,
            A.Fecha,
            A.Hora;
    ";
    // Ejecutar la consulta y obtener array
    $asistencias = resp_simdim($query);
    // Enviar las asistencias a Kliente
    enviarAsistenciasKliente($asistencias);
    file_put_contents('/storage/tareas/syn_put_asistencias.log',date('Y-m-d H:i:s') . " - éxito\n",FILE_APPEND);
?>