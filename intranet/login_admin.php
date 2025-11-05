<?php
/**
 * Página de login para acceso administrativo
 */
require_once 'auth.php';

// Si ya está autenticado, redirigir
if (isset($_SESSION['admin_autenticado']) && $_SESSION['admin_autenticado'] === true) {
    $redirect = $_GET['redirect'] ?? 'admin_directorio.php';
    header('Location: ' . $redirect);
    exit;
}

$error = '';
$mensaje = '';

// Procesar login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($usuario) || empty($password)) {
        $error = 'Por favor complete todos los campos';
    } else {
        $resultado = login($usuario, $password);
        
        if ($resultado) {
            // Login exitoso, redirigir
            $redirect = $_GET['redirect'] ?? 'admin_directorio.php';
            header('Location: ' . $redirect);
            exit;
        } else {
            $error = 'Usuario o contraseña incorrectos';
        }
    }
}

// Si hay un parámetro de logout en la URL
if (isset($_GET['logout']) && $_GET['logout'] === '1') {
    logout();
    $mensaje = 'Sesión cerrada correctamente';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Administrativo - Intranet Mabel Group</title>
    <link href="../assets/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }
        .login-card {
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            border-radius: 15px;
            overflow: hidden;
            max-width: 450px;
            width: 100%;
        }
        .login-header {
            background: linear-gradient(135deg, #64b5f6 0%, #1976d2 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .login-header h4 {
            margin-bottom: 0.5rem;
            font-weight: 600;
        }
        .login-header small {
            opacity: 0.9;
            font-size: 0.9rem;
        }
        .login-body {
            background: white;
            padding: 2rem;
        }
        .form-control-lg {
            border-radius: 8px;
            padding: 0.75rem 1rem;
        }
        .btn-login {
            background: linear-gradient(135deg, #64b5f6 0%, #1976d2 100%);
            border: none;
            padding: 0.75rem;
            border-radius: 8px;
            font-weight: 600;
            transition: transform 0.2s;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(100, 181, 246, 0.4);
        }
        .alert {
            border-radius: 8px;
        }
        .back-link {
            color: #6c757d;
            text-decoration: none;
            transition: color 0.2s;
        }
        .back-link:hover {
            color: #495057;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card login-card border-0">
                    <div class="login-header">
                        <h4>🔐 Acceso Administrativo</h4>
                        <small>Administración de Directorio Telefónico</small>
                    </div>
                    <div class="login-body">
                        <?php if ($mensaje): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?= htmlspecialchars($mensaje) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($error): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-triangle-fill me-2" viewBox="0 0 16 16">
                                    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5m.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/>
                                </svg>
                                <?= htmlspecialchars($error) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" id="loginForm">
                            <div class="mb-3">
                                <label for="usuario" class="form-label fw-bold">Usuario:</label>
                                <input type="text" 
                                       class="form-control form-control-lg" 
                                       id="usuario" 
                                       name="usuario" 
                                       required 
                                       autofocus
                                       autocomplete="username"
                                       placeholder="Ingrese su usuario"
                                       value="<?= htmlspecialchars($_POST['usuario'] ?? '') ?>">
                            </div>
                            
                            <div class="mb-4">
                                <label for="password" class="form-label fw-bold">Contraseña:</label>
                                <input type="password" 
                                       class="form-control form-control-lg" 
                                       id="password" 
                                       name="password" 
                                       required
                                       autocomplete="current-password"
                                       placeholder="Ingrese su contraseña">
                            </div>
                            
                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary btn-lg btn-login text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-box-arrow-in-right me-2" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M6 3.5a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-2a.5.5 0 0 0-1 0v2A1.5 1.5 0 0 0 6.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-8A1.5 1.5 0 0 0 5 3.5v2a.5.5 0 0 0 1 0z"/>
                                        <path fill-rule="evenodd" d="M11.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H1.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z"/>
                                    </svg>
                                    Iniciar Sesión
                                </button>
                            </div>
                        </form>
                        
                        <div class="text-center">
                            <a href="index.php" class="back-link">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left me-1" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
                                </svg>
                                Volver al inicio
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="text-center mt-3">
                    <small class="text-white-50">
                        © <?= date('Y') ?> Mabel Group - Sistema Intranet
                    </small>
                </div>
            </div>
        </div>
    </div>
    
    <script src="../assets/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Prevenir doble envío del formulario
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const btn = this.querySelector('button[type="submit"]');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Iniciando sesión...';
        });
    </script>
</body>
</html>


