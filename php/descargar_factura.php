<?php
session_start();
require 'conexion.php';

// 1. Verificar si hay sesión
if (!isset($_SESSION['user_id'])) {
    die("Error: Debes iniciar sesión para ver la factura.");
}

// 2. Verificar que llegue el ID del pedido
if (!isset($_GET['pedido'])) {
    die("Error: No se especificó un pedido.");
}

$id_pedido = $_GET['pedido'];
$id_usuario_actual = $_SESSION['user_id'];
$rol_actual = $_SESSION['rol']; // Asegúrate que en el login guardamos esto en sesión

try {
    // --- LÓGICA CLAVE: CONSULTA DIFERENCIADA ---
    
    if ($rol_actual === 'admin') {
        // SI ES ADMIN: Puede ver el pedido SIN importar de quién sea
        $sql = "SELECT p.*, u.nombre as nombre_cliente, u.email, u.telefono, u.direccion 
                FROM Pedidos p
                JOIN Usuarios u ON p.id_usuario = u.id_usuario
                WHERE p.id_pedido = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id_pedido]);
    
    } else {
        // SI ES CLIENTE: Solo puede ver SU propio pedido
        $sql = "SELECT p.*, u.nombre as nombre_cliente, u.email, u.telefono, u.direccion 
                FROM Pedidos p
                JOIN Usuarios u ON p.id_usuario = u.id_usuario
                WHERE p.id_pedido = ? AND p.id_usuario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id_pedido, $id_usuario_actual]);
    }

    $pedido = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$pedido) {
        die("<div style='text-align:center; padding:50px; font-family:sans-serif;'>
                <h1>⚠️ Pedido no encontrado</h1>
                <p>Es posible que no tengas permisos para ver esta factura o que el ID sea incorrecto.</p>
                <a href='../index.php'>Volver al inicio</a>
             </div>");
    }

    // 3. Obtener los detalles (productos)
    $stmtDetalles = $conn->prepare("SELECT d.*, p.nombre 
                                    FROM Detalles_Pedidos d 
                                    JOIN Productos p ON d.id_producto = p.id_producto 
                                    WHERE d.id_pedido = ?");
    $stmtDetalles->execute([$id_pedido]);
    $detalles = $stmtDetalles->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Error de base de datos: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura #<?php echo str_pad($pedido['id_pedido'], 5, "0", STR_PAD_LEFT); ?></title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #555; padding: 30px; }
        .invoice-box {
            background: #fff; max-width: 800px; margin: auto; padding: 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15); font-size: 16px; line-height: 24px; color: #555;
        }
        .invoice-header { display: flex; justify-content: space-between; margin-bottom: 20px; }
        .invoice-title { font-size: 45px; line-height: 45px; color: #333; }
        .invoice-details { text-align: right; }
        
        table { width: 100%; line-height: inherit; text-align: left; border-collapse: collapse; }
        table td { padding: 5px; vertical-align: top; }
        table tr.heading td { background: #eee; border-bottom: 1px solid #ddd; font-weight: bold; }
        table tr.item td { border-bottom: 1px solid #eee; }
        table tr.total td { border-top: 2px solid #eee; font-weight: bold; }
        
        .status-badge {
            display: inline-block; padding: 5px 10px; border-radius: 5px; color: white; font-weight: bold; font-size: 0.8rem;
            background: <?php echo ($pedido['estado'] == 'Pendiente') ? '#ffc107' : '#28a745'; ?>;
        }
        
        @media print { body { background: white; } .no-print { display: none; } }
    </style>
</head>
<body>

    <div class="no-print" style="text-align: center; margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #002527; color: white; border: none; cursor: pointer; font-size: 16px;">🖨️ Imprimir Factura</button>
        <?php if($rol_actual == 'admin'): ?>
            <a href="../admin/pedidos.php" style="color: white; margin-left: 20px;">Volver al Panel</a>
        <?php else: ?>
            <a href="../historial.php" style="color: white; margin-left: 20px;">Volver al Historial</a>
        <?php endif; ?>
    </div>

    <div class="invoice-box">
        <div class="invoice-header">
            <div>
                <div class="invoice-title">ElectroStore</div>
                <div>Av. General Rumiñahui, Sangolquí</div>
                <div>soporte@electrostore.com</div>
            </div>
            <div class="invoice-details">
                <b>Factura #: <?php echo str_pad($pedido['id_pedido'], 5, "0", STR_PAD_LEFT); ?></b><br>
                Fecha: <?php echo date("d/m/Y", strtotime($pedido['fecha_pedido'])); ?><br>
                Estado: <span class="status-badge"><?php echo $pedido['estado']; ?></span>
            </div>
        </div>

        <table cellpadding="0" cellspacing="0">
            <tr class="heading">
                <td>Facturar a:</td>
                <td></td>
            </tr>
            <tr class="details">
                <td colspan="2">
                    <?php echo htmlspecialchars($pedido['nombre_cliente']); ?><br>
                    <?php echo htmlspecialchars($pedido['email']); ?><br>
                    <?php echo !empty($pedido['telefono']) ? htmlspecialchars($pedido['telefono']) : 'Sin teléfono'; ?><br>
                    <?php echo !empty($pedido['direccion']) ? htmlspecialchars($pedido['direccion']) : 'Dirección no registrada'; ?>
                </td>
            </tr>
        </table>

        <br>

        <table>
            <tr class="heading">
                <td>Producto</td>
                <td style="text-align: center;">Cant.</td>
                <td style="text-align: right;">Precio Unit.</td>
                <td style="text-align: right;">Total</td>
            </tr>

            <?php foreach($detalles as $item): ?>
            <tr class="item">
                <td><?php echo htmlspecialchars($item['nombre']); ?></td>
                <td style="text-align: center;"><?php echo $item['cantidad']; ?></td>
                <td style="text-align: right;">$<?php echo number_format($item['precio_unitario'], 2); ?></td>
                <td style="text-align: right;">$<?php echo number_format($item['subtotal'], 2); ?></td>
            </tr>
            <?php endforeach; ?>

            <tr class="total">
                <td colspan="3" style="text-align: right;">TOTAL A PAGAR:</td>
                <td style="text-align: right; color: #00B7C3; font-size: 1.2rem;">
                    $<?php echo number_format($pedido['total'], 2); ?>
                </td>
            </tr>
        </table>
        
        <br><br>
        <div style="text-align: center; color: #888; font-size: 0.85rem;">
            Gracias por su compra. Esta es una factura generada electrónicamente.
        </div>
    </div>
</body>
</html>