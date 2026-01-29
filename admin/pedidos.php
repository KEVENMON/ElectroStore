<?php
require '../php/conexion.php';
require 'includes/header.php'; // Cargamos el diseño unificado

// Seguridad
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    echo "<script>window.location.href='../index.php';</script>"; exit;
}

// Consultar Pedidos con datos del Cliente
$sql = "SELECT p.id_pedido, p.fecha_pedido, p.total, p.estado, u.nombre, u.email 
        FROM Pedidos p 
        JOIN Usuarios u ON p.id_usuario = u.id_usuario 
        ORDER BY p.fecha_pedido DESC";
$pedidos = $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="table-responsive">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="margin: 0; color: #002527;">📦 Gestión de Pedidos</h3>
        <span style="background: #e9ecef; padding: 5px 10px; border-radius: 5px; font-size: 0.9rem; color: #666;">
            Total Registros: <strong><?php echo count($pedidos); ?></strong>
        </span>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nro. Orden</th>
                <th>Cliente</th>
                <th>Fecha</th>
                <th>Total</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if(count($pedidos) > 0): ?>
                <?php foreach ($pedidos as $p): ?>
                <tr>
                    <td style="font-weight: bold;">#<?php echo str_pad($p['id_pedido'], 5, "0", STR_PAD_LEFT); ?></td>
                    <td>
                        <div style="font-weight: 600; color: #333;"><?php echo htmlspecialchars($p['nombre']); ?></div>
                        <div style="font-size: 0.85rem; color: #888;"><?php echo htmlspecialchars($p['email']); ?></div>
                    </td>
                    <td><?php echo date('d/m/Y H:i', strtotime($p['fecha_pedido'])); ?></td>
                    <td style="font-weight: bold; color: #00B7C3;">$<?php echo number_format($p['total'], 2); ?></td>
                    <td>
                        <?php 
                            $claseEstado = 'status-warning'; // Por defecto amarillo (Pendiente)
                            if ($p['estado'] == 'Completado' || $p['estado'] == 'Enviado') {
                                $claseEstado = 'status-success'; // Verde
                            } elseif ($p['estado'] == 'Cancelado') {
                                $claseEstado = 'btn-delete'; // Rojo (usamos la clase de borrar que es roja)
                            }
                        ?>
                        <span class="status-badge <?php echo $claseEstado; ?>">
                            <?php echo $p['estado']; ?>
                        </span>
                    </td>
                    <td>
                        <a href="../php/descargar_factura.php?pedido=<?php echo $p['id_pedido']; ?>" target="_blank" class="btn-primary btn-sm" style="text-decoration: none; display: inline-flex; align-items: center; gap: 5px;">
                            📄 Factura
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align: center; padding: 30px; color: #999;">
                        No hay pedidos registrados todavía.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require 'includes/footer.php'; ?>