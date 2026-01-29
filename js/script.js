document.addEventListener('DOMContentLoaded', () => {
    
    // Referencias al DOM
    const modal = document.getElementById('loginModal');
    const btnOpen = document.getElementById('btnOpenLogin');
    const btnClose = document.querySelector('.close-btn');
    const sectionLogin = document.getElementById('sectionLogin');
    const sectionRegister = document.getElementById('sectionRegister');

    // 1. ABRIR Y CERRAR MODAL
    if(btnOpen) btnOpen.addEventListener('click', () => {
        modal.style.display = 'flex'; // Usamos flex para centrar
    });

    if(btnClose) btnClose.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    // Cerrar si clicamos fuera de la caja blanca
    window.addEventListener('click', (e) => {
        if (e.target == modal) modal.style.display = 'none';
    });

    // 2. CAMBIAR ENTRE LOGIN Y REGISTRO
    document.getElementById('linkToRegister').addEventListener('click', (e) => {
        e.preventDefault();
        sectionLogin.style.display = 'none';
        sectionRegister.style.display = 'block';
    });

    document.getElementById('linkToLogin').addEventListener('click', (e) => {
        e.preventDefault();
        sectionRegister.style.display = 'none';
        sectionLogin.style.display = 'block';
    });

    // 3. LOGICA DE LOGIN (AJAX)
    document.getElementById('formLogin').addEventListener('submit', async (e) => {
        e.preventDefault();
        const email = document.getElementById('logEmail').value;
        const pass = document.getElementById('logPass').value;

        const res = await fetch('php/login.php', {
            method: 'POST',
            body: JSON.stringify({ email, pass }),
            headers: {'Content-Type': 'application/json'}
        });
        const data = await res.json();

        if (data.success) {
            alert("Bienvenido");
            if(data.rol === 'admin') {
                window.location.href = 'admin/dashboard.php'; // O la página que crees para admin
            } else {
                location.reload(); // Recarga para ver sesión iniciada
            }
        } else {
            alert(data.message);
        }
    });

    // 4. LOGICA DE REGISTRO (AJAX)
    document.getElementById('formRegister').addEventListener('submit', async (e) => {
        e.preventDefault();
        const nombre = document.getElementById('regName').value;
        const email = document.getElementById('regEmail').value;
        const pass = document.getElementById('regPass').value;

        const res = await fetch('php/registro.php', {
            method: 'POST',
            body: JSON.stringify({ nombre, email, pass }),
            headers: {'Content-Type': 'application/json'}
        });
        const data = await res.json();

        if (data.success) {
            alert("Cuenta creada. Inicia sesión.");
            document.getElementById('linkToLogin').click(); // Vuelve al login
        } else {
            alert("Error: " + data.message);
        }
    });
});