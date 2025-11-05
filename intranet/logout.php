<?php
/**
 * Script para cerrar sesión
 */
require_once 'auth.php';

logout();

// Redirigir al login con mensaje
header('Location: login_admin.php?logout=1');
exit;


