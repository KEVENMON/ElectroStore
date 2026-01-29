<?php 
require 'includes/header.php'; 
require 'php/conexion.php'; 

// Obtener la búsqueda, limpiando espacios
$busqueda = isset($_GET['q']) ? trim($_GET['q']) : '';
?>

<main>
    <div class="products-container">
        <div style="background: #f8f9fa; padding: 30px; border-radius: 15px; text-align: center; margin-bottom: 40px;">
            <h1 style="color: #002527; margin-bottom: 10px; font-size: 2rem;">Resultados de Búsqueda</h1>
            <?php if($busqueda): ?>
                <p style="color: #666; font-size: 1.2rem;">Mostrando resultados para: <strong>"<?php echo htmlspecialchars($busqueda); ?>"</strong></p>
            <?php else: ?>
                <p style="color: #666;">Por favor ingresa un término para buscar.</p>
            <?php endif; ?>
        </div>
        
        <div class="products-grid">
            <?php
            if ($busqueda) {
                try {
                    // Buscar por nombre O descripción (usamos %comodines%)
                    $stmt = $conn->prepare("SELECT * FROM Productos WHERE nombre LIKE ? OR descripcion LIKE ?");
                    $termino = "%" . $busqueda . "%";
                    $stmt->execute([$termino, $termino]);
                    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if (count($resultados) > 0) {
                        foreach ($resultados as $prod) {
                            $hayStock = $prod['stock'] > 0;
                            $colorStock = $hayStock ? "#28a745" : "#dc3545";
                            $textoStock = $hayStock ? "Stock: " . $prod['stock'] : "Agotado";
                            $imgRuta = !empty($prod['imagen']) ? htmlspecialchars($prod['imagen']) : 'img/default.jpg';
            ?>
                <div class="product-card">
                    <img src="<?php echo $imgRuta; ?>" alt="<?php echo htmlspecialchars($prod['nombre']); ?>">
                    
                    <div>
                        <h3><?php echo htmlspecialchars($prod['nombre']); ?></h3>
                        <p class="price">$<?php echo number_format($prod['precio'], 2); ?></p>
                        <p class="stock-info" style="color: <?php echo $colorStock; ?>"><?php echo $textoStock; ?></p>
                    </div>

                    <div class="card-buttons">
                        <?php if ($hayStock): ?>
                            <button class="carrito_button">Agregar al Carrito</button>
                        <?php else: ?>
                            <button class="carrito_button" disabled>Sin Stock</button>
                        <?php endif; ?>
                    </div>

                    <div style="display:none;" class="product-data">
                        <span class="p-id"><?php echo $prod['id_producto']; ?></span>
                        <span class="p-name"><?php echo htmlspecialchars($prod['nombre']); ?></span>
                        <span class="p-price"><?php echo $prod['precio']; ?></span>
                    </div>
                </div>
            <?php 
                        }
                    } else {
                        // DISEÑO PARA "NO ENCONTRADO"
                        echo "
                        <div style='grid-column: 1/-1; text-align: center; padding: 50px;'>
                            <div style='font-size: 4rem; margin-bottom: 20px;'>😕</div>
                            <h3 style='color: #555;'>No encontramos productos que coincidan con tu búsqueda.</h3>
                            <p>Intenta con otra palabra o <a href='tienda.php' style='color:#00B7C3; font-weight:bold;'>mira todo nuestro catálogo</a>.</p>
                        </div>";
                    }
                } catch (PDOException $e) { 
                    echo "<p>Error técnico en la búsqueda.</p>"; 
                }
            }
            ?>
        </div>
    </div>
</main>

<?php require 'includes/footer.php'; ?>