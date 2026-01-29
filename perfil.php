<?php
// Usamos el header global para mantener el menú
require 'includes/header.php'; 
require 'php/conexion.php';

// Seguridad: Si no está logueado, redirigir al login
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href='login.php';</script>";
    exit();
}

$id_usuario = $_SESSION['user_id'];

// 1. SOLUCIÓN AL ERROR: Consultar datos frescos del usuario
// Esto evita el error "Undefined index" si la sesión no tiene el email
$stmtUser = $conn->prepare("SELECT nombre, email, telefono, direccion, ciudad FROM Usuarios WHERE id_usuario = ?");
$stmtUser->execute([$id_usuario]);
$usuario = $stmtUser->fetch(PDO::FETCH_ASSOC);

// Si por alguna razón no existe el usuario, cerrar sesión forzada
if (!$usuario) {
    session_destroy();
    echo "<script>window.location.href='login.php';</script>";
    exit();
}

// 2. Obtener historial de pedidos
$stmt = $conn->prepare("SELECT * FROM Pedidos WHERE id_usuario = ? ORDER BY fecha_pedido DESC");
$stmt->execute([$id_usuario]);
$pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
    body { background-color: #f4f7f6; } /* Fondo suave para toda la página */
    
    .profile-wrapper {
        max-width: 1200px;
        margin: 50px auto;
        padding: 0 20px;
        display: grid;
        grid-template-columns: 300px 1fr; /* Columna Izq (Datos) - Columna Der (Pedidos) */
        gap: 30px;
    }

    /* Tarjeta de Usuario (Izquierda) */
    .user-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        padding: 30px;
        text-align: center;
        height: fit-content;
    }
    
    .avatar-circle {
        width: 100px;
        height: 100px;
        background: #002527;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        margin: 0 auto 20px;
    }

    .user-card h2 { color: #002527; margin-bottom: 5px; font-size: 1.5rem; }
    .user-card p { color: #666; margin-bottom: 20px; font-size: 0.95rem; }
    
    .info-list { text-align: left; margin-top: 20px; border-top: 1px solid #eee; padding-top: 20px; }
    .info-item { margin-bottom: 15px; }
    .info-item strong { display: block; color: #00B7C3; font-size: 0.85rem; text-transform: uppercase; }
    .info-item span { color: #333; font-weight: 500; }

    /* Sección de Pedidos (Derecha) */
    .orders-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        padding: 30px;
    }

    .orders-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        border-bottom: 2px solid #f0f0f0;
        padding-bottom: 15px;
    }
    .orders-header h3 { font-size: 1.5rem; color: #002527; margin: 0; }

    /* Tabla Estilizada */
    .custom-table { width: 100%; border-collapse: collapse; }
    .custom-table th { text-align: left; color: #888; font-weight: 600; padding: 15px; font-size: 0.9rem; }
    .custom-table td { padding: 15px; border-top: 1px solid #f9f9f9; color: #333; vertical-align: middle; }
    .custom-table tr:hover td { background-color: #fcfcfc; }

    /* Estado (Badge) */
    .badge { padding: 6px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: bold; display: inline-block; }
    .badge-success { background: #d4edda; color: #155724; }
    .badge-warning { background: #fff3cd; color: #856404; }

    .btn-invoice {
        background: white;
        border: 1px solid #00B7C3;
        color: #00B7C3;
        padding: 6px 15px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 0.85rem;
        transition: 0.3s;
        display: inline-flex;
        align-items: center; gap: 5px;
    }
    .btn-invoice:hover { background: #00B7C3; color: white; }

    /* Responsive */
    @media (max-width: 900px) {
        .profile-wrapper { grid-template-columns: 1fr; }
        .custom-table thead { display: none; } /* Ocultar cabeceras en móvil */
        .custom-table tr { display: block; margin-bottom: 20px; border: 1px solid #eee; border-radius: 10px; padding: 10px; }
        .custom-table td { display: flex; justify-content: space-between; padding: 10px; border: none; }
        .custom-table td::before { content: attr(data-label); font-weight: bold; color: #888; }
    }
</style>

<main>
    <div class="profile-wrapper">
        
        <aside class="user-card">
            <div class="avatar-circle">
                <?php echo strtoupper(substr($usuario['nombre'], 0, 1)); ?>
            </div>
            <h2><?php echo htmlspecialchars($usuario['nombre']); ?></h2>
            <p>Cliente Registrado</p>
            
            <a href="php/logout.php" class="btn" style="background:#dc3545; color:white; width:100%; display:block; text-align:center;">Cerrar Sesión</a>

            <div class="info-list">
                <div class="info-item">
                    <strong>Email</strong>
                    <span><?php echo htmlspecialchars($usuario['email']); ?></span>
                </div>
                <div class="info-item">
                    <strong>Teléfono</strong>
                    <span><?php echo !empty($usuario['telefono']) ? htmlspecialchars($usuario['telefono']) : 'Sin registrar'; ?></span>
                </div>
                <div class="info-item">
                    <strong>Dirección</strong>
                    <span><?php echo !empty($usuario['direccion']) ? htmlspecialchars($usuario['direccion']) : 'Sin registrar'; ?></span>
                </div>
                <div class="info-item">
                    <strong>Ciudad</strong>
                    <span><?php echo !empty($usuario['ciudad']) ? htmlspecialchars($usuario['ciudad']) : 'Sin registrar'; ?></span>
                </div>
            </div>
        </aside>

        <section class="orders-card">
            <div class="orders-header">
                <h3>📦 Mis Pedidos</h3>
                <span style="background:#002527; color:white; padding:5px 10px; border-radius:10px; font-size:0.9rem;">
                    Total: <?php echo count($pedidos); ?>
                </span>
            </div>

            <?php if (count($pedidos) > 0): ?>
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>N° PEDIDO</th>
                            <th>FECHA</th>
                            <th>TOTAL</th>
                            <th>ESTADO</th>
                            <th>ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pedidos as $p): ?>
                        <tr>
                            <td data-label="Pedido"><strong>#<?php echo $p['id_pedido']; ?></strong></td>
                            <td data-label="Fecha"><?php echo date('d/m/Y', strtotime($p['fecha_pedido'])); ?></td>
                            <td data-label="Total" style="font-weight:bold; color:#002527;">$<?php echo number_format($p['total'], 2); ?></td>
                            <td data-label="Estado">
                                <span class="badge <?php echo ($p['estado'] == 'Completado') ? 'badge-success' : 'badge-warning'; ?>">
                                    <?php echo $p['estado']; ?>
                                </span>
                            </td>
                            <td data-label="Acciones">
                                <a href="php/descargar_factura.php?pedido=<?php echo $p['id_pedido']; ?>&print=true" target="_blank" class="btn-invoice">
                                    📄 Ver Factura
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div style="text-align:center; padding: 40px;">
                    <div style="font-size: 4rem; margin-bottom: 20px; opacity: 0.5;">🛍️</div>
                    <h4 style="color: #666; margin-bottom: 10px;">Aún no tienes pedidos</h4>
                    <p style="color: #999; margin-bottom: 20px;">Tus compras aparecerán aquí.</p>
                    <a href="tienda.php" class="btn" style="background: #00B7C3; color: white;">Ir a la Tienda</a>
                </div>
            <?php endif; ?>
        </section>

    </div>
</main>

<?php require 'includes/footer.php'; ?>