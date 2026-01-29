<?php require 'includes/header.php'; ?>

<style>
    /* --- CONFIGURACIÓN GENERAL --- */
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #fcfcfc; /* Blanco humo muy sutil */
    }

    /* Animación de entrada suave */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    main {
        display: block;
        width: 100%;
        animation: fadeInUp 0.8s ease-out;
    }

    /* --- 1. HERO BANNER MODERNIZADO --- */
    .hero-banner {
        background: linear-gradient(135deg, #002527 0%, #004a50 100%);
        color: white;
        padding: 100px 20px;
        text-align: center;
        margin-bottom: 0; /* Pegado a la siguiente sección visualmente */
        position: relative;
        overflow: hidden;
    }
    /* Decoración de fondo sutil */
    .hero-banner::before {
        content: '';
        position: absolute;
        top: -50%; left: -50%;
        width: 200%; height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 60%);
        pointer-events: none;
    }
    .hero-banner h1 { 
        font-size: 3.5rem; 
        margin: 0 0 20px 0; 
        font-weight: 800; 
        letter-spacing: -1px;
    }
    .hero-banner p { 
        font-size: 1.4rem; 
        opacity: 0.9; 
        max-width: 700px; 
        margin: 0 auto; 
        font-weight: 300;
    }

    /* --- 2. CONTENEDOR DE HISTORIA (Limpieza) --- */
    .story-section {
        background: white;
        padding: 80px 20px;
    }
    .story-container {
        max-width: 900px;
        margin: 0 auto;
    }
    .story-text {
        font-size: 1.15rem;
        line-height: 1.9;
        color: #444;
        text-align: justify;
    }
    /* Letra capital elegante para el primer párrafo */
    .story-text p:first-of-type::first-letter {
        font-size: 3.5rem;
        float: left;
        line-height: 0.8;
        padding-right: 15px;
        color: #00B7C3;
        font-weight: bold;
    }

    /* Títulos de Sección */
    .section-title {
        text-align: center;
        color: #002527;
        font-size: 2.5rem;
        margin-bottom: 50px;
        position: relative;
        font-weight: 700;
    }
    .section-title::after {
        content: '';
        display: block;
        width: 60px;
        height: 4px;
        background: #00B7C3;
        margin: 15px auto 0;
        border-radius: 2px;
    }

    /* --- 3. MISIÓN Y VISIÓN (Tarjetas Destacadas) --- */
    .mv-section {
        padding: 60px 20px;
        background: #f4f8f9;
    }
    .mv-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 40px;
        max-width: 1100px;
        margin: 0 auto;
    }
    .mv-card {
        background: white;
        padding: 50px 40px;
        border-radius: 20px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.05);
        text-align: center;
        border-top: 5px solid #00B7C3;
        transition: transform 0.3s ease;
    }
    .mv-card:hover { transform: translateY(-10px); }
    .mv-icon { font-size: 3rem; margin-bottom: 20px; display: block; }
    .mv-card h2 { color: #002527; font-size: 1.8rem; margin-bottom: 20px; }
    .mv-card p { color: #666; font-size: 1.1rem; line-height: 1.6; }

    /* --- 4. VALORES (Grid Moderno) --- */
    .values-section {
        padding: 100px 20px;
        background: white;
    }
    .values-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 40px;
        max-width: 1200px;
        margin: 0 auto;
    }
    .value-card {
        background: #fff;
        padding: 40px 30px;
        border-radius: 15px;
        text-align: center;
        border: 1px solid #f0f0f0;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .value-card:hover {
        box-shadow: 0 20px 40px rgba(0, 183, 195, 0.15);
        border-color: transparent;
        transform: translateY(-5px);
    }
    
    /* Círculo para el icono */
    .icon-circle {
        width: 80px; height: 80px;
        background: #e6fbfc; /* Turquesa muy claro */
        color: #00B7C3;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        margin: 0 auto 25px;
        transition: all 0.3s;
    }
    .value-card:hover .icon-circle {
        background: #00B7C3;
        color: white;
        transform: scale(1.1);
    }

    .value-card h3 { font-size: 1.4rem; color: #002527; margin-bottom: 15px; font-weight: 700; }
    .value-card p { color: #666; line-height: 1.6; font-size: 1rem; }

    /* --- 5. EQUIPO --- */
    .team-section {
        padding: 80px 20px 100px;
        background: #fcfcfc;
        border-top: 1px solid #eee;
    }
    .team-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 30px;
        max-width: 1200px;
        margin: 50px auto 0;
    }
    .team-card {
        background: white;
        padding: 40px 20px;
        border-radius: 15px;
        text-align: center;
        box-shadow: 0 5px 15px rgba(0,0,0,0.03);
        transition: 0.3s;
        border-bottom: 3px solid transparent;
    }
    .team-card:hover {
        box-shadow: 0 15px 30px rgba(0,0,0,0.08);
        border-bottom-color: #00B7C3;
        transform: translateY(-5px);
    }
    .team-role {
        color: #00B7C3;
        font-weight: bold;
        text-transform: uppercase;
        font-size: 0.85rem;
        margin-bottom: 10px;
        display: block;
    }
    .team-card h3 { margin: 5px 0 10px; color: #002527; font-size: 1.3rem; }
    .team-desc { font-size: 0.95rem; color: #777; line-height: 1.5; }

    /* Responsive */
    @media (max-width: 768px) {
        .hero-banner { padding: 60px 20px; }
        .hero-banner h1 { font-size: 2.5rem; }
        .section-title { font-size: 2rem; }
        .story-text p:first-of-type::first-letter { font-size: 3rem; }
    }
</style>

<main>
    <div class="hero-banner">
        <h1>Sobre ElectroStore</h1>
        <p>Conoce nuestra historia, misión y valores</p>
    </div>

    <section class="story-section">
        <div class="story-container">
            <h2 class="section-title">Nuestra Historia</h2>
            <div class="story-text">
                <p>ElectroStore nace en 2020 con la visión de revolucionar la compra de electrodomésticos en Ecuador. Iniciamos como una pequeña empresa con apenas 5 empleados y una tienda física. Hoy en día, somos una empresa con presencia en dos ciudades principales de Ecuador: Quito y Guayaquil.</p>
                <br>
                <p>Nuestro crecimiento se ha fundamentado en dos pilares: la calidad de nuestros productos y la satisfacción de nuestros clientes. Cada electrodoméstico que vendemos ha sido cuidadosamente seleccionado para garantizar el mejor desempeño y durabilidad.</p>
                <br>
                <p>Hoy contamos con más de 50 empleados dedicados a brindar la mejor experiencia de compra. Hemos servido a miles de clientes satisfechos en todo el país, quienes confían en nuestra marca y recomiendan nuestros servicios a sus amigos y familia.</p>
            </div>
        </div>
    </section>

    <section class="mv-section">
        <div class="mv-grid">
            <div class="mv-card">
                <span class="mv-icon">🎯</span>
                <h2>Nuestra Misión</h2>
                <p>Ofrecer electrodomésticos de alta calidad a precios competitivos, proporcionando una experiencia de compra excepcional que supere las expectativas de nuestros clientes.</p>
            </div>
            <div class="mv-card">
                <span class="mv-icon">👁️</span>
                <h2>Nuestra Visión</h2>
                <p>Ser la empresa líder en la venta de electrodomésticos en Ecuador, expandiéndonos a nuevas ciudades y ofreciendo una gama más amplia de productos.</p>
            </div>
        </div>
    </section>

    <section class="values-section">
        <h2 class="section-title">Nuestros Valores</h2>
        <div class="values-grid">
            <div class="value-card">
                <div class="icon-circle">💼</div>
                <h3>Integridad</h3>
                <p>Actuamos con honestidad y transparencia.</p>
            </div>
            <div class="value-card">
                <div class="icon-circle">⭐</div>
                <h3>Calidad</h3>
                <p>Comprometidos con la excelencia.</p>
            </div>
            <div class="value-card">
                <div class="icon-circle">👥</div>
                <h3>Servicio</h3>
                <p>Priorizamos tu satisfacción.</p>
            </div>
            <div class="value-card">
                <div class="icon-circle">🚀</div>
                <h3>Innovación</h3>
                <p>Buscamos nuevas soluciones siempre.</p>
            </div>
            <div class="value-card">
                <div class="icon-circle">🌱</div>
                <h3>Responsabilidad</h3>
                <p>Prácticas sostenibles y éticas.</p>
            </div>
            <div class="value-card">
                <div class="icon-circle">🤝</div>
                <h3>Confianza</h3>
                <p>Construimos relaciones duraderas.</p>
            </div>
        </div>
    </section>

    <section class="team-section">
        <h2 class="section-title">Nuestro Equipo</h2>
        <p style="text-align: center; color: #666; font-size: 1.1rem; max-width: 700px; margin: 0 auto;">Profesionales dedicados a brindarte la mejor experiencia.</p>
        
        <div class="team-grid">
            <div class="team-card">
                <div class="icon-circle" style="background:#f0f0f0; color:#333; font-size:2rem;">👔</div>
                <span class="team-role">Gerente General</span>
                <h3>Carlos Mendoza</h3>
                <p class="team-desc">Visión y estrategia empresarial.</p>
            </div>
            <div class="team-card">
                <div class="icon-circle" style="background:#f0f0f0; color:#333; font-size:2rem;">📊</div>
                <span class="team-role">Operaciones</span>
                <h3>María González</h3>
                <p class="team-desc">Eficiencia en tiendas y procesos.</p>
            </div>
            <div class="team-card">
                <div class="icon-circle" style="background:#f0f0f0; color:#333; font-size:2rem;">💻</div>
                <span class="team-role">Tecnología</span>
                <h3>Juan Rodríguez</h3>
                <p class="team-desc">Plataformas digitales óptimas.</p>
            </div>
            <div class="team-card">
                <div class="icon-circle" style="background:#f0f0f0; color:#333; font-size:2rem;">📢</div>
                <span class="team-role">Marketing</span>
                <h3>Ana López</h3>
                <p class="team-desc">Estrategias y ofertas.</p>
            </div>
        </div>
    </section>
</main>

<?php require 'includes/footer.php'; ?>