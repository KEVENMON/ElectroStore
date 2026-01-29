<?php
require '../php/conexion.php';
require 'includes/header.php'; 

// Seguridad
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    echo "<script>window.location.href='../index.php';</script>"; exit;
}

// --- CONSULTAS SQL ---

// 1. Total Ventas ($)
$totalVentas = $conn->query("SELECT SUM(total) FROM Pedidos WHERE estado != 'Cancelado'")->fetchColumn() ?: 0;

// 2. TOTAL USUARIOS (Admin + Clientes)
// Al quitar el "WHERE", cuenta a todos los registrados en la base de datos.
$totalUsuarios = $conn->query("SELECT COUNT(*) FROM Usuarios")->fetchColumn() ?: 0;

// 3. Total Productos
$totalProductos = $conn->query("SELECT COUNT(*) FROM Productos")->fetchColumn() ?: 0;

// 4. Últimos 5 Pedidos
$sqlRecientes = "SELECT p.id_pedido, u.nombre, p.total, p.estado, p.fecha_pedido 
                 FROM Pedidos p 
                 JOIN Usuarios u ON p.id_usuario = u.id_usuario 
                 ORDER BY p.fecha_pedido DESC LIMIT 5";
$recientes = $conn->query($sqlRecientes)->fetchAll(PDO::FETCH_ASSOC);

// 5. Stock Bajo
$sqlStock = "SELECT nombre, stock, imagen FROM Productos WHERE stock <= 5 ORDER BY stock ASC LIMIT 5";
$stockBajo = $conn->query($sqlStock)->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
    .dashboard-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 25px; margin-top: 30px; }
    .panel-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #f4f7f6; }
    .panel-title { margin: 0; color: #002527; font-size: 1.1rem; font-weight: 700; }
    
    .list-item { display: flex; align-items: center; gap: 15px; padding: 15px 0; border-bottom: 1px solid #eee; }
    .list-item:last-child { border-bottom: none; }
    .item-img { width: 40px; height: 40px; border-radius: 5px; object-fit: contain; background: #f9f9f9; border: 1px solid #eee; }
    
    .stock-badge { background: #ffeeba; color: #856404; padding: 2px 8px; border-radius: 10px; font-size: 0.75rem; font-weight: bold; }
    .stock-critical { background: #f8d7da; color: #721c24; }

    .quick-actions { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 15px; }
    .action-btn { background: #f8f9fa; border: 1px solid #ddd; padding: 15px; border-radius: 10px; text-align: center; text-decoration: none; color: #333; font-weight: 600; transition: 0.3s; display: flex; flex-direction: column; align-items: center; gap: 5px; }
    .action-btn:hover { background: #00B7C3; color: white; border-color: #00B7C3; transform: translateY(-3px); }
    .action-icon { font-size: 1.5rem; }

    /* Estilo Morado para Usuarios */
    .card.purple { border-color: #6f42c1; }
    .card.purple h3 { color: #6f42c1; }

    @media(max-width: 900px) { .dashboard-grid { grid-template-columns: 1fr; } }
</style>

<div class="dashboard-cards">
    <div class="card blue">
        <h3>$<?php echo number_format($totalVentas, 2); ?></h3>
        <p>Ingresos Totales</p>
    </div>

    <div class="card purple">
        <h3><?php echo $totalUsuarios; ?></h3>
        <p>Usuarios Registrados</p> </div>

    <div class="card green">
        <h3><?php echo $totalProductos; ?></h3>
        <p>Productos Activos</p>
    </div>
</div>

<div class="dashboard-grid">
    
    <div class="card">
        <div class="panel-header">
            <h3 class="panel-title">📦 Actividad Reciente</h3>
            <a href="pedidos.php" style="color: #00B7C3; text-decoration: none; font-size: 0.9rem;">Ver todos →</a>
        </div>
        
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="text-align: left; color: #666; font-size: 0.85rem;">
                        <th style="padding-bottom: 10px;">Pedido</th>
                        <th style="padding-bottom: 10px;">Cliente</th>
                        <th style="padding-bottom: 10px;">Estado</th>
                        <th style="padding-bottom: 10px; text-align: right;">Monto</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($recientes) > 0): ?>
                        <?php foreach($recientes as $p): ?>
                        <tr style="border-top: 1px solid #f4f7f6;">
                            <td style="padding: 12px 0; font-weight: bold;">#<?php echo $p['id_pedido']; ?></td>
                            <td style="padding: 12px 0;"><?php echo htmlspecialchars($p['nombre']); ?></td>
                            <td style="padding: 12px 0;">
                                <span class="status-badge <?php echo ($p['estado']=='Pendiente')?'status-warning':'status-success'; ?>">
                                    <?php echo $p['estado']; ?>
                                </span>
                            </td>
                            <td style="padding: 12px 0; text-align: right; font-weight: bold; color: #333;">
                                $<?php echo number_format($p['total'], 2); ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="4" style="text-align:center; padding: 20px;">No hay ventas recientes.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div>
        <div class="card" style="margin-bottom: 25px;">
            <div class="panel-header">
                <h3 class="panel-title">⚠️ Alerta de Stock</h3>
            </div>
            
            <?php if(count($stockBajo) > 0): ?>
                <?php foreach($stockBajo as $prod): ?>
                <div class="list-item">
                    <img src="../<?php echo !empty($prod['imagen']) ? htmlspecialchars($prod['imagen']) : 'img/default.jpg'; ?>" class="item-img">
                    <div style="flex: 1;">
                        <div style="font-weight: 600; font-size: 0.9rem;"><?php echo htmlspecialchars($prod['nombre']); ?></div>
                        <span class="stock-badge <?php echo ($prod['stock'] == 0) ? 'stock-critical' : ''; ?>">
                            Quedan: <?php echo $prod['stock']; ?>
                        </span>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="text-align: center; color: #28a745; padding: 15px;">
                    <span style="font-size: 2rem;">✅</span>
                    <p>Inventario estable</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="card">
            <h3 class="panel-title">⚡ Accesos</h3>
            <div class="quick-actions">
                <a href="productos.php" class="action-btn">
                    <span class="action-icon">➕</span> Producto
                </a>
                <a href="pedidos.php" class="action-btn">
                    <span class="action-icon">🔍</span> Pedidos
                </a>
                <a href="../index.php" target="_blank" class="action-btn">
                    <span class="action-icon">🏠</span> Tienda
                </a>
                <a href="../php/logout.php" class="action-btn" style="color: #dc3545; border-color: #f8d7da;">
                    <span class="action-icon">🚪</span> Salir
                </a>
            </div>
        </div>
    </div>
</div>

<?php require 'includes/footer.php'; ?>