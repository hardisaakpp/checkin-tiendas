<!doctype html>
<html lang="es" data-bs-theme="auto">
<?php 
date_default_timezone_set('America/Bogota');
// Iniciar sesión si no está iniciada (para mostrar información de usuario en el header)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
  <head>
    <script src="../assets/dist/js/color-modes.js"></script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Departamento Desarrollo MABEL">
    <meta name="generator" content="1.0">
    <title>Intranet Mabel Group</title>
    <link rel="icon" type="image/png" href="../assets/img/logos/<?php echo rawurlencode('Solo LogoMT.png'); ?>" />
    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/carousel/">  
    <link href="../assets/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="../assets/dist/js/jquery.min.js"></script>  
    <!-- DataTables scripts comentados - se cargan desde CDN en páginas específicas -->
    <!-- <script src="../assets/dist/js/dataTables.bootstrap.min.js"></script> -->            
    <!-- <link rel="stylesheet" href="../assets/dist/css/jquery.dataTables.css"> -->
    <!-- <script src="../assets/dist/js/jquery-2.1.1.min.js"></script> -->
    <!-- <script type="text/javascript" src="../assets/dist/js/jquery.dataTables.min.js"></script> -->
    <!-- <script>$(document).ready(function(){ $('#employee_data').DataTable(); });</script> --> 
    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }

      .b-example-divider {
        width: 100%;
        height: 3rem;
        background-color: rgba(0, 0, 0, .1);
        border: solid rgba(0, 0, 0, .15);
        border-width: 1px 0;
        box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15);
      }

      .b-example-vr {
        flex-shrink: 0;
        width: 1.5rem;
        height: 100vh;
      }

      .bi {
        vertical-align: -.125em;
        fill: currentColor;
      }

      .nav-scroller {
        position: relative;
        z-index: 2;
        height: 2.75rem;
        overflow-y: hidden;
      }

      .nav-scroller .nav {
        display: flex;
        flex-wrap: nowrap;
        padding-bottom: 1rem;
        margin-top: -1px;
        overflow-x: auto;
        text-align: center;
        white-space: nowrap;
        -webkit-overflow-scrolling: touch;
      }

      .btn-bd-primary {
        --bd-violet-bg: #712cf9;
        --bd-violet-rgb: 112.520718, 44.062154, 249.437846;

        --bs-btn-font-weight: 600;
        --bs-btn-color: var(--bs-white);
        --bs-btn-bg: var(--bd-violet-bg);
        --bs-btn-border-color: var(--bd-violet-bg);
        --bs-btn-hover-color: var(--bs-white);
        --bs-btn-hover-bg: #6528e0;
        --bs-btn-hover-border-color: #6528e0;
        --bs-btn-focus-shadow-rgb: var(--bd-violet-rgb);
        --bs-btn-active-color: var(--bs-btn-hover-color);
        --bs-btn-active-bg: #5a23c8;
        --bs-btn-active-border-color: #5a23c8;
      }

      .bd-mode-toggle {
        z-index: 1500;
      }

      .bd-mode-toggle .dropdown-menu .active .bi {
        display: block !important;
      }

      .carousel-indicators .inactive {
        background-color:rgb(92, 103, 148); /* Cambia el color inactivo */
      }

      .carousel-indicators .active {
          background-color:rgb(80, 18, 236); /* Cambia el color activo */
      }

      .logo-animated {
        transition: transform 0.5s ease-in-out;
        cursor: pointer;
      }

      .logo-animated:hover {
        transform: scale(1.2);
        animation: bounce 0.9s;
      }

      @keyframes bounce {
        0%   { transform: scale(1.2); }
        50%  { transform: scale(0.99); }
        100% { transform: scale(1.2); }
      }

      .brand-button {
        background-color:rgb(159, 203, 239);
        padding: 7px 10px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid rgba(255,255,255,0.2);
      }
      .brand-button:hover {
        background-color:rgb(159, 203, 239);
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        transform: translateY(-1px);
      }

      /* Estilos mejorados para el navbar */
      .navbar {
        transition: box-shadow 0.3s ease;
      }

      /* Placeholder personalizado */
      .form-control::placeholder {
        color: rgba(255, 255, 255, 0.7) !important;
        opacity: 1;
      }

      .form-control:focus::placeholder {
        color: rgba(255, 255, 255, 0.5) !important;
      }

      /* Mejoras en la visualización de iconos */
      .icon-container svg {
        filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
      }

    </style>  
    <script>
      function abrirCarpeta() {
          // Redirige a una vista de exploración de carpetas (puedes crear una página tipo file browser)
          window.location.href = "explorador_carpetas_th.php";
        }
    </script>  
    <!-- Custom styles for this template -->
    <link href="../assets/dist/css/carousel.css" rel="stylesheet">
  </head>
  <body class="bg-body-tertiary">
    <svg xmlns="#" class="d-none">
      <symbol id="check2" viewBox="0 0 16 16">
        <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
      </symbol>
      <symbol id="circle-half" viewBox="0 0 16 16">
        <path d="M8 15A7 7 0 1 0 8 1v14zm0 1A8 8 0 1 1 8 0a8 8 0 0 1 0 16z"/>
      </symbol>
      <symbol id="moon-stars-fill" viewBox="0 0 16 16">
        <path d="M6 .278a.768.768 0 0 1 .08.858 7.208 7.208 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277.527 0 1.04-.055 1.533-.16a.787.787 0 0 1 .81.316.733.733 0 0 1-.031.893A8.349 8.349 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.752.752 0 0 1 6 .278z"/>
        <path d="M10.794 3.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387a1.734 1.734 0 0 0-1.097 1.097l-.387 1.162a.217.217 0 0 1-.412 0l-.387-1.162A1.734 1.734 0 0 0 9.31 6.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387a1.734 1.734 0 0 0 1.097-1.097l.387-1.162zM13.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.156 1.156 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.156 1.156 0 0 0-.732-.732l-.774-.258a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732L13.863.1z"/>
      </symbol>
      <symbol id="sun-fill" viewBox="0 0 16 16">
        <path d="M8 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0zm0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13zm8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5zM3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8zm10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0zm-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0zm9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707zM4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708z"/>
      </symbol>
    </svg>

    <div class="dropdown position-fixed bottom-0 end-0 mb-3 me-3 bd-mode-toggle">
      <button class="btn btn-bd-primary py-2 dropdown-toggle d-flex align-items-center" id="bd-theme" type="button" aria-expanded="false" data-bs-toggle="dropdown" aria-label="Toggle theme (auto)">
        <svg class="bi my-1 theme-icon-active" width="1em" height="1em"><use href="#circle-half"></use></svg>
        <span class="visually-hidden" id="bd-theme-text">Toggle theme</span>
      </button>
      <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="bd-theme-text">
        <li>
          <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="light" aria-pressed="false">
            <svg class="bi me-2 opacity-50" width="1em" height="1em"><use href="#sun-fill"></use></svg>
            Claro
            <svg class="bi ms-auto d-none" width="1em" height="1em"><use href="#check2"></use></svg>
          </button>
        </li>
        <li>
          <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="dark" aria-pressed="false">
            <svg class="bi me-2 opacity-50" width="1em" height="1em"><use href="#moon-stars-fill"></use></svg>
            Oscuro
            <svg class="bi ms-auto d-none" width="1em" height="1em"><use href="#check2"></use></svg>
          </button>
        </li>
        <li>
          <button type="button" class="dropdown-item d-flex align-items-center active" data-bs-theme-value="auto" aria-pressed="true">
            <svg class="bi me-2 opacity-50" width="1em" height="1em"><use href="#circle-half"></use></svg>
            Automático
            <svg class="bi ms-auto d-none" width="1em" height="1em"><use href="#check2"></use></svg>
          </button>
        </li>
      </ul>
    </div>

    <div class="container">
      
      <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between mb-4 border-bottom">
        <nav class="navbar navbar-expand-md navbar-dark fixed-top" style="background: linear-gradient(135deg, #64b5f6 0%, #42a5f5 100%); box-shadow: 0 2px 12px rgba(0,0,0,0.15); backdrop-filter: blur(10px);">
          <div class="container-fluid">
            <a class="navbar-brand brand-button" href="index.php" title="Haz clic para volver al inicio"><img src="../assets/img/intranet/2.png" width="50" height="36" alt="Logo Intranet" class="logo-animated"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation" style="border: 1px solid rgba(255,255,255,0.3); border-radius: 6px; padding: 4px 8px;">
              <span class="navbar-toggler-icon"></span>
            </button>

            <div class="icon-container" style="display: flex; gap: 12px; align-items: center;">
              <?php
              // Mostrar información de usuario si está autenticado
              if (isset($_SESSION['admin_autenticado']) && $_SESSION['admin_autenticado'] === true) {
                  $usuarioNombre = $_SESSION['usuario_nombre'] ?? 'Usuario';
                  $usuarioRol = $_SESSION['usuario_rol'] ?? '';
                  echo '<span class="text-white me-2 d-flex align-items-center" style="font-size: 0.9rem; font-weight: 500; padding: 6px 12px; background-color: rgba(255,255,255,0.1); border-radius: 8px; transition: all 0.3s ease;" onmouseover="this.style.backgroundColor=\'rgba(255,255,255,0.15)\'" onmouseout="this.style.backgroundColor=\'rgba(255,255,255,0.1)\'">';
                  echo '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-circle me-2" viewBox="0 0 16 16" style="flex-shrink: 0;">';
                  echo '<path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>';
                  echo '<path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>';
                  echo '</svg>';
                  echo '<span>' . htmlspecialchars($usuarioNombre) . '</span>';
                  if ($usuarioRol) {
                      echo ' <small class="badge ms-2" style="background: linear-gradient(135deg, rgba(255,255,255,0.3) 0%, rgba(255,255,255,0.2) 100%); color: white; border: 1px solid rgba(255,255,255,0.3); border-radius: 6px; padding: 2px 8px; font-weight: 500;">' . htmlspecialchars($usuarioRol) . '</small>';
                  }
                  echo '</span>';
                  echo '<a href="logout.php" class="btn btn-outline-light btn-sm d-flex align-items-center gap-1" style="text-decoration: none; border-radius: 8px; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); border-width: 1.5px;" title="Cerrar sesión" onmouseover="this.style.transform=\'translateY(-2px)\'; this.style.boxShadow=\'0 4px 12px rgba(0,0,0,0.2)\'" onmouseout="this.style.transform=\'translateY(0)\'; this.style.boxShadow=\'none\'">';
                  echo '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16">';
                  echo '<path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z"/>';
                  echo '<path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z"/>';
                  echo '</svg>';
                  echo 'Salir';
                  echo '</a>';
              }
              ?>
              <button class="btn btn-outline-light d-flex align-items-center gap-2" onclick="abrirCarpeta()" style="border-radius: 10px; border-width: 1.5px; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); font-weight: 500;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(0,0,0,0.2)'; this.style.backgroundColor='rgba(255,255,255,0.15)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'; this.style.backgroundColor='transparent'">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-folder-fill" viewBox="0 0 16 16" style="transition: transform 0.3s ease;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                  <path d="M9.828 4a.5.5 0 0 1 .354.146l1.5 1.5A.5.5 0 0 1 11.5 6H14a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h5.172a.5.5 0 0 1 .354.146l1.5 1.5A.5.5 0 0 1 9.5 3H14a2 2 0 0 1 2 2v1H9.828z"/>
                </svg>
                TALENTO HUMANO
              </button>
            </div>

            <div class="collapse navbar-collapse" id="navbarCollapse">
              <form class="d-flex ms-auto">
                <input class="form-control me-2" type="search" placeholder="Buscar..." aria-label="Search" style="border-radius: 8px; border: 1px solid rgba(255,255,255,0.3); background-color: rgba(255,255,255,0.1); color: white; transition: all 0.3s ease;" onfocus="this.style.backgroundColor='rgba(255,255,255,0.2)'; this.style.borderColor='rgba(255,255,255,0.5)'" onblur="this.style.backgroundColor='rgba(255,255,255,0.1)'; this.style.borderColor='rgba(255,255,255,0.3)'">
                <button class="btn btn-secondary" type="submit" style="border-radius: 8px; border: none; background-color: rgba(255,255,255,0.2); transition: all 0.3s ease; font-weight: 500;" onmouseover="this.style.backgroundColor='rgba(255,255,255,0.3)'; this.style.transform='translateY(-1px)'" onmouseout="this.style.backgroundColor='rgba(255,255,255,0.2)'; this.style.transform='translateY(0)'">Buscar</button>
              </form>
            </div>
          </div>
        </nav>            
      </header>

