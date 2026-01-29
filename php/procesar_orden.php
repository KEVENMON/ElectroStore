<?php
session_start();
require 'conexion.php';
header('Content-Type: application/json');

// Leer el JSON que envía el JavaScript
$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!$data) {
    echo json_encode(['success' => false, 'message' => 'No se recibieron datos']);
    exit;
}

// Verificar sesión
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuario no autenticado']);
    exit;
}

$id_usuario = $_SESSION['user_id'];
$carrito = $data['carrito'];
$total = $data['total'];
$direccion = $data['direccion_entrega'];
$telefono = $data['telefono'];

try {
    $conn->beginTransaction();

    // 1. Actualizar datos de contacto del usuario
    $stmtUser = $conn->prepare("UPDATE Usuarios SET telefono = ?, direccion = ? WHERE id_usuario = ?");
    $stmtUser->execute([$telefono, $direccion, $id_usuario]);

    // 2. Crear Pedido
    $stmtPedido = $conn->prepare("INSERT INTO Pedidos (id_usuario, total, estado, notas) VALUES (?, ?, 'Pendiente', 'Pedido web')");
    $stmtPedido->execute([$id_usuario, $total]);
    $id_pedido = $conn->lastInsertId();

    // 3. Guardar detalles
    $stmtDetalle = $conn->prepare("INSERT INTO Detalles_Pedidos (id_pedido, id_producto, cantidad, precio_unitario, subtotal) VALUES (?, ?, ?, ?, ?)");
    
    // Preparar query para descontar stock
    $stmtStock = $conn->prepare("UPDATE Productos SET stock = stock - ? WHERE id_producto = ?");

    foreach ($carrito as $item) {
        // Tu JS usa 'id' o a veces no lo tiene, intenta buscar el ID correcto
        // Asegúrate de que tu carrito tenga el ID del producto, si no, fallará.
        // En tienda.php agregamos <span class="p-id"> para esto.
        
        // Si el JS antiguo no guardaba ID, intentamos buscar por nombre (menos seguro pero funcional para rescate)
        $id_prod = 0;
        if (isset($item['id'])) {
            $id_prod = $item['id'];
        } else {
            // Buscamos ID por nombre si no viene en el carrito
            $stmtBuscar = $conn->prepare("SELECT id_producto FROM Productos WHERE nombre = ? LIMIT 1");
            $stmtBuscar->execute([$item['nombre']]);
            $prod = $stmtBuscar->fetch(PDO::FETCH_ASSOC);
            $id_prod = $prod ? $prod['id_producto'] : 0;
        }

        if ($id_prod > 0) {
            $subtotal = $item['precio'] * $item['cantidad'];
            $stmtDetalle->execute([$id_pedido, $id_prod, $item['cantidad'], $item['precio'], $subtotal]);
            
            // Descontar Stock
            $stmtStock->execute([$item['cantidad'], $id_prod]);
        }
    }

    $conn->commit();
    
    // Guardar para la confirmación
    $_SESSION['pedido_exitoso'] = true;
    $_SESSION['id_pedido'] = $id_pedido;

    echo json_encode(['success' => true, 'orden_id' => $id_pedido]);

} catch (Exception $e) {
    $conn->rollBack();
    echo json_encode(['success' => false, 'message' => 'Error en BD: ' . $e->getMessage()]);
}
?>