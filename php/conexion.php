<?php
$host = "localhost";
$db = "tienda";
$user = "root";
$pass = ""; // <--- AQUÍ: Borra "Erick@02" y déjalo vacío

try {
    $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>