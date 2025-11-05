<?php
/**
 * Sistema de autenticación para el módulo de administración
 * Funciones reutilizables para validar sesiones y permisos
 */

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Conectar a la base de datos
 * Usa la misma configuración que el resto del proyecto
 */
function getDBConnection() {
    $contraseña = "Datos.22";
    $usuario = "consultas";
    $nombreBaseDeDatos = "plataforma_web";
    $rutaServidor = "10.10.100.12";
    
    try {
        $db = new PDO("sqlsrv:server=$rutaServidor;TrustServerCertificate=TRUE;database=$nombreBaseDeDatos", 
                      $usuario, $contraseña);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db;
    } catch (Exception $e) {
        error_log("Error de conexión a BD: " . $e->getMessage());
        return null;
    }
}

/**
 * Verificar si un usuario está autenticado
 * @param string $rolRequerido Rol requerido (opcional). Si es null, solo verifica autenticación
 * @return bool
 */
function requireAuth($rolRequerido = null) {
    // Si no hay sesión de autenticación, redirigir al login
    if (!isset($_SESSION['admin_autenticado']) || $_SESSION['admin_autenticado'] !== true) {
        $redirect = $_SERVER['PHP_SELF'];
        if (isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING'])) {
            $redirect .= '?' . $_SERVER['QUERY_STRING'];
        }
        header('Location: login_admin.php?redirect=' . urlencode($redirect));
        exit;
    }
    
    // Si se especifica un rol requerido, verificarlo
    if ($rolRequerido !== null && isset($_SESSION['usuario_rol'])) {
        $rolUsuario = strtolower($_SESSION['usuario_rol']);
        $rolRequerido = strtolower($rolRequerido);
        
        // Los administradores tienen acceso a todo
        if ($rolUsuario !== 'admin' && $rolUsuario !== $rolRequerido) {
            die("Acceso denegado. No tiene permisos suficientes para acceder a esta sección.");
        }
    }
    
    return true;
}

/**
 * Autenticar un usuario
 * @param string $usuario
 * @param string $password
 * @return bool|array Retorna false si falla, o array con datos del usuario si es exitoso
 */
function login($usuario, $password) {
    $db = getDBConnection();
    if (!$db) {
        return false;
    }
    
    try {
        $query = "SELECT usu_id, usu_txt_usuario, usu_txt_password, usu_txt_nombre, usu_txt_rol 
                  FROM plataforma_web.dbo.int_usuarios 
                  WHERE usu_txt_usuario = ? AND usu_sts_activo = 1";
        
        $stmt = $db->prepare($query);
        $stmt->execute([$usuario]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['usu_txt_password'])) {
            // Actualizar último acceso
            $updateQuery = "UPDATE plataforma_web.dbo.int_usuarios 
                           SET usu_fec_ultimo_acceso = GETDATE() 
                           WHERE usu_id = ?";
            $updateStmt = $db->prepare($updateQuery);
            $updateStmt->execute([$user['usu_id']]);
            
            // Establecer variables de sesión
            $_SESSION['admin_autenticado'] = true;
            $_SESSION['usuario_id'] = $user['usu_id'];
            $_SESSION['usuario_nombre'] = $user['usu_txt_nombre'];
            $_SESSION['usuario_rol'] = $user['usu_txt_rol'];
            $_SESSION['usuario_login'] = time();
            
            return $user;
        }
        
        return false;
    } catch (Exception $e) {
        error_log("Error en login: " . $e->getMessage());
        return false;
    }
}

/**
 * Cerrar sesión
 */
function logout() {
    $_SESSION = array();
    
    // Destruir la cookie de sesión si existe
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    session_destroy();
}

/**
 * Verificar si el usuario actual tiene un rol específico
 * @param string $rol
 * @return bool
 */
function tieneRol($rol) {
    if (!isset($_SESSION['usuario_rol'])) {
        return false;
    }
    
    $rolUsuario = strtolower($_SESSION['usuario_rol']);
    $rol = strtolower($rol);
    
    // Los administradores tienen todos los roles
    return ($rolUsuario === 'admin' || $rolUsuario === $rol);
}

/**
 * Obtener información del usuario actual
 * @return array|null
 */
function getUsuarioActual() {
    if (!isset($_SESSION['admin_autenticado']) || $_SESSION['admin_autenticado'] !== true) {
        return null;
    }
    
    return [
        'id' => $_SESSION['usuario_id'] ?? null,
        'nombre' => $_SESSION['usuario_nombre'] ?? null,
        'rol' => $_SESSION['usuario_rol'] ?? null,
        'login' => $_SESSION['usuario_login'] ?? null
    ];
}


