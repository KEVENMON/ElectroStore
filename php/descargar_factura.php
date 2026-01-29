<?php
session_start();
require 'conexion.php';

// Verificar acceso
if (!isset($_SESSION['user_id']) || !isset($_GET['pedido'])) {
    die("Acceso denegado.");
}

$id_pedido = $_GET['pedido'];
$id_usuario = $_SESSION['user_id'];

// Consultar Pedido (Asegurando que pertenezca al usuario logueado)
$stmt = $conn->prepare("SELECT p.*, u.nombre, u.email, u.direccion, u.telefono 
                        FROM Pedidos p 
                        JOIN Usuarios u ON p.id_usuario = u.id_usuario 
                        WHERE p.id_pedido = ? AND p.id_usuario = ?");
$stmt->execute([$id_pedido, $id_usuario]);
$pedido = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pedido) die("Pedido no encontrado.");

// Consultar Detalles
$stmtDet = $conn->prepare("SELECT dp.*, pr.nombre 
                           FROM Detalles_Pedidos dp 
                           JOIN Productos pr ON dp.id_producto = pr.id_producto 
                           WHERE dp.id_pedido = ?");
$stmtDet->execute([$id_pedido]);
$items = $stmtDet->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura #<?php echo $id_pedido; ?></title>
    <style>
        body { font-family: Arial, sans-serif; padding: 40px; color: #333; }
        .invoice-header { display: flex; justify-content: space-between; border-bottom: 2px solid #002527; padding-bottom: 20px; margin-bottom: 30px; }
        .invoice-title { font-size: 2rem; color: #002527; font-weight: bold; }
        .company-info { text-align: right; font-size: 0.9rem; }
        
        .client-info { margin-bottom: 40px; }
        .client-info h3 { border-bottom: 1px solid #ccc; padding-bottom: 5px; margin-bottom: 10px; font-size: 1.1rem; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th { background: #f4f4f4; padding: 10px; text-align: left; border-bottom: 2px solid #ddd; }
        td { padding: 10px; border-bottom: 1px solid #eee; }
        .total-section { text-align: right; }
        .total-row { font-size: 1.2rem; font-weight: bold; color: #002527; }
        
        .footer { margin-top: 50px; text-align: center; font-size: 0.8rem; color: #777; border-top: 1px solid #eee; padding-top: 20px; }
        
        @media print {
            .no-print { display: none; }
            body { padding: 0; }
        }
    </style>
</head>
<body onload="window.print()"> <div class="invoice-header">
        <div class="invoice-title">FACTURA</div>
        <div class="company-info">
            <strong>ElectroStore S.A.</strong><br>
            Av. Amazonas N45-123<br>
            Quito, Ecuador<br>
            RUC: 1790012345001<br>
            info@electrostore.com
        </div>
    </div>

    <div class="client-info">
        <h3>Datos del Cliente</h3>
        <p>
            <strong>Cliente:</strong> <?php echo htmlspecialchars($pedido['nombre']); ?><br>
            <strong>Dirección:</strong> <?php echo htmlspecialchars($pedido['direccion']); ?><br>
            <strong>Teléfono:</strong> <?php echo htmlspecialchars($pedido['telefono']); ?><br>
            <strong>Fecha de Emisión:</strong> <?php echo date('d/m/Y H:i', strtotime($pedido['fecha_pedido'])); ?><br>
            <strong>Nro. Orden:</strong> #<?php echo str_pad($id_pedido, 6, "0", STR_PAD_LEFT); ?>
        </p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Descripción</th>
                <th width="10%">Cant.</th>
                <th width="15%">P. Unit</th>
                <th width="15%" style="text-align:right;">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
            <tr>
                <td><?php echo htmlspecialchars($item['nombre']); ?></td>
                <td><?php echo $item['cantidad']; ?></td>
                <td>$<?php echo number_format($item['precio_unitario'], 2); ?></td>
                <td style="text-align:right;">$<?php echo number_format($item['subtotal'], 2); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="total-section">
        <p>Subtotal: $<?php echo number_format($pedido['total'] / 1.10 - 5, 2); ?></p>
        <p>IVA (10%): $<?php echo number_format(($pedido['total'] - 5) - ($pedido['total'] / 1.10 - 5), 2); ?></p>
        <p>Envío: $5.00</p>
        <p class="total-row">TOTAL: $<?php echo number_format($pedido['total'], 2); ?></p>
    </div>

    <div class="footer">
        Gracias por su compra en ElectroStore. <br>
        Esta es una factura generada electrónicamente válida para fines tributarios.
    </div>

</body>
</html>