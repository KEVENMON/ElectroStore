<?php
// Asegurarnos de que la sesión esté iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Detectar página actual para activar menú
$pagina = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin - ElectroStore</title>
    <link rel="stylesheet" href="css/admin_style.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>

    <nav class="admin-sidebar">
        <div class="brand-logo">ElectroAdmin</div>
        
        <ul class="nav-links">
            <li>
                <a href="dashboard.php" class="nav-link <?php echo ($pagina == 'dashboard.php') ? 'active' : ''; ?>">
                    <span class="nav-icon">📊</span> Dashboard
                </a>
            </li>
            <li>
                <a href="productos.php" class="nav-link <?php echo ($pagina == 'productos.php') ? 'active' : ''; ?>">
                    <span class="nav-icon">📦</span> Productos
                </a>
            </li>
            
            <li>
                <a href="ofertas_panel.php" class="nav-link <?php echo ($pagina == 'ofertas_panel.php') ? 'active' : ''; ?>">
                    <span class="nav-icon" style="color: #e67e22;">🔥</span> Ofertas
                </a>
            </li>
            <li>
                <a href="pedidos.php" class="nav-link <?php echo ($pagina == 'pedidos.php') ? 'active' : ''; ?>">
                    <span class="nav-icon">🛒</span> Pedidos
                </a>
            </li>
            <li style="margin-top: 30px; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 20px;">
                <a href="../index.php" class="nav-link" target="_blank">
                    <span class="nav-icon">🌐</span> Ver Tienda
                </a>
            </li>
            <li>
                <a href="../php/logout.php" class="nav-link" style="color: #ff6b6b;">
                    <span class="nav-icon">🚪</span> Cerrar Sesión
                </a>
            </li>
        </ul>
    </nav>

    <div class="admin-main">
        
        <header class="admin-topbar">
            <h2 class="page-title">
                <?php 
                    if($pagina == 'dashboard.php') echo 'Resumen General';
                    elseif($pagina == 'productos.php') echo 'Gestión de Productos';
                    elseif($pagina == 'pedidos.php') echo 'Control de Pedidos';
                ?>
            </h2>
            
            <div class="user-wrapper">
                <div style="text-align: right;">
                    <span style="display:block; font-weight:bold; font-size:0.9rem;"><?php echo $_SESSION['user_name']; ?></span>
                    <span style="display:block; font-size:0.8rem; color:#888;">Administrador</span>
                </div>
                <div class="admin-avatar">
                    <?php echo strtoupper(substr($_SESSION['user_name'], 0, 1)); ?>
                </div>
            </div>
        </header>

        <div class="content-wrapper">