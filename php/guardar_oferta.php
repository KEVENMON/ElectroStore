<?php
session_start();
require 'conexion.php';

// Seguridad
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    header("Location: ../index.php"); exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $precio_normal = $_POST['precio_normal'];
    
    // Verificar si se presionó el botón de borrar (X)
    if (isset($_POST['borrar_oferta'])) {
        $oferta = 0;
    } else {
        $oferta = $_POST['oferta'];
    }

    // Validaciones
    if ($oferta >= $precio_normal) {
        // Si la oferta es mayor al precio, la anulamos (ponemos 0)
        $oferta = 0; 
    }
    
    if ($oferta < 0) $oferta = 0;

    try {
        $stmt = $conn->prepare("UPDATE Productos SET precio_oferta = ? WHERE id_producto = ?");
        $stmt->execute([$oferta, $id]);
        
        // Volver al panel de ofertas
        header("Location: ../admin/ofertas_panel.php?msg=guardado");
        exit();

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    header("Location: ../admin/ofertas_panel.php");
}
?>