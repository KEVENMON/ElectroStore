<?php require 'includes/header.php'; ?>

<style>
    /* Forzar limpieza de layout */
    main { display: block; width: 100%; clear: both; }

    /* Header Simple */
    .delivery-hero {
        text-align: center;
        padding: 60px 20px;
        background: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
        margin-bottom: 50px;
        width: 100%;
        box-sizing: border-box;
    }
    .delivery-hero h1 { color: #002527; font-size: 3rem; margin-bottom: 10px; }
    .delivery-hero p { color: #666; font-size: 1.2rem; }

    .main-container { max-width: 1000px; margin: 0 auto; padding: 0 20px; }

    /* Tarjetas de Ubicación GRANDES */
    .location-card-large {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 15px 40px rgba(0,0,0,0.08);
        margin-bottom: 50px;
        border: 1px solid #eee;
    }
    
    .loc-title { background: #002527; color: white; padding: 25px; text-align: center; }
    .loc-title h2 { margin: 0; font-size: 1.8rem; }

    .loc-details {
        padding: 40px;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
    }

    .detail-item { display: flex; align-items: flex-start; gap: 15px; }
    .d-icon { 
        font-size: 1.5rem; color: #00B7C3; background: #e0f7fa; 
        width: 50px; height: 50px; 
        display: flex; align-items: center; justify-content: center; 
        border-radius: 50%; flex-shrink: 0;
    }
    .d-info h3 { margin: 0 0 5px 0; font-size: 1rem; color: #888; text-transform: uppercase; }
    .d-info p { margin: 0; font-size: 1.1rem; color: #333; font-weight: 500; }

    /* Contacto */
    .contact-block {
        background: linear-gradient(135deg, #002527 0%, #004a50 100%);
        color: white;
        padding: 60px;
        border-radius: 20px;
        text-align: center;
        margin-bottom: 80px;
    }
    .contact-grid-simple {
        display: flex; flex-wrap: wrap; justify-content: center; gap: 40px; margin-top: 40px;
    }
    .c-box h3 { color: #00B7C3; margin-bottom: 10px; }
    .c-box p { color: white; }
</style>

<main>
    <div class="delivery-hero">
        <h1>Puntos de Entrega</h1>
        <p>Visita nuestras sucursales y retira tus compras</p>
    </div>

    <div class="main-container">
        
        <div class="location-card-large">
            <div class="loc-title"><h2>📍 Quito - Pichincha</h2></div>
            <div class="loc-details">
                <div class="detail-item">
                    <div class="d-icon">🏢</div>
                    <div class="d-info"><h3>Dirección</h3><p>Calle Principal 123, CC Plaza Mayor</p></div>
                </div>
                <div class="detail-item">
                    <div class="d-icon">📞</div>
                    <div class="d-info"><h3>Teléfono</h3><p>+593 2 999-8888</p></div>
                </div>
                <div class="detail-item">
                    <div class="d-icon">🕐</div>
                    <div class="d-info"><h3>Horario</h3><p>Lun-Sáb: 9AM - 6PM</p></div>
                </div>
                <div class="detail-item">
                    <div class="d-icon">✉️</div>
                    <div class="d-info"><h3>Email</h3><p>quito@electrostore.com</p></div>
                </div>
            </div>
        </div>

        <div class="location-card-large">
            <div class="loc-title"><h2>📍 Guayaquil - Guayas</h2></div>
            <div class="loc-details">
                <div class="detail-item">
                    <div class="d-icon">🏢</div>
                    <div class="d-info"><h3>Dirección</h3><p>Av. Comercial 456, Centro Distribución</p></div>
                </div>
                <div class="detail-item">
                    <div class="d-icon">📞</div>
                    <div class="d-info"><h3>Teléfono</h3><p>+593 4 888-7777</p></div>
                </div>
                <div class="detail-item">
                    <div class="d-icon">🕐</div>
                    <div class="d-info"><h3>Horario</h3><p>Lun-Sáb: 8AM - 7PM</p></div>
                </div>
                <div class="detail-item">
                    <div class="d-icon">✉️</div>
                    <div class="d-info"><h3>Email</h3><p>guayaquil@electrostore.com</p></div>
                </div>
            </div>
        </div>

        <div class="contact-block">
            <h2>💬 ¿Necesitas Ayuda?</h2>
            <div class="contact-grid-simple">
                <div class="c-box"><h3>WhatsApp</h3><p>+593 98 45 62-834</p></div>
                <div class="c-box"><h3>Email</h3><p>info@electrostore.com</p></div>
            </div>
        </div>

    </div>
</main>

<?php require 'includes/footer.php'; ?>