
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso - ElectroStore</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/login.css">
</head>
<body class="body-login">

    <a href="index.php" class="back-home-btn">← Volver a la Tienda</a>

    <div class="login-container-wrapper">
        
        <div id="loginSection" class="auth-box">
            <h2>Bienvenido de nuevo</h2>
            <form id="formLogin">
                <div class="input-group">
                    <label>Correo Electrónico</label>
                    <input type="email" id="logEmail" required placeholder="ejemplo@correo.com">
                </div>
                <div class="input-group">
                    <label>Contraseña</label>
                    <input type="password" id="logPass" required placeholder="••••••••">
                </div>
                <button type="submit" class="btn-action">Iniciar Sesión</button>
            </form>
            <p class="switch-text">
                ¿Nuevo aquí? <span id="btnIrARegistro">Crear una cuenta</span>
            </p>
        </div>

        <div id="registerSection" class="auth-box" style="display: none;">
            <h2>Crear Cuenta</h2>
            <form id="formRegister">
                <div class="input-group">
                    <label>Nombre Completo</label>
                    <input type="text" id="regName" required placeholder="Tu nombre">
                </div>
                <div class="input-group">
                    <label>Correo Electrónico</label>
                    <input type="email" id="regEmail" required placeholder="tucorreo@ejemplo.com">
                </div>
                <div class="input-group">
                    <label>Teléfono</label>
                    <input type="tel" id="regTelefono" placeholder="099...">
                </div>
                <div class="input-group">
                    <label>Contraseña</label>
                    <input type="password" id="regPass" required placeholder="Crea una contraseña">
                </div>
                <button type="submit" class="btn-action">Registrarse</button>
            </form>
            <p class="switch-text">
                ¿Ya tienes cuenta? <span id="btnIrALogin" style="cursor:pointer; color:#00B7C3; font-weight:bold;">Inicia Sesión</span>
            </p>
        </div>

    <script src="js/auth.js"></script>
</body>
</html>