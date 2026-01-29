<a href="https://wa.me/+593984562834" target="_blank">
        <img src="Img/whatsapp.png" alt="WhatsApp" class="whatsapp-logo" 
             style="position: fixed; bottom: 20px; right: 20px; width: 60px; height: 60px; cursor: pointer; z-index: 999; box-shadow: 0 4px 12px rgba(0,0,0,0.2); border-radius: 50%; transition: transform 0.3s ease;">
    </a>

    <footer class="footer">
        <div class="footer-columns">
            
            <div>
                <h4>UBICACIONES</h4>
                <p>
                    <a href="https://www.google.com/maps/search/?api=1&query=Quito+Ecuador" target="_blank" class="footer-link">
                        📍 Quito, Pichincha
                    </a>
                </p>
                <p>
                    <a href="https://www.google.com/maps/search/?api=1&query=Guayaquil+Ecuador" target="_blank" class="footer-link">
                        📍 Guayaquil, Guayas
                    </a>
                </p>
            </div>

            <div>
                <h4>CATEGORÍAS</h4>
                <p><a href="tienda.php" class="footer-link">Electrodomésticos</a></p>
                <p><a href="galeria.php" class="footer-link">Ofertas Especiales</a></p>
                <p><a href="entrega.php" class="footer-link">Puntos de Entrega</a></p>
                <p><a href="<?php echo isset($_SESSION['user_id']) ? 'perfil.php' : 'login.php'; ?>" class="footer-link">Rastrear Pedido</a></p>
            </div>

            <div>
                <h4>INFORMACIÓN</h4>
                <p><a href="about.php" class="footer-link">Sobre ElectroStore</a></p>
            </div>

            <div>
                <h4>SÍGUENOS</h4>
                <p>
                    <a href="https://facebook.com" target="_blank" class="footer-link">
                        <span style="font-size:1.1rem">🔵</span> Facebook
                    </a>
                </p>
                <p>
                    <a href="https://instagram.com" target="_blank" class="footer-link">
                        <span style="font-size:1.1rem">📸</span> Instagram
                    </a>
                </p>
                <p>
                    <a href="https://wa.me/+593984562834" target="_blank" class="footer-link">
                        <span style="font-size:1.1rem">💬</span> WhatsApp 
                    </a>
                </p>
            </div>
        </div>
        
        <p style="margin-top: 30px; border-top: 1px solid #444; padding-top: 15px; font-size: 0.9rem; color: #aaa;">
            © Todos los derechos reservados 2026 - ElectroStore
        </p>
    </footer>

    <style>
        /* Estilo base de los enlaces */
        .footer-link {
            color: #e0e0e0; /* Blanco suave */
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-block; /* Permite animación de movimiento */
        }

        /* Efecto al pasar el mouse (Hover) */
        .footer-link:hover {
            color: #00B7C3; /* Color Turquesa de tu marca */
            padding-left: 5px; /* Pequeño movimiento a la derecha */
            text-shadow: 0 0 5px rgba(0, 183, 195, 0.5);
        }

        /* Ajuste de espaciado en párrafos del footer para que no se vean pegados */
        .footer-columns p {
            margin-bottom: 12px;
        }
    </style>
</body>
</html>