<?php
session_start();
require '../php/conexion.php';
require 'includes/header.php';

// Seguridad
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    echo "<script>window.location.href='../index.php';</script>"; exit;
}

// Lógica de Borrado
if (isset($_GET['borrar'])) {
    $id_borrar = $_GET['borrar'];
    try {
        $conn->beginTransaction();
        $conn->prepare("DELETE FROM Detalles_Pedidos WHERE id_producto = ?")->execute([$id_borrar]);
        $conn->prepare("DELETE FROM Productos WHERE id_producto = ?")->execute([$id_borrar]);
        $conn->commit();
        echo "<script>window.location.href='productos.php';</script>";
    } catch (PDOException $e) {
        $conn->rollBack();
        echo "<script>alert('Error');</script>";
    }
}

// Consultar productos
$productos = $conn->query("SELECT * FROM Productos ORDER BY id_producto DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="product-form-card" style="background:white; padding:30px; border-radius:15px; margin-bottom:30px; border-top:4px solid #00B7C3; box-shadow:0 5px 15px rgba(0,0,0,0.05);">
    <h3 style="margin-top:0; color:#002527;">➕ Nuevo Producto</h3>
    <form action="../php/guardar_producto.php" method="POST" enctype="multipart/form-data">
        <div style="display:flex; gap:20px; margin-bottom:15px;">
            <div style="flex:2;">
                <label style="font-weight:bold;">Nombre</label>
                <input type="text" name="nombre" required style="width:100%; padding:10px; border:1px solid #ddd; border-radius:5px;">
            </div>
            <div style="flex:1;">
                <label style="font-weight:bold;">Precio Normal ($)</label>
                <input type="number" step="0.01" name="precio" required style="width:100%; padding:10px; border:1px solid #ddd; border-radius:5px;">
            </div>
            <div style="flex:1;">
                <label style="font-weight:bold; color:#e67e22;">Precio Oferta ($)</label>
                <input type="number" step="0.01" name="precio_oferta" placeholder="Opcional" value="0" style="width:100%; padding:10px; border:1px solid #ddd; border-radius:5px; border-color:#e67e22;">
            </div>
        </div>
        <div style="display:flex; gap:20px; margin-bottom:15px;">
            <div style="flex:1;">
                <label style="font-weight:bold;">Stock</label>
                <input type="number" name="stock" required value="10" style="width:100%; padding:10px; border:1px solid #ddd; border-radius:5px;">
            </div>
            <div style="flex:2;">
                <label style="font-weight:bold;">Imagen</label>
                <input type="file" name="imagen" accept="image/*" required style="width:100%;">
            </div>
        </div>
        <button type="submit" class="btn-primary" style="width:100%; background:#002527; color:white; padding:12px; border:none; border-radius:5px; font-weight:bold; cursor:pointer;">Guardar Producto</button>
    </form>
</div>

<div class="table-responsive" style="background:white; padding:20px; border-radius:15px; box-shadow:0 5px 15px rgba(0,0,0,0.05);">
    <h3 style="margin-top:0; color:#002527;">📦 Inventario</h3>
    <table style="width:100%; border-collapse:collapse;">
        <thead>
            <tr style="background:#f8f9fa; text-align:left;">
                <th style="padding:15px;">Producto</th>
                <th>Precio</th>
                <th>Oferta</th> <th>Stock</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($productos as $p): ?>
            <tr style="border-bottom:1px solid #eee;">
                <td style="padding:10px; display:flex; align-items:center; gap:10px;">
                    <img src="../<?php echo htmlspecialchars($p['imagen']); ?>" style="width:40px; height:40px; object-fit:contain;">
                    <?php echo htmlspecialchars($p['nombre']); ?>
                </td>
                <td style="font-weight:bold;">$<?php echo number_format($p['precio'], 2); ?></td>
                <td>
                    <?php if($p['precio_oferta'] > 0 && $p['precio_oferta'] < $p['precio']): ?>
                        <span style="background:#e67e22; color:white; padding:3px 8px; border-radius:10px; font-size:0.8rem; font-weight:bold;">
                            $<?php echo number_format($p['precio_oferta'], 2); ?>
                        </span>
                    <?php else: ?>
                        <span style="color:#ccc;">-</span>
                    <?php endif; ?>
                </td>
                <td><?php echo $p['stock']; ?></td>
                <td>
                    <button class="btn-primary" style="padding:5px 10px; background:#ffc107; border:none; border-radius:5px; cursor:pointer;"
                            onclick="abrirModalEditar(
                                '<?php echo $p['id_producto']; ?>', 
                                '<?php echo addslashes($p['nombre']); ?>', 
                                '<?php echo $p['precio']; ?>', 
                                '<?php echo $p['precio_oferta']; ?>', 
                                '<?php echo $p['stock']; ?>'
                            )">
                        ✏️
                    </button>
                    <a href="productos.php?borrar=<?php echo $p['id_producto']; ?>" style="padding:5px 10px; background:#dc3545; color:white; text-decoration:none; border-radius:5px;" onclick="return confirm('¿Eliminar?');">🗑️</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div id="modalEditar" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); justify-content:center; align-items:center; z-index:1000;">
    <div style="background:white; padding:30px; width:90%; max-width:500px; border-radius:15px; border-top:5px solid #ffc107; position:relative;">
        <span onclick="document.getElementById('modalEditar').style.display='none'" style="position:absolute; top:15px; right:20px; font-size:25px; cursor:pointer;">&times;</span>
        <h2 style="margin-top:0;">✏️ Editar Producto</h2>
        
        <form action="../php/actualizar_producto.php" method="POST">
            <input type="hidden" name="id" id="edit_id">
            
            <div style="margin-bottom:15px;">
                <label style="font-weight:bold;">Nombre</label>
                <input type="text" name="nombre" id="edit_nombre" required style="width:100%; padding:10px; border:1px solid #ddd; border-radius:5px;">
            </div>

            <div style="display:flex; gap:15px; margin-bottom:15px;">
                <div style="flex:1;">
                    <label style="font-weight:bold;">Precio Normal</label>
                    <input type="number" step="0.01" name="precio" id="edit_precio" required style="width:100%; padding:10px; border:1px solid #ddd; border-radius:5px;">
                </div>
                <div style="flex:1;">
                    <label style="font-weight:bold; color:#e67e22;">Precio Oferta</label>
                    <input type="number" step="0.01" name="precio_oferta" id="edit_precio_oferta" value="0" style="width:100%; padding:10px; border:1px solid #ddd; border-radius:5px; border-color:#e67e22;">
                    <small style="color:#888; font-size:0.75rem;">Pon 0 para quitar oferta</small>
                </div>
            </div>

            <div style="margin-bottom:20px;">
                <label style="font-weight:bold;">Stock</label>
                <input type="number" name="stock" id="edit_stock" required style="width:100%; padding:10px; border:1px solid #ddd; border-radius:5px;">
            </div>

            <button type="submit" style="width:100%; padding:12px; background:#ffc107; border:none; font-weight:bold; cursor:pointer; border-radius:5px;">Guardar Cambios</button>
        </form>
    </div>
</div>

<script>
    function abrirModalEditar(id, nombre, precio, precioOferta, stock) {
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_nombre').value = nombre;
        document.getElementById('edit_precio').value = precio;
        document.getElementById('edit_precio_oferta').value = precioOferta;
        document.getElementById('edit_stock').value = stock;
        document.getElementById('modalEditar').style.display = 'flex';
    }
</script>

<?php require 'includes/footer.php'; ?>