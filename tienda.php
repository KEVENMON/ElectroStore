<?php 
require 'includes/header.php'; 
require 'php/conexion.php'; 

// 1. Obtener precio máximo para el slider
$stmtMax = $conn->query("SELECT MAX(precio) as max_price FROM Productos");
$rowMax = $stmtMax->fetch(PDO::FETCH_ASSOC);
$maxPrice = ($rowMax && $rowMax['max_price']) ? ceil($rowMax['max_price']) : 1000;
?>

<style>
    /* --- ESTRUCTURA PRINCIPAL --- */
    .shop-layout {
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 40px;
        max-width: 1400px;
        margin: 40px auto;
        padding: 0 20px;
        align-items: start;
    }

    /* --- SIDEBAR DE FILTROS --- */
    .filters-sidebar {
        background: white;
        padding: 25px;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        position: sticky;
        top: 20px; 
        border: 1px solid #eee;
    }

    .filter-group { margin-bottom: 25px; border-bottom: 1px solid #eee; padding-bottom: 20px; }
    .filter-group:last-child { border: none; }
    .filter-title { font-size: 1.1rem; font-weight: bold; color: #002527; margin-bottom: 15px; display: block; }

    /* Inputs y Controles */
    .filter-search { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; margin-bottom: 15px; }
    .price-slider { width: 100%; cursor: pointer; accent-color: #00B7C3; }
    .checkbox-label { display: flex; align-items: center; gap: 10px; margin-bottom: 10px; cursor: pointer; user-select: none; }
    .checkbox-label input { accent-color: #00B7C3; width: 18px; height: 18px; cursor: pointer; }

    /* --- CLASE CRÍTICA PARA EL FILTRADO --- */
    /* Esta clase TIENE que tener !important para sobreescribir el display:flex del header */
    .product-hidden {
        display: none !important;
    }

    /* Mensaje sin resultados */
    #no-results {
        display: none;
        text-align: center;
        grid-column: 1 / -1;
        padding: 50px;
        background: #f9f9f9;
        border-radius: 10px;
    }

    @media (max-width: 900px) {
        .shop-layout { grid-template-columns: 1fr; }
        .filters-sidebar { position: static; margin-bottom: 30px; }
    }
</style>

<main>
    <div style="background: #f8f9fa; padding: 40px; text-align: center; border-bottom: 1px solid #eee;">
        <h1 style="color: #002527; margin: 0;">Catálogo de Productos</h1>
        <p style="color: #666;">Encuentra el electrodoméstico ideal</p>
    </div>

    <div class="shop-layout">
        
        <aside class="filters-sidebar">
            
            <div class="filter-group">
                <label class="filter-title">Nombre del producto</label>
                <input type="text" id="searchInput" class="filter-search" placeholder="Ej: Refrigeradora...">
            </div>

            <div class="filter-group">
                <label class="filter-title">
                    Precio Máximo: <span id="priceValue" style="color:#00B7C3; font-weight:800;">$<?php echo $maxPrice; ?></span>
                </label>
                <input type="range" id="priceRange" class="price-slider" min="0" max="<?php echo $maxPrice; ?>" value="<?php echo $maxPrice; ?>">
            </div>

            <div class="filter-group">
                <label class="filter-title">Disponibilidad</label>
                <label class="checkbox-label">
                    <input type="checkbox" class="stock-check" value="in_stock" checked> 
                    <span>Solo Disponibles</span>
                </label>
                <label class="checkbox-label">
                    <input type="checkbox" class="stock-check" value="out_stock" checked> 
                    <span>Ver Agotados</span>
                </label>
            </div>

            <div class="filter-group">
                <label class="filter-title">Ordenar</label>
                <select id="sortOrder" style="width:100%; padding:10px; border-radius:5px; border:1px solid #ddd;">
                    <option value="default">Relevancia</option>
                    <option value="low_high">Precio: Menor a Mayor</option>
                    <option value="high_low">Precio: Mayor a Menor</option>
                </select>
            </div>

            <button onclick="resetFilters()" style="width:100%; padding:12px; background:#002527; color:white; border:none; border-radius:8px; cursor:pointer; font-weight:bold;">
                Limpiar Filtros
            </button>
        </aside>

        <section>
            <div class="products-grid" id="productsGrid">
                <?php
                try {
                    $stmt = $conn->query("SELECT * FROM Productos ORDER BY id_producto DESC");
                    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if (count($productos) > 0) {
                        foreach ($productos as $prod) {
                            $hayStock = $prod['stock'] > 0;
                            $colorStock = $hayStock ? "#28a745" : "#dc3545";
                            $textoStock = $hayStock ? "Stock: " . $prod['stock'] : "Agotado";
                            $imgRuta = !empty($prod['imagen']) ? htmlspecialchars($prod['imagen']) : 'img/default.jpg';
                            
                            // Normalización de datos para el filtro JS
                            // Convertimos el nombre a minúsculas y quitamos caracteres raros
                            $dataName = strtolower(strip_tags($prod['nombre'])); 
                            
                            // Quitamos cualquier caracter que no sea número del precio por si acaso
                            $dataPrice = preg_replace('/[^0-9.]/', '', $prod['precio']);
                            
                            $dataStock = $hayStock ? "in_stock" : "out_stock";
                ?>
                    <div class="product-card" 
                         data-price="<?php echo $dataPrice; ?>" 
                         data-name="<?php echo $dataName; ?>"
                         data-stock="<?php echo $dataStock; ?>">
                        
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
                                <button class="carrito_button" disabled style="background:#ccc; cursor:not-allowed;">Sin Stock</button>
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
                        echo "<p>No hay productos en la base de datos.</p>";
                    }
                } catch (PDOException $e) { echo "<p>Error de conexión.</p>"; }
                ?>
                
                <div id="no-results">
                    <div style="font-size: 3rem;">🔍</div>
                    <h3>No encontramos coincidencias</h3>
                    <p>Intenta ajustar el precio o el nombre de búsqueda.</p>
                </div>
            </div>
        </section>
    </div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const searchInput = document.getElementById('searchInput');
        const priceRange = document.getElementById('priceRange');
        const priceValue = document.getElementById('priceValue');
        const stockChecks = document.querySelectorAll('.stock-check');
        const sortSelect = document.getElementById('sortOrder');
        const grid = document.getElementById('productsGrid');
        const noResults = document.getElementById('no-results');
        
        // Obtenemos todas las tarjetas al cargar
        const cards = Array.from(document.querySelectorAll('.product-card'));

        // Normalizador de texto (Quita tildes: canción -> cancion)
        const normalize = (str) => str.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();

        function filterProducts() {
            const searchText = normalize(searchInput.value);
            const maxPrice = parseFloat(priceRange.value);
            
            // Obtener qué checkboxes de stock están marcados
            const activeStocks = Array.from(stockChecks)
                                      .filter(c => c.checked)
                                      .map(c => c.value);

            let visibleCount = 0;

            cards.forEach(card => {
                // Leer datos
                const name = normalize(card.dataset.name);
                const price = parseFloat(card.dataset.price);
                const stock = card.dataset.stock;

                // Lógica de validación
                const isNameMatch = name.includes(searchText);
                const isPriceMatch = price <= maxPrice;
                const isStockMatch = activeStocks.includes(stock);

                // IMPORTANTE: Usamos classList para ocultar/mostrar
                // Esto es lo que soluciona el conflicto con el header.php
                if (isNameMatch && isPriceMatch && isStockMatch) {
                    card.classList.remove('product-hidden'); // Mostrar
                    visibleCount++;
                } else {
                    card.classList.add('product-hidden'); // Ocultar
                }
            });

            // Mostrar u ocultar mensaje de "No resultados"
            if (visibleCount === 0) {
                noResults.style.display = 'block';
            } else {
                noResults.style.display = 'none';
            }
        }

        function sortProducts() {
            const order = sortSelect.value;
            
            // Filtramos solo las tarjetas visibles para ordenar (opcional, aquí ordenamos todas)
            const sortedCards = cards.sort((a, b) => {
                const priceA = parseFloat(a.dataset.price);
                const priceB = parseFloat(b.dataset.price);

                if (order === 'low_high') return priceA - priceB;
                if (order === 'high_low') return priceB - priceA;
                return 0; // Orden original (o aleatorio si no hay ID)
            });

            // Reordenar en el DOM
            sortedCards.forEach(card => {
                grid.insertBefore(card, noResults); // Insertamos antes del div de "no results"
            });
        }

        // EVENTOS
        searchInput.addEventListener('input', filterProducts);
        
        priceRange.addEventListener('input', (e) => {
            priceValue.textContent = '$' + e.target.value;
            filterProducts();
        });

        stockChecks.forEach(ch => ch.addEventListener('change', filterProducts));

        sortSelect.addEventListener('change', () => {
            sortProducts();
            filterProducts(); // Re-aplicar filtro visual por si acaso
        });
    });

    function resetFilters() {
        document.getElementById('searchInput').value = '';
        
        const slider = document.getElementById('priceRange');
        slider.value = slider.max;
        document.getElementById('priceValue').textContent = '$' + slider.max;
        
        document.querySelectorAll('.stock-check').forEach(c => c.checked = true);
        document.getElementById('sortOrder').value = 'default';
        
        // Disparar evento para actualizar
        document.getElementById('searchInput').dispatchEvent(new Event('input'));
    }
</script>

<?php require 'includes/footer.php'; ?>