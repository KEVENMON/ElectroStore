<?php
session_start();
require 'conexion.php';

// 1. Seguridad: Verificar que sea Admin
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

// 2. Verificar que se enviaron datos por POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Obtener los datos del formulario modal
    $id = $_POST['id'];
    $nombre = trim($_POST['nombre']);
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];

    // Validar que no estén vacíos
    if (empty($id) || empty($nombre) || empty($precio) || empty($stock)) {
        // Si falta algo, regresamos con error (opcional)
        header("Location: ../admin/productos.php?error=vacio");
        exit();
    }

    try {
        // 3. Actualizar en la Base de Datos
        // NOTA: No actualizamos la imagen aquí, solo datos de texto/números
        $sql = "UPDATE Productos SET nombre = ?, precio = ?, stock = ? WHERE id_producto = ?";
        $stmt = $conn->prepare($sql);
        
        if ($stmt->execute([$nombre, $precio, $stock, $id])) {
            // ÉXITO: Regresar a la lista de productos
            header("Location: ../admin/productos.php?msg=editado");
            exit();
        } else {
            echo "Error al actualizar el producto.";
        }

    } catch (PDOException $e) {
        echo "Error de base de datos: " . $e->getMessage();
    }

} else {
    // Si intentan entrar directo al archivo sin enviar formulario
    header("Location: ../admin/productos.php");
    exit();
}
?>