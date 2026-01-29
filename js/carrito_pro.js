document.addEventListener('DOMContentLoaded', function() {
    console.log("Carrito Pro Cargado Correctamente");

    // Delegación de eventos para botones (funciona con elementos dinámicos)
    document.body.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('carrito_button')) {
            e.preventDefault();
            // Deshabilitar botón temporalmente para evitar doble clic
            const btn = e.target;
            btn.disabled = true;
            btn.textContent = "Agregando...";
            
            agregarAlCarrito(btn);
            
            // Restaurar botón después de un momento
            setTimeout(() => {
                btn.disabled = false;
                btn.textContent = "Agregar al Carrito";
            }, 1000);
        }
    });
    
    // Botón Mi carrito del header
    const btnCarrito = document.getElementById('btnCarrito');
    if (btnCarrito) {
        btnCarrito.addEventListener('click', function() {
            window.location.href = 'carrito.php';
        });
    }
    
    actualizarContador();
});

function agregarAlCarrito(boton) {
    // 1. Buscar el contenedor padre
    let container = boton.closest('.product') || 
                    boton.closest('.featured-item-h') || 
                    boton.closest('.product-card');
    
    if (!container) {
        console.error('Error: No se encontró el contenedor del producto');
        return;
    }
    
    // 2. Extraer datos del DOM
    let id = null;
    let nombre = 'Producto';
    let precio = 0;
    let imagen = 'img/default.jpg';

    // A. Datos ocultos (Prioridad Alta)
    const hiddenId = container.querySelector('.p-id');
    const hiddenName = container.querySelector('.p-name');
    const hiddenPrice = container.querySelector('.p-price');

    if (hiddenId) id = hiddenId.textContent.trim();
    if (hiddenName) nombre = hiddenName.textContent.trim();
    
    // B. Obtener Precio (Limpieza robusta de símbolos)
    if (hiddenPrice) {
        precio = parseFloat(hiddenPrice.textContent.replace(/[^\d.]/g, ''));
    } else {
        // Fallback visual
        let priceElement = container.querySelector('.price');
        if (priceElement) {
            precio = parseFloat(priceElement.textContent.replace(/[^\d.]/g, ''));
        }
    }

    // C. Obtener Imagen
    if (container.querySelector('img')) {
        imagen = container.querySelector('img').src;
    } else {
        // Si la imagen está en un div con background (casos raros)
        const imgDiv = container.querySelector('.product-image');
        if (imgDiv) {
            const style = window.getComputedStyle(imgDiv);
            imagen = style.backgroundImage.slice(4, -1).replace(/"/g, "");
        }
    }
    
    console.log(`Procesando: ID=${id} | ${nombre} | $${precio}`);
    
    // 3. Guardar en LocalStorage
    let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
    
    // Buscar si ya existe (por ID es lo ideal)
    let existe = null;
    if (id) {
        existe = carrito.find(p => p.id_producto === id);
    } else {
        // Fallback por nombre si no hay ID (no recomendado para producción real pero útil aquí)
        existe = carrito.find(p => p.nombre === nombre);
    }

    if (existe) {
        existe.cantidad++;
    } else {
        // NUEVO PRODUCTO
        carrito.push({
            id_producto: id, // <--- IMPORTANTE: Usamos esta llave para PHP
            id: id,          // Mantenemos esta por compatibilidad visual
            nombre: nombre,
            precio: precio,
            imagen: imagen,
            cantidad: 1
        });
    }
    
    localStorage.setItem('carrito', JSON.stringify(carrito));
    localStorage.setItem('cart', JSON.stringify(carrito)); // Copia de seguridad
    
    // 4. Feedback
    mostrarModal();
    actualizarContador();
}

function mostrarModal() {
    // Eliminar modal previo
    const modalExistente = document.querySelector('[data-modal="carrito"]');
    if (modalExistente) modalExistente.remove();
    
    const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
    const subtotal = carrito.reduce((s, i) => s + (parseFloat(i.precio) * i.cantidad), 0);
    
    // Crear Overlay
    const modal = document.createElement('div');
    modal.setAttribute('data-modal', 'carrito');
    modal.style.cssText = `
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.5); backdrop-filter: blur(2px);
        display: flex; align-items: center; justify-content: center; 
        z-index: 10000; animation: fadeIn 0.3s ease;
    `;
    
    // Crear Caja
    const box = document.createElement('div');
    box.style.cssText = `
        background: white; border-radius: 12px; padding: 30px; 
        width: 90%; max-width: 400px; 
        box-shadow: 0 15px 50px rgba(0,0,0,0.3);
        text-align: center; transform: translateY(0); animation: slideUp 0.3s ease;
    `;
    
    // Inyectar animaciones CSS dinámicamente si no existen
    if (!document.getElementById('modal-animations')) {
        const style = document.createElement('style');
        style.id = 'modal-animations';
        style.innerHTML = `
            @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
            @keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        `;
        document.head.appendChild(style);
    }
    
    box.innerHTML = `
        <div style="width: 60px; height: 60px; background: #d4edda; color: #155724; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 30px; margin: 0 auto 15px;">✓</div>
        <h3 style="color: #002527; margin: 0 0 5px 0;">¡Agregado al Carrito!</h3>
        <p style="color: #666; margin-bottom: 20px; font-size: 0.95rem;">Tienes <strong>${carrito.length}</strong> productos en tu bolsa.</p>
        
        <div style="background: #f9f9f9; padding: 10px; border-radius: 8px; margin-bottom: 20px;">
            <p style="margin: 0; color: #333; font-weight: bold;">Subtotal actual: <span style="color: #00B7C3;">$${subtotal.toFixed(2)}</span></p>
        </div>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
            <button id="btnSeguir" style="padding: 12px; background: white; color: #666; border: 1px solid #ddd; border-radius: 6px; cursor: pointer; font-weight: 600;">Seguir</button>
            <button id="btnVerCarrito" style="padding: 12px; background: #002527; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">Ir a Pagar</button>
        </div>
    `;
    
    modal.appendChild(box);
    document.body.appendChild(modal);
    
    // Listeners
    document.getElementById('btnSeguir').onclick = () => modal.remove();
    document.getElementById('btnVerCarrito').onclick = () => { window.location.href = 'carrito.php'; };
    
    // Cerrar al hacer clic fuera
    modal.onclick = (e) => { if (e.target === modal) modal.remove(); };
    
    // Auto-cierre en 4 segundos
    setTimeout(() => { if(document.body.contains(modal)) modal.remove(); }, 4000);
}

function actualizarContador() {
    const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
    const total = carrito.reduce((s, i) => s + i.cantidad, 0);
    
    const btn = document.getElementById('btnCarrito');
    if (btn) {
        // Actualizamos el texto manteniendo el icono si usamos CSS ::before
        // O simplemente cambiamos el texto si es un botón normal
        btn.innerText = 'Mi carrito (' + total + ')';
    }
}