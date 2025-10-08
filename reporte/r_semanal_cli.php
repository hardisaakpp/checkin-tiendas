<?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    require '../assets/lib/vendor/autoload.php';
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

    include_once "../cx/bd_scs.php";  
    $q = "SELECT a.nombre2 AS nombre_tienda,
                c.tnd_id AS codigo_tienda,
                c.chk_txt_nombre AS nombre_cliente,
                c.chk_txt_apellido AS apellido_cliente,
                g.gen_txt_nombre AS genero_cliente,
                CASE 
                    WHEN c.chk_val_tip_iden = 1 THEN 'CEDULA'
                    ELSE 'PASSAPORTE'
                END AS tipo_identificacion,
                c.chk_txt_iden AS numero_identificacion,
                c.chk_txt_correo AS correo_cliente,
                c.chk_txt_celular AS telefono_cliente,
                c.chk_fec_registro AS fecha_registro
            FROM plataforma_web.dbo.chk_cliente AS c
				INNER JOIN plataforma_web.dbo.chk_genero AS g ON g.gen_id=c.gen_id
            INNER JOIN STORECONTROL.dbo.Almacen AS a ON a.id=c.tnd_id
            WHERE c.chk_sts_estado=1";
    $r = resp_simdim($q);

    // ✅ Crear nuevo objeto de Excel
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // ✅ Encabezados para las columnas
    $encabezados = [
        'Nombre Tienda',
        'Código Tienda',
        'Nombre Cliente',
        'Apellido Cliente',
        'Género Cliente',
        'Tipo Identificación',
        'Número Identificación',
        'Correo Cliente',
        'Teléfono Cliente',
        'Fecha Registro'
    ];
    $sheet->fromArray($encabezados, NULL, 'A1');

    // ✅ Insertar los datos de la consulta en las filas
    $fila = 2;  // Iniciar desde la segunda fila (la primera es para encabezados)
    foreach ($r as $row) {
        $sheet->fromArray(array_values($row), NULL, 'A' . $fila++);
    }

    // ✅ Enviar el archivo Excel al navegador
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    $fecha = date('Y-m-d'); // Formato: 2025-05-21
    header("Content-Disposition: attachment; filename=\"clientes_qr_$fecha.xlsx\"");
    header('Cache-Control: max-age=0');

    // Guardar el archivo Excel en el navegador
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
?>
