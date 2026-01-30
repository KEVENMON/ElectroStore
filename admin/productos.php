<?php
require '../php/conexion.php';
require 'includes/header.php';

// Seguridad
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    echo "<script>window.location.href='../index.php';</script>"; exit;
}

// Borrar producto
if (isset($_GET['borrar'])) {
    $stmt = $conn->prepare("DELETE FROM Productos WHERE id_producto = ?");
    $stmt->execute([$_GET['borrar']]);
    echo "<script>window.location.href='productos.php';</script>";
}

$productos = $conn->query("SELECT * FROM Productos ORDER BY id_producto DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
    .product-form-card {
        background: white;
        padding: 40px;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        margin-bottom: 50px;
        position: relative;
        overflow: hidden;
    }

    /* Adorno lateral de color */
    .product-form-card::before {
        content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 6px;
        background: linear-gradient(180deg, #00B7C3 0%, #005f66 100%);
    }

    .form-header h3 { margin: 0; color: #002527; font-size: 1.5rem; }
    .form-header p { margin: 5px 0 20px 0; color: #888; font-size: 0.9rem; }

    /* Estilos de los Inputs */
    .input-wrapper { margin-bottom: 20px; position: relative; }
    .input-label { display: block; font-weight: 600; color: #444; margin-bottom: 8px; font-size: 0.9rem; }
    
    .styled-input {
        width: 100%;
        padding: 12px 15px;
        padding-left: 40px; /* Espacio para el icono */
        border: 2px solid #f0f0f0;
        border-radius: 10px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: #f9f9f9;
        color: #333;
    }

    .styled-input:focus {
        border-color: #00B7C3;
        background: white;
        box-shadow: 0 5px 15px rgba(0, 183, 195, 0.1);
        outline: none;
    }

    /* Iconos dentro de los inputs */
    .input-icon {
        position: absolute; left: 15px; top: 42px; /* Ajustar según label */
        color: #aaa; transition: 0.3s;
    }
    .styled-input:focus + .input-icon, .styled-input:focus ~ .input-icon { color: #00B7C3; }

    /* Zona de Carga de Imagen (Upload Box) */
    .upload-box {
        border: 2px dashed #ddd;
        border-radius: 12px;
        padding: 30px;
        text-align: center;
        cursor: pointer;
        transition: 0.3s;
        background: #fafafa;
        position: relative;
    }
    .upload-box:hover { border-color: #00B7C3; background: #f0fbfc; }
    
    .upload-icon { font-size: 2rem; color: #ccc; margin-bottom: 10px; display: block; }
    .upload-text { color: #666; font-size: 0.9rem; }
    
    /* El input real está invisible pero funcional sobre la caja */
    .file-input-hidden {
        position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer;
    }

    /* Botón Guardar */
    .btn-save-product {
        background: #002527; color: white; border: none; padding: 15px 30px;
        border-radius: 10px; font-weight: 600; font-size: 1rem; cursor: pointer;
        transition: 0.3s; width: 100%; display: flex; align-items: center; justify-content: center; gap: 10px;
    }
    .btn-save-product:hover { background: #00B7C3; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.2); }

    /* Layout en Grid */
    .form-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 30px; }
    @media(max-width: 768px) { .form-grid { grid-template-columns: 1fr; } }
</style>

<div class="product-form-card">
    <div class="form-header">
        <h3>✨ Nuevo Producto</h3>
        <p>Completa la información para agregar al inventario.</p>
    </div>

    <form action="../php/guardar_producto.php" method="POST" enctype="multipart/form-data">
        <div class="form-grid">
            
            <div>
                <div class="input-wrapper">
                    <label class="input-label">Nombre del Producto</label>
                    <input type="text" name="nombre" class="styled-input" placeholder="Ej: Refrigeradora LG Instaview" required>
                    <span class="input-icon">🏷️</span>
                </div>

                <div style="display: flex; gap: 20px;">
                    <div class="input-wrapper" style="flex: 1;">
                        <label class="input-label">Precio ($)</label>
                        <input type="number" step="0.01" name="precio" class="styled-input" placeholder="0.00" required>
                        <span class="input-icon">💲</span>
                    </div>

                    <div class="input-wrapper" style="flex: 1;">
                        <label class="input-label">Stock Inicial</label>
                        <input type="number" name="stock" class="styled-input" value="10" required>
                        <span class="input-icon">📦</span>
                    </div>
                </div>
            </div>

            <div>
                <label class="input-label">Imagen del Producto</label>
                <div class="upload-box">
                    <input type="file" name="imagen" accept="image/*" class="file-input-hidden" required onchange="previewFile(this)">
                    <span class="upload-icon">☁️</span>
                    <span class="upload-text" id="uploadText">Arrastra una imagen o<br><b>haz clic aquí</b></span>
                </div>
            </div>

        </div>

        <button type="submit" class="btn-save-product">
            <span>💾</span> Guardar y Publicar
        </button>
    </form>
</div>

<div class="table-responsive">
    <h3 style="margin-top:0; color:#002527; margin-bottom:20px; display:flex; align-items:center; gap:10px;">
        📦 Inventario Actual
    </h3>
    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($productos as $p): ?>
            <tr>
                <td style="display: flex; align-items: center; gap: 15px;">
                    <img src="../<?php echo htmlspecialchars($p['imagen']); ?>" style="width:50px; height:50px; object-fit:contain; border-radius:8px; border:1px solid #eee; background: white;">
                    <span style="font-weight:600; color:#333;"><?php echo htmlspecialchars($p['nombre']); ?></span>
                </td>
                <td style="color:#00B7C3; font-weight:bold;">$<?php echo number_format($p['precio'], 2); ?></td>
                <td>
                    <span class="status-badge <?php echo ($p['stock'] > 5) ? 'status-success' : 'status-warning'; ?>">
                        <?php echo $p['stock']; ?> un.
                    </span>
                </td>
                <td>
                    <button class="btn-primary btn-sm btn-edit" 
                            onclick="abrirModalEditar('<?php echo $p['id_producto']; ?>', '<?php echo addslashes($p['nombre']); ?>', '<?php echo $p['precio']; ?>', '<?php echo $p['stock']; ?>')">
                        ✏️
                    </button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div id="modalEditar" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="cerrarModal()">&times;</span>
        <h2 style="color: #002527;">✏️ Editar Producto</h2>
        <form action="../php/actualizar_producto.php" method="POST">
            <input type="hidden" name="id" id="edit_id">
            <div style="margin-bottom:15px;">
                <label style="display:block; font-weight:bold; margin-bottom:5px;">Nombre</label>
                <input type="text" name="nombre" id="edit_nombre" required style="width:100%; padding:10px; border:1px solid #ddd; border-radius:8px;">
            </div>
            <div style="display:flex; gap:15px; margin-bottom:20px;">
                <div style="flex:1;">
                    <label style="display:block; font-weight:bold; margin-bottom:5px;">Precio</label>
                    <input type="number" step="0.01" name="precio" id="edit_precio" required style="width:100%; padding:10px; border:1px solid #ddd; border-radius:8px;">
                </div>
                <div style="flex:1;">
                    <label style="display:block; font-weight:bold; margin-bottom:5px;">Stock</label>
                    <input type="number" name="stock" id="edit_stock" required style="width:100%; padding:10px; border:1px solid #ddd; border-radius:8px;">
                </div>
            </div>
            <button type="submit" class="btn-primary" style="background:#ffc107; color:#333; width:100%; padding:12px; font-weight:bold;">Guardar Cambios</button>
        </form>
    </div>
</div>

<script>
    // Preview pequeño al seleccionar archivo
    function previewFile(input) {
        var file = input.files[0];
        if(file){
            document.getElementById('uploadText').innerHTML = "📷 " + file.name;
            input.parentElement.style.borderColor = "#00B7C3";
            input.parentElement.style.backgroundColor = "#e6fbfc";
        }
    }

    function abrirModalEditar(id, nombre, precio, stock) {
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_nombre').value = nombre;
        document.getElementById('edit_precio').value = precio;
        document.getElementById('edit_stock').value = stock;
        document.getElementById('modalEditar').style.display = 'flex';
    }
    function cerrarModal() { document.getElementById('modalEditar').style.display = 'none'; }
    window.onclick = function(event) { if (event.target == document.getElementById('modalEditar')) cerrarModal(); }
</script>

<style>
.modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); align-items: center; justify-content: center; backdrop-filter: blur(4px); }
.modal-content { background-color: white; padding: 30px; border-radius: 20px; width: 90%; max-width: 500px; box-shadow: 0 20px 50px rgba(0,0,0,0.3); position: relative; animation: slideUp 0.3s ease; }
@keyframes slideUp { from { transform: translateY(50px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
.close-btn { position: absolute; top: 20px; right: 25px; font-size: 25px; cursor: pointer; color:#999; }
</style>

<?php require 'includes/footer.php'; ?>