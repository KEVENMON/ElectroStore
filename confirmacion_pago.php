<?php
session_start();
// 1. Usamos el Header Global (Importante para el estilo general)
require 'includes/header.php';
require 'php/conexion.php';

// Obtener ID del pedido
$id_pedido = $_GET['pedido'] ?? $_SESSION['id_pedido'] ?? null;

if (!$id_pedido) {
    echo "<div style='text-align:center; padding:50px;'><h2>Error: Pedido no encontrado.</h2><a href='index.php' class='btn'>Volver</a></div>";
    require 'includes/footer.php';
    exit();
}

// Consultar datos del pedido
$stmt = $conn->prepare("SELECT p.*, u.nombre, u.email, u.telefono, u.direccion, u.ciudad 
                        FROM Pedidos p 
                        JOIN Usuarios u ON p.id_usuario = u.id_usuario 
                        WHERE p.id_pedido = ?");
$stmt->execute([$id_pedido]);
$pedido = $stmt->fetch(PDO::FETCH_ASSOC);

// Consultar detalles (productos)
$stmtDet = $conn->prepare("SELECT dp.*, pr.nombre, pr.imagen 
                           FROM Detalles_Pedidos dp 
                           JOIN Productos pr ON dp.id_producto = pr.id_producto 
                           WHERE dp.id_pedido = ?");
$stmtDet->execute([$id_pedido]);
$detalles = $stmtDet->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
    body { background-color: #f4f7f6; }

    .confirmation-container {
        max-width: 800px;
        margin: 50px auto;
        padding: 0 20px;
    }

    /* Tarjeta Principal (Estilo Ticket) */
    .invoice-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        overflow: hidden;
        border-top: 5px solid #28a745; /* Línea verde de éxito */
    }

    /* Cabecera de Éxito */
    .success-banner {
        padding: 40px 20px;
        text-align: center;
        background: #fdfdfd;
        border-bottom: 1px dashed #eee;
    }

    /* Animación del Icono */
    @keyframes popIn {
        0% { transform: scale(0); opacity: 0; }
        80% { transform: scale(1.1); opacity: 1; }
        100% { transform: scale(1); }
    }
    
    .success-icon {
        font-size: 4rem;
        color: #28a745;
        margin-bottom: 15px;
        display: inline-block;
        animation: popIn 0.6s cubic-bezier(0.68, -0.55, 0.27, 1.55) forwards;
    }

    .success-banner h1 { margin: 0; color: #002527; font-size: 2rem; }
    .success-banner p { color: #666; margin-top: 10px; font-size: 1.1rem; }

    /* Cuerpo de la Factura */
    .invoice-body { padding: 40px; }

    /* Grid de Información */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        margin-bottom: 30px;
        background: #f9f9f9;
        padding: 20px;
        border-radius: 10px;
    }
    .info-item h4 { margin: 0 0 5px 0; font-size: 0.85rem; color: #888; text-transform: uppercase; letter-spacing: 1px; }
    .info-item p { margin: 0; color: #333; font-weight: 600; font-size: 1.05rem; }

    /* Tabla de Productos */
    .invoice-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
    .invoice-table th { text-align: left; color: #999; font-weight: normal; padding: 10px 0; border-bottom: 1px solid #eee; }
    .invoice-table td { padding: 15px 0; border-bottom: 1px solid #f5f5f5; color: #333; }
    .invoice-table td.price { text-align: right; font-weight: bold; }
    
    /* Totales */
    .invoice-total {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 2px solid #002527;
    }
    .total-label { font-size: 1.2rem; margin-right: 20px; color: #555; }
    .total-amount { font-size: 2rem; color: #00B7C3; font-weight: 800; }

    /* Botones de Acción */
    .actions-footer {
        background: #f9f9f9;
        padding: 20px;
        text-align: center;
        display: flex;
        justify-content: center;
        gap: 20px;
    }

    .btn-print {
        background: #002527;
        color: white;
        padding: 12px 25px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: bold;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        transition: 0.3s;
    }
    .btn-print:hover { background: #004a50; transform: translateY(-2px); }

    .btn-home {
        background: white;
        color: #002527;
        border: 1px solid #002527;
        padding: 12px 25px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: bold;
        transition: 0.3s;
    }
    .btn-home:hover { background: #f0f0f0; }

    @media (max-width: 600px) {
        .info-grid { grid-template-columns: 1fr; }
        .invoice-body { padding: 20px; }
        .actions-footer { flex-direction: column; }
    }
</style>

<main>
    <div class="confirmation-container">
        
        <div class="invoice-card">
            <div class="success-banner">
                <div class="success-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                </div>
                <h1>¡Pago Exitoso!</h1>
                <p>Gracias por tu compra, <?php echo htmlspecialchars($pedido['nombre']); ?>.</p>
                <p style="font-size: 0.9rem; margin-top: 5px;">Hemos enviado un correo de confirmación a <strong><?php echo htmlspecialchars($pedido['email']); ?></strong></p>
            </div>

            <div class="invoice-body">
                <div class="info-grid">
                    <div class="info-item">
                        <h4>Número de Orden</h4>
                        <p>#<?php echo str_pad($id_pedido, 6, "0", STR_PAD_LEFT); ?></p>
                    </div>
                    <div class="info-item">
                        <h4>Fecha</h4>
                        <p><?php echo date('d/m/Y H:i', strtotime($pedido['fecha_pedido'])); ?></p>
                    </div>
                    <div class="info-item">
                        <h4>Dirección de Envío</h4>
                        <p><?php echo htmlspecialchars($pedido['direccion']); ?><br><span style="font-weight:normal; font-size:0.9rem;"><?php echo htmlspecialchars($pedido['ciudad']); ?></span></p>
                    </div>
                    <div class="info-item">
                        <h4>Método de Pago</h4>
                        <p>Tarjeta de Crédito ••••</p>
                    </div>
                </div>

                <table class="invoice-table">
                    <thead>
                        <tr>
                            <th width="60%">Producto</th>
                            <th width="10%">Cant.</th>
                            <th width="30%" style="text-align:right;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($detalles as $d): ?>
                        <tr>
                            <td>
                                <span style="font-weight:600; display:block;"><?php echo htmlspecialchars($d['nombre']); ?></span>
                                <span style="font-size:0.85rem; color:#888;">Electrodoméstico</span>
                            </td>
                            <td>x<?php echo $d['cantidad']; ?></td>
                            <td class="price">$<?php echo number_format($d['subtotal'], 2); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="invoice-total">
                    <span class="total-label">TOTAL PAGADO</span>
                    <span class="total-amount">$<?php echo number_format($pedido['total'], 2); ?></span>
                </div>
            </div>

            <div class="actions-footer">
                <a href="php/descargar_factura.php?pedido=<?php echo $id_pedido; ?>&print=true" target="_blank" class="btn-print">
                    🖨️ Descargar Factura PDF
                </a>
                <a href="index.php" class="btn-home">
                    Seguir Comprando
                </a>
            </div>
        </div>

    </div>
</main>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Borramos el carrito solo si la página cargó con éxito
        if (localStorage.getItem('carrito')) {
            localStorage.removeItem('carrito');
            // Actualizar contador del header a 0 visualmente
            const btnCarrito = document.getElementById('btnCarrito');
            if(btnCarrito) btnCarrito.innerText = "Mi carrito (0)";
        }
    });
</script>

<?php require 'includes/footer.php'; ?>