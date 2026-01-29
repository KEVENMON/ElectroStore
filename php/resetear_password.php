<?php
/**
 * Script para resetear/actualizar contraseña de un usuario
 * Uso: Accede a http://localhost/Electrostore/Electrostore/php/resetear_password.php
 * y proporciona el email y nueva contraseña
 */

require 'conexion.php';

echo "<!DOCTYPE html>";
echo "<html lang='es'>";
echo "<head>";
echo "<meta charset='UTF-8'>";
echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
echo "<title>Resetear Contraseña</title>";
echo "<style>";
echo "body { font-family: Arial; margin: 20px; background: linear-gradient(135deg, #002527 0%, #004a50 100%); }";
echo ".container { max-width: 500px; margin: 50px auto; background: white; padding: 40px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.2); }";
echo "h1 { color: #002527; text-align: center; }";
echo ".form-group { margin-bottom: 20px; }";
echo "label { display: block; font-weight: bold; color: #333; margin-bottom: 5px; }";
echo "input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px; }";
echo "button { width: 100%; padding: 12px; background: #00B7C3; color: white; border: none; border-radius: 5px; font-weight: bold; cursor: pointer; transition: 0.3s; }";
echo "button:hover { background: #009099; }";
echo ".success { background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin: 10px 0; }";
echo ".error { background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin: 10px 0; }";
echo ".info { background: #d1ecf1; color: #0c5460; padding: 15px; border-radius: 5px; margin: 10px 0; }";
echo "</style>";
echo "</head>";
echo "<body>";
echo "<div class='container'>";
echo "<h1>🔐 Resetear Contraseña</h1>";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $nueva_pass = trim($_POST['nueva_pass'] ?? '');
    $confirmar_pass = trim($_POST['confirmar_pass'] ?? '');
    
    // Validaciones
    if (empty($email) || empty($nueva_pass) || empty($confirmar_pass)) {
        echo "<div class='error'>✗ Todos los campos son requeridos</div>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<div class='error'>✗ Email inválido</div>";
    } elseif ($nueva_pass !== $confirmar_pass) {
        echo "<div class='error'>✗ Las contraseñas no coinciden</div>";
    } elseif (strlen($nueva_pass) < 6) {
        echo "<div class='error'>✗ La contraseña debe tener al menos 6 caracteres</div>";
    } else {
        try {
            // Verificar si el usuario existe
            $stmt = $conn->prepare("SELECT id_usuario, nombre FROM Usuarios WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user) {
                // Hashear la nueva contraseña
                $pass_hashed = password_hash($nueva_pass, PASSWORD_DEFAULT);
                
                // Actualizar contraseña
                $updateStmt = $conn->prepare("UPDATE Usuarios SET contrasena = ? WHERE email = ?");
                $updateStmt->execute([$pass_hashed, $email]);
                
                echo "<div class='success'>";
                echo "✓ Contraseña actualizada exitosamente para: " . htmlspecialchars($user['nombre']) . "<br>";
                echo "Ahora puedes iniciar sesión con la nueva contraseña.";
                echo "</div>";
            } else {
                echo "<div class='error'>✗ Usuario no encontrado con el email: " . htmlspecialchars($email) . "</div>";
            }
        } catch (PDOException $e) {
            echo "<div class='error'>✗ Error en la base de datos: " . htmlspecialchars($e->getMessage()) . "</div>";
        }
    }
}
?>

<form method="POST">
    <div class="form-group">
        <label for="email">Email del Usuario:</label>
        <input type="email" id="email" name="email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
    </div>
    
    <div class="form-group">
        <label for="nueva_pass">Nueva Contraseña:</label>
        <input type="password" id="nueva_pass" name="nueva_pass" required placeholder="Mínimo 6 caracteres">
    </div>
    
    <div class="form-group">
        <label for="confirmar_pass">Confirmar Contraseña:</label>
        <input type="password" id="confirmar_pass" name="confirmar_pass" required placeholder="Repite la contraseña">
    </div>
    
    <button type="submit">Actualizar Contraseña</button>
</form>

<div class="info">
    <strong>Instrucciones:</strong><br>
    1. Ingresa el email del usuario (ej: johan@gmail.com)<br>
    2. Ingresa la nueva contraseña deseada<br>
    3. Confirma la contraseña<br>
    4. Haz clic en "Actualizar Contraseña"<br>
    5. Prueba el login con la nueva contraseña
</div>

<?php
echo "</div>";
echo "</body>";
echo "</html>";
?>
