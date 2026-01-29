<?php
/**
 * Verificación de Base de Datos
 * Accede a: http://localhost/Electrostore/Electrostore/php/verificar_bd.php
 */

require 'conexion.php';

echo "<!DOCTYPE html>";
echo "<html lang='es'>";
echo "<head>";
echo "<meta charset='UTF-8'>";
echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
echo "<title>Diagnóstico de BD</title>";
echo "<style>";
echo "body { font-family: Arial; margin: 20px; background: #f5f5f5; }";
echo ".container { max-width: 1000px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }";
echo ".success { background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin: 10px 0; }";
echo ".error { background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin: 10px 0; }";
echo ".info { background: #d1ecf1; color: #0c5460; padding: 15px; border-radius: 5px; margin: 10px 0; }";
echo "table { width: 100%; border-collapse: collapse; margin: 10px 0; }";
echo "table, th, td { border: 1px solid #ddd; }";
echo "th, td { padding: 10px; text-align: left; }";
echo "th { background: #002527; color: white; }";
echo "h2 { color: #002527; margin-top: 30px; }";
echo "</style>";
echo "</head>";
echo "<body>";
echo "<div class='container'>";
echo "<h1>🔧 Diagnóstico de Base de Datos</h1>";

try {
    echo "<div class='success'>✓ Conexión a Base de Datos: OK</div>";
    
    // Estructura de tabla
    echo "<h2>Estructura de Tabla 'Usuarios':</h2>";
    $stmt = $conn->prepare("DESCRIBE Usuarios");
    $stmt->execute();
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table>";
    echo "<tr><th>Campo</th><th>Tipo</th><th>Nulo</th><th>Clave</th><th>Default</th></tr>";
    foreach ($columns as $col) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($col['Field']) . "</td>";
        echo "<td>" . htmlspecialchars($col['Type']) . "</td>";
        echo "<td>" . htmlspecialchars($col['Null']) . "</td>";
        echo "<td>" . htmlspecialchars($col['Key']) . "</td>";
        echo "<td>" . htmlspecialchars($col['Default'] ?? '-') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Usuarios
    echo "<h2>Usuarios Registrados:</h2>";
    $stmt = $conn->prepare("SELECT id_usuario, nombre, email, activo FROM Usuarios");
    $stmt->execute();
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($usuarios) {
        echo "<table>";
        echo "<tr><th>ID</th><th>Nombre</th><th>Email</th><th>Activo</th></tr>";
        foreach ($usuarios as $user) {
            echo "<tr>";
            echo "<td>" . $user['id_usuario'] . "</td>";
            echo "<td>" . htmlspecialchars($user['nombre']) . "</td>";
            echo "<td>" . htmlspecialchars($user['email']) . "</td>";
            echo "<td>" . ($user['activo'] ? '✓ Sí' : '✗ No') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<div class='error'>⚠ No hay usuarios</div>";
    }
    
    echo "<h2>Opciones Disponibles:</h2>";
    echo "<div class='info'>";
    echo "• <a href='resetear_password.php'>Resetear contraseña de usuario</a><br>";
    echo "• <a href='../index.php'>Volver a la tienda</a>";
    echo "</div>";
    
} catch (Exception $e) {
    echo "<div class='error'>✗ Error: " . htmlspecialchars($e->getMessage()) . "</div>";
}

echo "</div></body></html>";
?>
