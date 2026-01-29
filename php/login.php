<?php
session_start();
require 'conexion.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if(isset($data)) {
    $email = trim($data['email'] ?? '');
    $pass = trim($data['pass'] ?? '');

    if (empty($email) || empty($pass)) {
        echo json_encode(["success" => false, "message" => "Datos incompletos"]);
        exit;
    }

    try {
        // CORRECCIÓN: Seleccionamos también el campo 'rol'
        $stmt = $conn->prepare("SELECT id_usuario, nombre, email, contrasena, rol, activo FROM Usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if (password_verify($pass, $user['contrasena'])) {
                // CORRECCIÓN: Asignamos el rol real de la base de datos
                $_SESSION['user_id'] = $user['id_usuario'];
                $_SESSION['user_name'] = $user['nombre'];
                $_SESSION['rol'] = $user['rol']; // Aquí estaba el error antes

                echo json_encode([
                    "success" => true, 
                    "rol" => $user['rol'], // Enviamos el rol al JS para que sepa dónde redirigir
                    "mensaje" => "Login exitoso"
                ]);
            } else {
                echo json_encode(["success" => false, "message" => "Contraseña incorrecta"]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Usuario no encontrado"]);
        }
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Error BD: " . $e->getMessage()]);
    }
}
?>