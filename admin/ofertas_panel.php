<?php
require '../php/conexion.php';
require 'includes/header.php';

// Seguridad
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    echo "<script>window.location.href='../index.php';</script>"; exit;
}

// CORRECCIÓN: Quitamos "WHERE activo = 1" porque esa columna no existe en tu BD
$productos = $conn->query("SELECT * FROM Productos ORDER BY id_producto DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="table-responsive" style="border-top: 4px solid #e67e22;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div>
            <h3 style="margin: 0; color: #e67e22;">🔥 Gestión de Ofertas</h3>
            <p style="margin: 5px 0 0; color: #666; font-size: 0.9rem;">Asigna precios especiales a tus productos existentes.</p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Precio Normal</th>
                <th>Precio Oferta</th>
                <th>Descuento</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($productos as $p): ?>
            <?php 
                // Calcular si tiene oferta válida
                $tieneOferta = ($p['precio_oferta'] > 0 && $p['precio_oferta'] < $p['precio']);
                $porcentaje = 0;
                if ($tieneOferta) {
                    $porcentaje = round((($p['precio'] - $p['precio_oferta']) / $p['precio']) * 100);
                }
            ?>
            <tr>
                <td style="display: flex; align-items: center; gap: 15px;">
                    <img src="../<?php echo htmlspecialchars($p['imagen']); ?>" style="width: 50px; height: 50px; object-fit: contain; background: white; border: 1px solid #eee; border-radius: 5px;">
                    <div>
                        <div style="font-weight: 600;"><?php echo htmlspecialchars($p['nombre']); ?></div>
                        <small style="color: #999;">Stock: <?php echo $p['stock']; ?></small>
                    </div>
                </td>
                
                <td style="font-weight: bold; color: #555;">
                    $<?php echo number_format($p['precio'], 2); ?>
                </td>

                <td>
                    <form action="../php/guardar_oferta.php" method="POST" style="display: flex; align-items: center; gap: 10px;">
                        <input type="hidden" name="id" value="<?php echo $p['id_producto']; ?>">
                        <input type="hidden" name="precio_normal" value="<?php echo $p['precio']; ?>">
                        
                        <div style="position: relative;">
                            <span style="position: absolute; left: 10px; top: 8px; color: #e67e22;">$</span>
                            <input type="number" step="0.01" name="oferta" 
                                   value="<?php echo ($p['precio_oferta'] > 0) ? $p['precio_oferta'] : ''; ?>" 
                                   placeholder="0.00" 
                                   style="width: 100px; padding: 8px 8px 8px 25px; border: 2px solid <?php echo $tieneOferta ? '#e67e22' : '#ddd'; ?>; border-radius: 5px; font-weight: bold; color: #333;">
                        </div>
                        
                        <button type="submit" class="btn-primary" style="background: #e67e22; padding: 8px 12px; font-size: 0.9rem;" title="Guardar">💾</button>
                        
                        <?php if($tieneOferta): ?>
                            <button type="submit" name="borrar_oferta" value="true" style="background: #dc3545; color: white; border: none; padding: 8px 12px; border-radius: 5px; cursor: pointer;" title="Quitar">✖</button>
                        <?php endif; ?>
                    </form>
                </td>

                <td>
                    <?php if ($tieneOferta): ?>
                        <span style="background: #e67e22; color: white; padding: 5px 10px; border-radius: 15px; font-weight: bold; font-size: 0.85rem;">
                            -<?php echo $porcentaje; ?>%
                        </span>
                    <?php else: ?>
                        <span style="color: #ccc; font-size: 0.85rem;">Normal</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require 'includes/footer.php'; ?>