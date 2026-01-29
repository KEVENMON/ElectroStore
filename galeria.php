<?php 
require 'includes/header.php'; 
require 'php/conexion.php'; 
?>

<style>
    /* Estilos específicos para la galería de ofertas */
    .ofertas-banner {
        background: linear-gradient(135deg, #e67e22 0%, #d35400 100%);
        color: white;
        text-align: center;
        padding: 60px 20px;
        margin-bottom: 40px;
        border-radius: 0 0 20px 20px;
    }
    
    .oferta-badge {
        position: absolute; top: 15px; left: 15px;
        background: #e67e22; color: white;
        padding: 5px 12px; border-radius: 20px;
        font-weight: bold; font-size: 0.9rem;
        box-shadow: 0 4px 10px rgba(230, 126, 34, 0.4);
        z-index: 10;
    }

    .precio-antiguo { text-decoration: line-through; color: #999; font-size: 0.95rem; margin-right: 10px; }
    .precio-nuevo { color: #e67e22; font-weight: 800; font-size: 1.4rem; }
    
    .card-oferta { border: 2px solid transparent; transition: 0.3s; }
    .card-oferta:hover { border-color: #e67e22; transform: translateY(-5px); }
</style>

<div class="ofertas-banner">
    <h1 style="margin: 0; font-size: 3rem;">🔥 Ofertas Especiales 🔥</h1>
    <p style="font-size: 1.2rem; opacity: 0.9;">¡Precios increíbles por tiempo limitado!</p>
</div>

<div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
    
    <div class="products-grid">
        <?php
        try {
            // Consulta: Traer productos con precio_oferta válido
            // Quitamos 'activo = 1' porque no existe en tu BD
            $sql = "SELECT * FROM Productos WHERE precio_oferta > 0 AND precio_oferta < precio";
            $stmt = $conn->query($sql);
            $ofertas = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($ofertas) > 0) {
                foreach ($ofertas as $prod) {
                    $descuento = round((($prod['precio'] - $prod['precio_oferta']) / $prod['precio']) * 100);
                    $imgRuta = !empty($prod['imagen']) ? htmlspecialchars($prod['imagen']) : 'img/default.jpg';
        ?>
            <div class="product-card card-oferta">
                <span class="oferta-badge">-<?php echo $descuento; ?>%</span>
                
                <img src="<?php echo $imgRuta; ?>" alt="<?php echo htmlspecialchars($prod['nombre']); ?>">
                
                <div style="padding: 15px;">
                    <h3><?php echo htmlspecialchars($prod['nombre']); ?></h3>
                    
                    <div style="margin: 15px 0;">
                        <span class="precio-antiguo">$<?php echo number_format($prod['precio'], 2); ?></span>
                        <span class="precio-nuevo">$<?php echo number_format($prod['precio_oferta'], 2); ?></span>
                    </div>

                    <button class="carrito_button" 
                            style="background: #e67e22;"
                            onclick="agregarAlCarrito(this)">
                        ¡Aprovechar Oferta!
                    </button>

                    <div style="display:none;" class="product-data">
                        <span class="p-id"><?php echo $prod['id_producto']; ?></span>
                        <span class="p-name"><?php echo htmlspecialchars($prod['nombre']); ?></span>
                        <span class="p-price"><?php echo $prod['precio_oferta']; ?></span> </div>
                </div>
            </div>
        <?php 
                }
            } else {
                echo "<div style='grid-column: 1/-1; text-align: center; padding: 50px;'>
                        <div style='font-size: 4rem;'>😢</div>
                        <h3>No hay ofertas activas en este momento.</h3>
                        <p>Pero tenemos excelentes precios en nuestro catálogo general.</p>
                        <a href='tienda.php' style='display:inline-block; margin-top:20px; padding:10px 20px; background:#002527; color:white; text-decoration:none; border-radius:5px;'>Ir a la Tienda</a>
                      </div>";
            }
        } catch (PDOException $e) {
            echo "<p>Error al cargar ofertas.</p>";
        }
        ?>
    </div>
</div>

<div style="height: 50px;"></div>

<?php require 'includes/footer.php'; ?>