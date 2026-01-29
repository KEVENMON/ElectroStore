<?php
session_start();
require 'conexion.php';

// Verificar si hay usuario logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Verificar si se enviaron datos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Obtener datos del formulario
    $id_usuario = $_SESSION['user_id'];
    $nombre = $_POST['nombre'] ?? '';
    $direccion = $_POST['direccion'] ?? '';
    $ciudad = $_POST['ciudad'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $metodo_pago = "Tarjeta"; // Simulado
    
    // Obtener el carrito (viene como JSON string desde el input hidden)
    $carritoJson = $_POST['carrito'] ?? '[]';
    $carrito = json_decode($carritoJson, true);

    if (empty($carrito)) {
        die("Error: El carrito está vacío.");
    }

    try {
        // INICIAR TRANSACCIÓN (Para asegurar que se guarde todo o nada)
        $conn->beginTransaction();

        // 1. Calcular total real (seguridad del lado del servidor)
        $total = 0;
        foreach ($carrito as $item) {
            $total += $item['precio'] * $item['cantidad'];
        }
        $total_con_envio = $total * 1.10 + 5; // +10% IVA + $5 Envío

        // 2. Insertar el Pedido
        $stmtPedido = $conn->prepare("INSERT INTO Pedidos (id_usuario, fecha_pedido, total, estado) VALUES (?, NOW(), ?, 'Completado')");
        $stmtPedido->execute([$id_usuario, $total_con_envio]);
        $id_pedido = $conn->lastInsertId();

        // 3. Insertar Detalles y Actualizar Stock
        $stmtDetalle = $conn->prepare("INSERT INTO Detalles_Pedidos (id_pedido, id_producto, cantidad, precio_unitario, subtotal) VALUES (?, ?, ?, ?, ?)");
        $stmtStock = $conn->prepare("UPDATE Productos SET stock = stock - ? WHERE id_producto = ?");

        foreach ($carrito as $item) {
            // Verificar ID del producto (asumiendo que en el JSON viene como 'id_producto' o puedes obtenerlo del nombre si es necesario, pero idealmente el ID debe venir del JS)
            // NOTA: Asegúrate que tu JS en carrito.php envíe el ID del producto. 
            // Si tu JS usa 'id', cámbialo aquí.
            $id_prod = $item['id'] ?? $item['id_producto']; 
            $cant = $item['cantidad'];
            $precio = $item['precio'];
            $subtotal = $precio * $cant;

            // Guardar detalle
            $stmtDetalle->execute([$id_pedido, $id_prod, $cant, $precio, $subtotal]);

            // Restar stock
            $stmtStock->execute([$cant, $id_prod]);
        }

        // CONFIRMAR TRANSACCIÓN
        $conn->commit();

        // Guardar ID de pedido en sesión para mostrarlo en la confirmación
        $_SESSION['id_pedido'] = $id_pedido;

        // Redirigir a la página de éxito
        header("Location: ../confirmacion_pago.php?pedido=" . $id_pedido);
        exit();

    } catch (Exception $e) {
        // Si algo falla, deshacer cambios
        $conn->rollBack();
        die("Error al procesar el pedido: " . $e->getMessage());
    }

} else {
    header("Location: ../index.php");
}
?>