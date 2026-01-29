<?php
session_start();
require 'conexion.php';

// Verificar que vengan datos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $accion = $_POST['accion'];

    // --- LOGICA DE LOGIN ---
    if ($accion === 'login') {
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        if (empty($email) || empty($password)) {
            header("Location: ../login.php?error=vacio");
            exit();
        }

        // Buscar usuario
        $stmt = $conn->prepare("SELECT * FROM Usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verificar contraseña (usamos password_verify para seguridad)
        // NOTA: Si creaste usuarios manualmente en la BD sin encriptar, esto fallará.
        // Asegúrate de crear usuarios nuevos desde el registro.php
        if ($usuario && password_verify($password, $usuario['password'])) {
            
            // ¡LOGIN EXITOSO!
            $_SESSION['user_id'] = $usuario['id_usuario'];
            $_SESSION['user_name'] = $usuario['nombre'];
            $_SESSION['user_email'] = $usuario['email'];
            $_SESSION['user_rol'] = $usuario['rol']; // 'admin' o 'cliente'

            // Redirección según rol
            if ($usuario['rol'] === 'admin') {
                // Si es admin, va al panel (lo crearemos luego)
                header("Location: ../admin/dashboard.php");
            } else {
                // Si es cliente, va a la tienda
                header("Location: ../index.php");
            }
            exit();

        } else {
            header("Location: ../login.php?error=credenciales");
            exit();
        }
    }

    // --- LOGICA DE REGISTRO ---
    if ($accion === 'registro') {
        $nombre = trim($_POST['nombre']);
        $email = trim($_POST['email']);
        $telefono = trim($_POST['telefono']);
        $password = trim($_POST['password']);
        
        // Encriptar contraseña
        $passHash = password_hash($password, PASSWORD_DEFAULT);
        $rol = 'cliente'; // Por defecto todos son clientes

        try {
            // Verificar si el correo ya existe
            $check = $conn->prepare("SELECT id_usuario FROM Usuarios WHERE email = ?");
            $check->execute([$email]);
            if ($check->rowCount() > 0) {
                echo "<script>alert('Este correo ya está registrado.'); window.location.href='../registro.php';</script>";
                exit();
            }

            // Insertar nuevo usuario
            $stmt = $conn->prepare("INSERT INTO Usuarios (nombre, email, password, telefono, rol) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$nombre, $email, $passHash, $telefono, $rol]);

            // Iniciar sesión automáticamente
            $_SESSION['user_id'] = $conn->lastInsertId();
            $_SESSION['user_name'] = $nombre;
            $_SESSION['user_email'] = $email;
            $_SESSION['user_rol'] = $rol;

            header("Location: ../index.php"); // Enviar a la tienda
            exit();

        } catch (PDOException $e) {
            die("Error en registro: " . $e->getMessage());
        }
    }
} else {
    header("Location: ../index.php");
}
?>