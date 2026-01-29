<?php
session_start();
require '../php/conexion.php';

// Seguridad
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    header("Location: ../index.php"); exit();
}

// Validar que venga un ID
if (!isset($_GET['id'])) {
    header("Location: productos.php"); exit();
}

$id = $_GET['id'];

// Obtener datos del producto
$stmt = $conn->prepare("SELECT * FROM Productos WHERE id_producto = ?");
$stmt->execute([$id]);
$producto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$producto) die("Producto no encontrado.");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto - ElectroAdmin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f4f7f6; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; }
        .edit-container { background: white; padding: 40px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); width: 100%; max-width: 500px; border-top: 5px solid #ffc107; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; color: #333; }
        .form-group input { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        .btn-save { background: #00B7C3; color: white; border: none; padding: 15px; width: 100%; border-radius: 5px; font-weight: bold; cursor: pointer; font-size: 1rem; }
        .btn-cancel { display: block; text-align: center; margin-top: 15px; text-decoration: none; color: #666; }
    </style>
</head>
<body>

    <div class="edit-container">
        <h2 style="margin-top:0; color:#002527;">✏️ Editar Producto</h2>
        
        <form action="../php/actualizar_producto.php" method="POST">
            
            <input type="hidden" name="id" value="<?php echo $producto['id_producto']; ?>">

            <div class="form-group">
                <label>Nombre del Producto</label>
                <input type="text" name="nombre" value="<?php echo htmlspecialchars($producto['nombre']); ?>" required>
            </div>

            <div style="display: flex; gap: 15px;">
                <div class="form-group" style="flex:1;">
                    <label>Precio ($)</label>
                    <input type="number" step="0.01" name="precio" value="<?php echo $producto['precio']; ?>" required>
                </div>
                <div class="form-group" style="flex:1;">
                    <label>Stock</label>
                    <input type="number" name="stock" value="<?php echo $producto['stock']; ?>" required>
                </div>
            </div>

            <button type="submit" class="btn-save">Guardar Cambios</button>
            <a href="productos.php" class="btn-cancel">Cancelar</a>
        </form>
    </div>

</body>
</html>