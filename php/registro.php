<?php
// Archivo: php/registro.php
session_start();
require 'conexion.php'; 
header('Content-Type: application/json');

// Recibir JSON del JS
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Fallback por si llega por formulario normal
if (!$data) {
    $data = $_POST;
}

// Validar datos obligatorios
if (!isset($data['nombre']) || !isset($data['email']) || !isset($data['pass'])) {
    echo json_encode(["success" => false, "message" => "Faltan datos por llenar."]);
    exit;
}

$nombre = trim($data['nombre']);
$email = trim($data['email']);
$pass = trim($data['pass']);
$telefono = isset($data['telefono']) ? trim($data['telefono']) : '';

try {
    // 1. Verificar si el correo existe
    $stmt = $conn->prepare("SELECT id_usuario FROM Usuarios WHERE email = ?");
    $stmt->execute([$email]);
    
    if ($stmt->rowCount() > 0) {
        echo json_encode(["success" => false, "message" => "El correo ya está registrado."]);
        exit;
    }

    // 2. Insertar nuevo usuario
    // - Usamos 'contrasena' (como en tu imagen)
    // - Usamos 'fecha_creacion' (como en tu imagen)
    // - Ponemos 'activo' en 1 por defecto
    $passHash = password_hash($pass, PASSWORD_DEFAULT);
    
    $sql = "INSERT INTO Usuarios (nombre, email, contrasena, telefono, rol, activo, fecha_creacion) VALUES (?, ?, ?, ?, 'cliente', 1, NOW())";
    $insert = $conn->prepare($sql);
    
    if ($insert->execute([$nombre, $email, $passHash, $telefono])) {
        echo json_encode(["success" => true, "message" => "Cuenta creada exitosamente."]);
    } else {
        echo json_encode(["success" => false, "message" => "Error al guardar en la base de datos."]);
    }

} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Error de sistema: " . $e->getMessage()]);
}
?>