<?php 
require 'includes/header.php'; 
require 'php/conexion.php'; 
?>

<section class="hero-section" style="background: linear-gradient(135deg, #002527 0%, #004a50 100%); color: white; padding: 100px 20px; text-align: center; margin-bottom: 50px;">
    <h1 style="font-size: 3.5rem; margin-bottom: 20px;">Bienvenido a ElectroStore</h1>
    <p style="font-size: 1.3rem; opacity: 0.9;">Tecnología y confort para tu hogar al mejor precio</p>
</section>

<main>
    <section class="featured-products">
        <div style="text-align: center; margin-bottom: 50px;">
            <h2 style="color: #002527; font-size: 2.5rem; display: inline-block; border-bottom: 4px solid #00B7C3; padding-bottom: 10px;">Nuevos Ingresos</h2>
        </div>
        
        <div class="products-grid">
            <?php
            try {
                // Traer 8 productos para mostrar 2 filas de 4
                $stmt = $conn->query("SELECT * FROM Productos ORDER BY id_producto DESC LIMIT 8");
                $productosHome = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (count($productosHome) > 0) {
                    foreach ($productosHome as $prod) {
                        $hayStock = $prod['stock'] > 0;
                        $colorStock = $hayStock ? "#28a745" : "#dc3545";
                        $textoStock = $hayStock ? "Stock: " . $prod['stock'] : "Agotado";
                        $imgRuta = !empty($prod['imagen']) ? htmlspecialchars($prod['imagen']) : 'img/default.jpg';
            ?>
                <div class="product-card">
                    <img src="<?php echo $imgRuta; ?>" alt="<?php echo htmlspecialchars($prod['nombre']); ?>">
                    
                    <div> <h3><?php echo htmlspecialchars($prod['nombre']); ?></h3>
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
                }
            } catch (PDOException $e) { echo "<p>Error al cargar productos.</p>"; }
            ?>
        </div>
    </section>
</main>

<?php require 'includes/footer.php'; ?>