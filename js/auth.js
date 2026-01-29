// js/auth.js

document.addEventListener('DOMContentLoaded', () => {
    
    // Referencias
    const loginSection = document.getElementById('loginSection');
    const registerSection = document.getElementById('registerSection');
    const btnIrARegistro = document.getElementById('btnIrARegistro');
    const btnIrALogin = document.getElementById('btnIrALogin');

    // 1. SWITCH ENTRE FORMULARIOS
    btnIrARegistro.addEventListener('click', () => {
        loginSection.style.display = 'none';
        registerSection.style.display = 'block';
    });

    btnIrALogin.addEventListener('click', () => {
        registerSection.style.display = 'none';
        loginSection.style.display = 'block';
    });

    // 2. LÓGICA DE LOGIN
    document.getElementById('formLogin').addEventListener('submit', async (e) => {
        e.preventDefault();
        const email = document.getElementById('logEmail').value;
        const pass = document.getElementById('logPass').value;

        // Petición al backend
        const res = await fetch('php/login.php', {
            method: 'POST',
            body: JSON.stringify({ email: email, pass: pass }),
            headers: { 'Content-Type': 'application/json' }
        });
        
        const data = await res.json();

        if (data.success) {
            // Redirección según rol
            if(data.rol === 'admin') {
                window.location.href = 'admin.php';
            } else {
                window.location.href = 'index.php';
            }
        } else {
            alert("Error: " + data.message);
        }
    });

    // 3. LÓGICA DE REGISTRO
    document.getElementById('formRegister').addEventListener('submit', async (e) => {
        e.preventDefault();
        const nombre = document.getElementById('regName').value;
        const email = document.getElementById('regEmail').value;
        const pass = document.getElementById('regPass').value;

        // Validar que el nombre no contenga números
        if (/\d/.test(nombre)) {
            alert("Error: El nombre completo no puede contener números");
            return;
        }

        // Validar que el nombre no esté vacío
        if (nombre.trim().length < 3) {
            alert("Error: El nombre debe tener al menos 3 caracteres");
            return;
        }

        // Validar contraseña
        if (pass.length < 6) {
            alert("Error: La contraseña debe tener al menos 6 caracteres");
            return;
        }

        const res = await fetch('php/registro.php', {
            method: 'POST',
            body: JSON.stringify({ nombre, email, pass }),
            headers: { 'Content-Type': 'application/json' }
        });

        const data = await res.json();

        if (data.success) {
            alert("¡Cuenta creada! Ahora inicia sesión.");
            document.getElementById('formRegister').reset();
            btnIrALogin.click(); // Cambia a la vista de login automáticamente
        } else {
            alert("Error: " + data.message);
        }
    });
});