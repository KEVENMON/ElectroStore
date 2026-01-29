<?php
// Evitar iniciar sesión si ya está activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Detectar página actual
$pagina_actual = basename($_SERVER['PHP_SELF']);
// Generar una versión única para romper la caché
$version = time(); 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ElectroStore</title>
    
    <link rel="stylesheet" href="css/style.css?v=<?php echo $version; ?>">
    <link rel="stylesheet" href="css/carrito.css?v=<?php echo $version; ?>">
    <link rel="stylesheet" href="css/login.css?v=<?php echo $version; ?>">
    <link rel="stylesheet" href="css/shop.css?v=<?php echo $version; ?>">
    
    <style>
        /* --- ESTILOS DEL BOTÓN DE CARRITO MODERNO --- */
        #btnCarrito {
            background-color: #002527; /* Fondo Oscuro */
            color: white !important;
            border: none;
            padding: 10px 20px;
            border-radius: 50px; /* Forma de píldora */
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px; /* Espacio entre icono y texto */
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(0, 37, 39, 0.2);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Efecto Hover */
        #btnCarrito:hover {
            background-color: #00B7C3; /* Cambio a Turquesa */
            transform: translateY(-2px); /* Se eleva un poco */
            box-shadow: 0 6px 15px rgba(0, 183, 195, 0.3);
        }

        /* Icono inyectado por CSS (Sobrevive a la actualización de JS) */
        #btnCarrito::before {
            content: '🛒'; 
            font-size: 1.2rem;
        }

        /* --- TUS ESTILOS DE GRID ANTERIORES (Mantenidos) --- */
        .products-grid {
            display: grid !important;
            grid-template-columns: repeat(4, 1fr) !important;
            gap: 30px !important;
            width: 100% !important;
            max-width: 1400px !important;
            margin: 30px auto !important;
        }
        .product-card {
            display: flex !important;
            flex-direction: column !important;
            width: 100% !important;
            background: #fff;
            border: 1px solid #eee;
            border-radius: 15px;
            overflow: hidden;
        }
        .product-card img {
            width: 100% !important;
            height: 200px !important;
            object-fit: contain !important;
        }
        @media (max-width: 1200px) { .products-grid { grid-template-columns: repeat(3, 1fr) !important; } }
        @media (max-width: 900px) { .products-grid { grid-template-columns: repeat(2, 1fr) !important; } }
        @media (max-width: 600px) { .products-grid { grid-template-columns: 1fr !important; } }
        main { display: block !important; width: 100% !important; clear: both !important; }
        .hero-banner, .delivery-hero { width: 100% !important; box-sizing: border-box !important; }
    </style>

    <script src="js/script.js?v=<?php echo $version; ?>" defer></script>
    <script src="js/carrito_pro.js?v=<?php echo $version; ?>" defer></script>
    <script src="js/html2canvas.js"></script>
    <script src="js/jspdf.debug.js"></script>
    <script src="js/jspdf.plugin.autotable.min.js"></script>
</head>
<body>
    <header class="header">
        <div class="div_header">
            <div class="logo_actions_header">
                <h1><a href="index.php" style="text-decoration:none; color:white;">ElectroStore</a></h1>
                
                <div class="actions_1_header">
                    <form action="buscar.php" method="GET" style="display: flex; align-items: center; background: white; border-radius: 20px; padding: 5px 15px; border: 1px solid #ccc;">
                        <input type="text" name="q" placeholder="Buscar producto..." required 
                            style="border: none; outline: none; padding: 5px; width: 200px; border-radius: 0;">
                        <button type="submit" style="background: none; border: none; cursor: pointer; font-size: 1.2rem;">🔍</button>
                    </form>
                    
                    <a href="https://wa.me/+593984562834" class="btn" target="_blank" style="margin-right: 15px;">Contáctanos</a>
                    
                    <button id="btnCarrito">Mi carrito (0)</button>

                    <?php if(isset($_SESSION['user_id'])): ?>
                        <a href="perfil.php" class="btn" style="background:rgba(255,255,255,0.9); color:#002527; font-weight:bold; text-decoration:none; margin-left: 15px;">
                            Hola, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Cliente'); ?> 👤
                        </a>
                        <a href="php/logout.php" class="btn" style="background-color: #dc3545; color: white;">Salir</a>
                    <?php else: ?>
                        <a href="login.php" class="btn" style="margin-left: 15px;">Ingresar</a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="actions_2_header">
                <nav class="nav">
                    <a href="index.php">Inicio</a>
                    <a href="tienda.php">Electrodomésticos</a>
                    <a href="galeria.php">Ofertas</a>
                    <a href="entrega.php">Punto de Entrega</a>
                    <a href="about.php">Sobre Nosotros</a>
                </nav>
            </div>

            <?php if ($pagina_actual == 'index.php' || $pagina_actual == ''): ?>
            <div class="banner_header" style="width: 100%; overflow: hidden;">
                <section class="banner">
                    <img src="Img/pictures/banner.jpg" alt="Banner" style="width: 100%; height: auto; display: block;">
                </section>
            </div>
            <?php endif; ?>
        </div>
    </header>