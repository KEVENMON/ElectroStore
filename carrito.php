<?php 
require 'includes/header.php'; 
?>

<style>
    body { background-color: #f4f7f6; }

    .cart-page-container { 
        max-width: 1200px; 
        margin: 40px auto; 
        padding: 0 20px; 
    }

    /* BARRA DE PROGRESO */
    .checkout-steps {
        display: flex;
        justify-content: center;
        margin-bottom: 40px;
    }
    .step {
        display: flex;
        align-items: center;
        color: #ccc;
        font-weight: bold;
        font-size: 1.1rem;
    }
    .step.active { color: #002527; }
    .step .num {
        width: 35px; height: 35px;
        background: #eee;
        color: #999;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        margin-right: 10px;
        font-weight: bold;
    }
    .step.active .num { background: #00B7C3; color: white; }
    .step-line {
        width: 60px; height: 3px; background: #eee; margin: 0 15px;
    }

    /* GRID PRINCIPAL */
    .cart-content { display: grid; grid-template-columns: 2fr 1fr; gap: 30px; }

    /* LISTA DE PRODUCTOS */
    .cart-items { 
        background: white; 
        padding: 30px; 
        border-radius: 15px; 
        box-shadow: 0 5px 20px rgba(0,0,0,0.05); 
    }
    
    .cart-header-title {
        font-size: 1.5rem; color: #002527; margin-bottom: 20px; 
        border-bottom: 1px solid #eee; padding-bottom: 15px;
    }

    .cart-item { 
        display: grid; 
        grid-template-columns: 80px 1fr auto auto; 
        gap: 20px; 
        align-items: center; 
        padding-bottom: 20px; 
        margin-bottom: 20px; 
        border-bottom: 1px solid #f0f0f0;
    }
    .cart-item:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
    
    .cart-item img { 
        width: 80px; height: 80px; object-fit: contain; 
        border-radius: 8px; border: 1px solid #eee; padding: 5px;
    }
    
    .item-details h3 { font-size: 1.1rem; color: #333; margin-bottom: 5px; }
    .item-details p { color: #888; font-size: 0.9rem; }
    
    /* CONTROLES CANTIDAD */
    .quantity-control { 
        display: flex; align-items: center; border: 1px solid #ddd; border-radius: 5px; overflow: hidden; 
    }
    .quantity-control button { 
        background: #f9f9f9; color: #333; border: none; width: 30px; height: 30px; cursor: pointer; font-weight: bold; transition: 0.2s;
    }
    .quantity-control button:hover { background: #e0e0e0; }
    .quantity-control input { 
        width: 40px; text-align: center; border: none; font-weight: bold; color: #002527; 
    }

    .item-price { font-weight: bold; color: #00B7C3; font-size: 1.1rem; }
    
    .remove-btn { 
        background: none; border: none; color: #dc3545; cursor: pointer; font-size: 1.2rem; transition: 0.2s; 
    }
    .remove-btn:hover { color: #a71d2a; transform: scale(1.1); }

    /* RESUMEN (DERECHA) */
    .cart-summary { 
        background: white; 
        padding: 30px; 
        border-radius: 15px; 
        box-shadow: 0 5px 20px rgba(0,0,0,0.05); 
        height: fit-content; 
        position: sticky; top: 20px; 
    }
    
    .summary-row { display: flex; justify-content: space-between; margin-bottom: 15px; color: #555; }
    .summary-row.total { 
        border-top: 2px dashed #eee; 
        padding-top: 20px; 
        margin-top: 20px;
        font-size: 1.4rem; 
        font-weight: 800; 
        color: #002527; 
    }
    
    .promo-code { display: flex; gap: 10px; margin-bottom: 20px; }
    .promo-code input { 
        flex: 1; padding: 10px; border: 1px solid #ddd; border-radius: 5px; outline: none; 
    }
    .promo-code button {
        padding: 10px 15px; background: #333; color: white; border: none; border-radius: 5px; cursor: pointer;
    }

    .checkout-btn { 
        width: 100%; 
        background: linear-gradient(135deg, #00B7C3 0%, #009099 100%); 
        color: white; border: none; 
        padding: 15px; font-size: 1.1rem; 
        border-radius: 8px; cursor: pointer; 
        font-weight: bold; text-align: center; 
        box-shadow: 0 4px 15px rgba(0, 183, 195, 0.3);
        transition: transform 0.2s;
    }
    .checkout-btn:hover { transform: translateY(-2px); }

    .trust-badges { 
        margin-top: 20px; text-align: center; font-size: 0.85rem; color: #999; 
    }
    .trust-icons { font-size: 1.5rem; letter-spacing: 10px; margin-top: 5px; opacity: 0.7; }

    /* ESTADO VACÍO */
    .empty-cart-state { text-align: center; padding: 50px 20px; }
    .empty-icon { font-size: 4rem; color: #eee; margin-bottom: 20px; }
    .btn-shop { 
        background: #002527; color: white; padding: 10px 25px; border-radius: 5px; text-decoration: none; display: inline-block; margin-top: 20px; 
    }

    /* FORMULARIO CHECKOUT */
    #checkout-view { display: none; }
    .form-section-title { color: #002527; margin: 20px 0 15px; font-size: 1.2rem; border-left: 4px solid #00B7C3; padding-left: 10px; }
    .input-group { margin-bottom: 15px; }
    .input-group label { display: block; margin-bottom: 5px; font-weight: 600; color: #555; }
    .input-group input { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; background: #f9f9f9; }
    .input-group input:focus { border-color: #00B7C3; background: white; outline: none; }
    .input-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    
    .back-btn { background: none; border: none; color: #666; cursor: pointer; display: flex; align-items: center; gap: 5px; margin-bottom: 20px; font-size: 1rem; }
    .back-btn:hover { color: #00B7C3; }

    @media (max-width: 900px) {
        .cart-content { grid-template-columns: 1fr; }
        .cart-summary { position: static; }
    }
</style>

<main>
    <div class="cart-page-container">
        
        <div class="checkout-steps">
            <div class="step active" id="step1">
                <div class="num">1</div> Carrito
            </div>
            <div class="step-line"></div>
            <div class="step" id="step2">
                <div class="num">2</div> Envío y Pago
            </div>
            <div class="step-line"></div>
            <div class="step">
                <div class="num">3</div> Confirmación
            </div>
        </div>

        <div id="cart-view">
            <div class="cart-content">
                <div class="cart-items">
                    <h2 class="cart-header-title">Productos en tu bolsa</h2>
                    <div id="cart-items-list">
                        </div>
                </div>

                <div class="cart-summary">
                    <h2 class="cart-header-title" style="font-size: 1.3rem;">Resumen del Pedido</h2>

                    <div class="summary-row"><span>Subtotal</span><span id="subtotal">$0.00</span></div>
                    <div class="summary-row"><span>Impuestos (10%)</span><span id="tax">$0.00</span></div>
                    <div class="summary-row"><span>Envío Estimado</span><span id="shipping">$5.00</span></div>
                    <div class="summary-row total"><span>Total</span><span id="total">$0.00</span></div>
                    
                    <button class="checkout-btn" id="btn-go-checkout">Continuar Compra →</button>
                    
                    <div class="trust-badges">
                        <p>Pagos 100% Seguros</p>
                        <div class="trust-icons">💳 🔒 🛡️</div>
                        <p style="margin-top:10px; font-size:0.75rem;">Garantía de devolución de 30 días</p>
                    </div>
                </div>
            </div>
        </div>

        <div id="checkout-view">
            <button class="back-btn" id="btn-back-cart">← Volver al Carrito</button>
            
            <div class="cart-content">
                <div class="cart-items">
                    <h2 class="cart-header-title">Detalles de Facturación</h2>
                    <form id="formPago" method="POST" action="php/procesar_pago.php">
                        <input type="hidden" name="carrito" id="carritoJson">
                        
                        <h3 class="form-section-title">📍 ¿Dónde enviamos tu pedido?</h3>
                        <div class="input-group">
                            <label>Nombre Completo</label>
                            <input type="text" name="nombre" required value="<?php echo $_SESSION['user_name'] ?? ''; ?>">
                        </div>
                        <div class="input-row">
                            <div class="input-group"><label>Email</label><input type="email" name="email" required value="<?php echo $_SESSION['user_email'] ?? ''; ?>"></div>
                            <div class="input-group"><label>Teléfono</label><input type="tel" name="telefono" required placeholder="099..."></div>
                        </div>
                        <div class="input-group"><label>Dirección de Envío</label><input type="text" name="direccion" required placeholder="Calle principal, número y sector"></div>
                        <div class="input-row">
                            <div class="input-group"><label>Ciudad</label><input type="text" name="ciudad" required></div>
                            <div class="input-group"><label>Código Postal</label><input type="text" name="codigo_postal" required></div>
                        </div>

                        <h3 class="form-section-title">💳 Método de Pago Seguro</h3>
                        <div class="input-group">
                            <label>Número de Tarjeta</label>
                            <input type="text" name="tarjeta" required placeholder="0000 0000 0000 0000" maxlength="19">
                        </div>
                        <div class="input-row">
                            <div class="input-group"><label>Vencimiento</label><input type="text" name="vencimiento" required placeholder="MM/YY" maxlength="5"></div>
                            <div class="input-group"><label>CVV</label><input type="text" name="cvv" required placeholder="123" maxlength="4"></div>
                        </div>
                        <div class="input-group">
                            <label>Nombre en la Tarjeta</label>
                            <input type="text" name="titular" required>
                        </div>

                        <button type="submit" class="checkout-btn" style="margin-top: 20px;">Confirmar y Pagar <span id="btn-total-amount"></span></button>
                    </form>
                </div>

                <div class="cart-summary">
                    <h3 style="margin-bottom:15px;">En tu carrito</h3>
                    <div id="mini-cart-summary" style="margin-bottom:20px; font-size:0.9rem; color:#666;">
                        </div>
                    <div class="summary-row total" style="border-top:none; margin-top:0;">
                        <span>Total a Pagar</span><span id="checkout-total-display">$0.00</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</main>

<script>
    // ESTADO DE SESIÓN
    const usuarioLogueado = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;

    document.addEventListener('DOMContentLoaded', () => {
        cargarCarrito();
        
        const viewCart = document.getElementById('cart-view');
        const viewCheckout = document.getElementById('checkout-view');
        const step1 = document.getElementById('step1');
        const step2 = document.getElementById('step2');
        
        document.getElementById('btn-go-checkout').addEventListener('click', () => {
            const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
            if (carrito.length === 0) return alert('Tu carrito está vacío');
            
            if (!usuarioLogueado) {
                alert("Por favor inicia sesión para continuar con la compra.");
                window.location.href = 'login.php';
                return;
            }

            // Cambiar vista
            viewCart.style.display = 'none';
            viewCheckout.style.display = 'block';
            
            // Actualizar pasos visuales
            step1.classList.remove('active');
            step2.classList.add('active');
            
            window.scrollTo(0,0);
            
            // Actualizar montos en checkout
            const totalText = document.getElementById('total').textContent;
            document.getElementById('checkout-total-display').textContent = totalText;
            document.getElementById('btn-total-amount').textContent = totalText;

            // Llenar mini resumen
            const miniList = document.getElementById('mini-cart-summary');
            miniList.innerHTML = carrito.map(item => `
                <div style="display:flex; justify-content:space-between; margin-bottom:5px;">
                    <span>${item.cantidad}x ${item.nombre}</span>
                    <span>$${(item.precio * item.cantidad).toFixed(2)}</span>
                </div>
            `).join('');
        });

        document.getElementById('btn-back-cart').addEventListener('click', () => {
            viewCheckout.style.display = 'none';
            viewCart.style.display = 'block';
            step2.classList.remove('active');
            step1.classList.add('active');
        });

        // ENVÍO DE FORMULARIO
        document.getElementById('formPago').addEventListener('submit', function(e) {
            const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
            if (carrito.length === 0) {
                e.preventDefault();
                alert("Carrito vacío");
                return;
            }
            document.getElementById('carritoJson').value = JSON.stringify(carrito);
        });
    });

    // RENDERING DEL CARRITO
    function cargarCarrito() {
        const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
        const list = document.getElementById('cart-items-list');
        
        // ESTADO VACÍO BONITO
        if(carrito.length === 0) {
            list.innerHTML = `
                <div class="empty-cart-state">
                    <div class="empty-icon">🛒</div>
                    <h3 style="color:#555;">Tu carrito está vacío</h3>
                    <p style="color:#999;">¿No sabes qué comprar? ¡Tenemos miles de ofertas!</p>
                    <a href="tienda.php" class="btn-shop">Ir a la Tienda</a>
                </div>
            `;
            actualizarResumen([]); 
            // Ocultar resumen si está vacío
            document.querySelector('.cart-summary').style.opacity = '0.5';
            document.getElementById('btn-go-checkout').disabled = true;
            document.getElementById('btn-go-checkout').style.background = '#ccc';
            return;
        }

        // Habilitar botón si hay items
        document.querySelector('.cart-summary').style.opacity = '1';
        const btnCheckout = document.getElementById('btn-go-checkout');
        btnCheckout.disabled = false;
        btnCheckout.style.background = 'linear-gradient(135deg, #00B7C3 0%, #009099 100%)';

        // LISTA DE ITEMS MEJORADA
        list.innerHTML = carrito.map((item, i) => `
            <div class="cart-item">
                <img src="${item.imagen || 'img/default.jpg'}" alt="${item.nombre}">
                
                <div class="item-details">
                    <h3>${item.nombre}</h3>
                    <p>Código: REF-${item.id || 'GEN'}</p>
                </div>

                <div class="quantity-control">
                    <button onclick="changeQty(${i}, -1)">-</button>
                    <input value="${item.cantidad}" readonly>
                    <button onclick="changeQty(${i}, 1)">+</button>
                </div>

                <div style="text-align:right;">
                    <div class="item-price">$${(item.precio * item.cantidad).toFixed(2)}</div>
                    <button class="remove-btn" onclick="removeItem(${i})" title="Eliminar">🗑️</button>
                </div>
            </div>`).join('');
            
        actualizarResumen(carrito);
    }

    function actualizarResumen(c) {
        const sub = c.reduce((s, i) => s + (i.precio * i.cantidad), 0);
        const tax = sub * 0.10;
        const shipping = c.length > 0 ? 5.00 : 0;
        const total = sub + tax + shipping;

        document.getElementById('subtotal').textContent = '$' + sub.toFixed(2);
        document.getElementById('tax').textContent = '$' + tax.toFixed(2);
        document.getElementById('shipping').textContent = '$' + shipping.toFixed(2);
        document.getElementById('total').textContent = '$' + total.toFixed(2);
    }

    function changeQty(i, d) {
        let c = JSON.parse(localStorage.getItem('carrito'));
        c[i].cantidad += d;
        if(c[i].cantidad < 1) c[i].cantidad = 1;
        localStorage.setItem('carrito', JSON.stringify(c));
        cargarCarrito();
    }

    function removeItem(i) {
        if(confirm('¿Seguro que deseas eliminar este producto?')) {
            let c = JSON.parse(localStorage.getItem('carrito'));
            c.splice(i, 1);
            localStorage.setItem('carrito', JSON.stringify(c));
            cargarCarrito();
        }
    }
</script>

<?php require 'includes/footer.php'; ?>