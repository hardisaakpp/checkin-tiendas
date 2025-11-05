<?php
header('Content-Type: application/json');

include_once "../cx/bd_scs.php";

$accion = $_POST['accion'] ?? '';

try {
    if ($accion === 'crear') {
        // Validar datos requeridos
        if (!isset($_POST['dir_txt_area']) || !isset($_POST['dir_txt_colaborador']) || !isset($_POST['dir_val_extensión'])) {
            echo json_encode(["status" => "error", "message" => "Datos incompletos"]);
            exit;
        }

        $area = strtoupper(trim($_POST['dir_txt_area']));
        $colaborador = strtoupper(trim($_POST['dir_txt_colaborador']));
        $extension = trim($_POST['dir_val_extensión']);
        // Asegurar que los nuevos registros siempre se creen como activos
        $estado = 1; // Siempre activo para nuevos registros

        // Escapar comillas simples para SQL
        $area = str_replace("'", "''", $area);
        $colaborador = str_replace("'", "''", $colaborador);
        $extension = str_replace("'", "''", $extension);

        // Preparar query de inserción
        $query = "INSERT INTO plataforma_web.dbo.int_directorio 
                  (dir_txt_area, dir_txt_colaborador, dir_val_extensión, dir_sts_estado, dir_fec_registro, dir_fec_cambio)
                  VALUES ('$area', '$colaborador', '$extension', $estado, GETDATE(), GETDATE())";

        insert_data($query);

        echo json_encode([
            "status" => "success",
            "message" => "Registro creado correctamente"
        ]);

    } elseif ($accion === 'editar') {
        // Validar datos requeridos
        if (!isset($_POST['dir_id']) || !isset($_POST['dir_txt_area']) || !isset($_POST['dir_txt_colaborador']) || !isset($_POST['dir_val_extensión'])) {
            echo json_encode(["status" => "error", "message" => "Datos incompletos"]);
            exit;
        }

        $id = intval($_POST['dir_id']);
        $area = strtoupper(trim($_POST['dir_txt_area']));
        $colaborador = strtoupper(trim($_POST['dir_txt_colaborador']));
        $extension = trim($_POST['dir_val_extensión']);
        $estado = isset($_POST['dir_sts_estado']) ? intval($_POST['dir_sts_estado']) : 1;

        // Escapar comillas simples para SQL
        $area = str_replace("'", "''", $area);
        $colaborador = str_replace("'", "''", $colaborador);
        $extension = str_replace("'", "''", $extension);

        // Preparar query de actualización
        $query = "UPDATE plataforma_web.dbo.int_directorio 
                  SET dir_txt_area = '$area',
                      dir_txt_colaborador = '$colaborador',
                      dir_val_extensión = '$extension',
                      dir_sts_estado = $estado,
                      dir_fec_cambio = GETDATE()
                  WHERE dir_id = $id";

        insert_data($query);

        echo json_encode([
            "status" => "success",
            "message" => "Registro actualizado correctamente"
        ]);

    } elseif ($accion === 'eliminar') {
        // Validar ID
        if (!isset($_POST['dir_id'])) {
            echo json_encode(["status" => "error", "message" => "ID no válido"]);
            exit;
        }

        $id = intval($_POST['dir_id']);

        // Preparar query de eliminación física (DELETE)
        $query = "DELETE FROM plataforma_web.dbo.int_directorio 
                  WHERE dir_id = $id";

        try {
            insert_data($query);
            
            echo json_encode([
                "status" => "success",
                "message" => "Registro eliminado correctamente"
            ]);
        } catch (Exception $e) {
            error_log("Error al eliminar: " . $e->getMessage());
            echo json_encode([
                "status" => "error",
                "message" => "Error al eliminar: " . $e->getMessage()
            ]);
        }

    } else {
        echo json_encode(["status" => "error", "message" => "Acción no válida"]);
    }

} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => "Error: " . $e->getMessage()]);
}
?>

