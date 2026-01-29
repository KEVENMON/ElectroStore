<?php
session_start();

// 1. Verificamos si existe la sesión y el rol
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    // Si no es admin, lo mandamos a la tienda
    header("Location: index.php");
    exit();
}

// 2. EL PUENTE: Si es admin, lo redirigimos a la nueva carpeta
header("Location: admin/dashboard.php");
exit();
?>