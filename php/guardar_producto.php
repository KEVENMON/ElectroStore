<?php
session_start();
require 'conexion.php';

// Verificar que sea admin
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    die("Acceso denegado");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock']; // Recibimos el stock
    
    // Manejo de la imagen
    $nombre_img = $_FILES['imagen']['name'];
    $tmp_img = $_FILES['imagen']['tmp_name'];
    $carpeta_destino = "../uploads/";
    
    // Crear carpeta si no existe
    if (!file_exists($carpeta_destino)) {
        mkdir($carpeta_destino, 0777, true);
    }
    
    $ruta_final = $carpeta_destino . basename($nombre_img);
    $ruta_bd = "uploads/" . basename($nombre_img); // Ruta relativa para la BD

    if (move_uploaded_file($tmp_img, $ruta_final)) {
        // Insertar en BD incluyendo el stock
        try {
            $stmt = $conn->prepare("INSERT INTO Productos (nombre, precio, stock, imagen) VALUES (?, ?, ?, ?)");
            if ($stmt->execute([$nombre, $precio, $stock, $ruta_bd])) {
                echo "<script>
                        alert('Producto agregado correctamente con Stock: $stock');
                        window.location.href='../admin.php';
                      </script>";
            } else {
                echo "Error al guardar en base de datos.";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Error al subir la imagen.";
    }
}
?>