document.addEventListener('DOMContentLoaded', () => {

    // --- 1. INTERACTIVIDAD VISUAL (CAMBIAR DE PANTALLA) ---
    const loginSection = document.getElementById('loginSection');
    const registerSection = document.getElementById('registerSection');
    const btnIrARegistro = document.getElementById('btnIrARegistro');
    const btnIrALogin = document.getElementById('btnIrALogin');

    // Si existen los botones, activamos el "switch"
    if (btnIrARegistro && btnIrALogin) {
        // Al dar clic en "Crear una cuenta", ocultamos login y mostramos registro
        btnIrARegistro.addEventListener('click', () => {
            loginSection.style.display = 'none';
            registerSection.style.display = 'block';
        });

        // Al dar clic en "Inicia Sesión", hacemos lo contrario
        btnIrALogin.addEventListener('click', () => {
            registerSection.style.display = 'none';
            loginSection.style.display = 'block';
        });
        
        // Mejoramos el cursor para que parezcan botones
        btnIrARegistro.style.cursor = 'pointer';
        btnIrARegistro.style.color = '#00B7C3';
        btnIrARegistro.style.fontWeight = 'bold';
    }

    // --- 2. LÓGICA DE LOGIN (CONECTA CON php/login.php) ---
    const formLogin = document.getElementById('formLogin');
    if (formLogin) {
        formLogin.addEventListener('submit', async (e) => {
            e.preventDefault(); // Evita que la página se recargue
            
            const email = document.getElementById('logEmail').value;
            const pass = document.getElementById('logPass').value;
            const btn = formLogin.querySelector('button');
            const textoOriginal = btn.textContent;

            btn.textContent = "Verificando...";
            btn.disabled = true;

            try {
                // AQUÍ ES DONDE SE LLAMA A LA CARPETA PHP
                const res = await fetch('php/login.php', { 
                    method: 'POST',
                    body: JSON.stringify({ email: email, pass: pass }),
                    headers: { 'Content-Type': 'application/json' }
                });
                
                const data = await res.json();

                if (data.success) {
                    if(data.rol === 'admin') {
                        window.location.href = 'admin/dashboard.php';
                    } else {
                        window.location.href = 'index.php';
                    }
                } else {
                    alert("⚠️ " + data.message);
                    btn.textContent = textoOriginal;
                    btn.disabled = false;
                }
            } catch (error) {
                console.error(error);
                alert("❌ Error de conexión");
                btn.textContent = textoOriginal;
                btn.disabled = false;
            }
        });
    }

    // --- 3. LÓGICA DE REGISTRO (CONECTA CON php/registro.php) ---
    const formRegister = document.getElementById('formRegister');
    if (formRegister) {
        formRegister.addEventListener('submit', async (e) => {
            e.preventDefault(); // Evita que la página se recargue
            
            // Recogemos los datos del HTML
            const nombre = document.getElementById('regName').value;
            const email = document.getElementById('regEmail').value;
            const pass = document.getElementById('regPass').value;
            const inputTel = document.getElementById('regTelefono');
            const telefono = inputTel ? inputTel.value : ''; // Si no llenó teléfono, manda vacío
            
            const btn = formRegister.querySelector('button');
            const textoOriginal = btn.textContent;

            // Validación rápida
            if (pass.length < 6) {
                alert("La contraseña debe tener al menos 6 caracteres");
                return;
            }

            btn.textContent = "Creando cuenta...";
            btn.disabled = true;

            // ... dentro del evento submit de registro ...
            
            try {
                const res = await fetch('php/registro.php', { 
                    method: 'POST',
                    body: JSON.stringify({ nombre, email, pass, telefono }),
                    headers: { 'Content-Type': 'application/json' }
                });

                // --- CAMBIO PARA DETECTAR ERRORES ---
                const textoRespuesta = await res.text(); // Leemos como texto primero
                
                try {
                    var data = JSON.parse(textoRespuesta); // Intentamos convertir a JSON
                } catch (e) {
                    // Si falla, mostramos qué devolvió el servidor (aquí saldrá el error real)
                    alert("ERROR DEL SERVIDOR:\n" + textoRespuesta);
                    btn.textContent = textoOriginal;
                    btn.disabled = false;
                    return;
                }
                // ------------------------------------

                if (data.success) {
                    alert("✅ ¡Cuenta creada! Iniciando sesión...");
                    registerSection.style.display = 'none';
                    loginSection.style.display = 'block';
                    formRegister.reset();
                } else {
                    alert("⚠️ " + data.message);
                }
            } catch (error) {
                console.error(error);
                alert("❌ Error de conexión");
            } finally {
                btn.textContent = textoOriginal;
                btn.disabled = false;
            }
        });
    }
});