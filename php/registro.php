<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - ElectroStore</title>
    <style>
        body {
            background: linear-gradient(135deg, #002527 0%, #004a50 100%);
            height: 100vh; display: flex; align-items: center; justify-content: center;
            margin: 0; font-family: 'Segoe UI', sans-serif;
        }
        .auth-card {
            background: white; padding: 40px; border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.3); width: 100%; max-width: 450px; text-align: center;
        }
        .auth-logo { font-size: 2rem; font-weight: 800; color: #002527; display: block; margin-bottom: 10px; text-decoration:none;}
        .input-group { margin-bottom: 15px; text-align: left; }
        .input-group label { display: block; margin-bottom: 5px; font-weight: 600; color: #333; }
        .input-group input { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; }
        .btn-auth { width: 100%; padding: 12px; background: #002527; color: white; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; margin-top: 10px; }
        .btn-auth:hover { background: #004a50; }
        .auth-footer { margin-top: 20px; font-size: 0.9rem; color: #666; }
        .auth-footer a { color: #00B7C3; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>
    <div class="auth-card">
        <a href="index.php" class="auth-logo">ElectroStore</a>
        <p style="color:#666; margin-bottom:20px;">Crea tu cuenta para comprar</p>

        <form action="php/auth.php" method="POST">
            <input type="hidden" name="accion" value="registro">
            
            <div class="input-group">
                <label>Nombre Completo</label>
                <input type="text" name="nombre" required placeholder="Juan Pérez">
            </div>

            <div class="input-group">
                <label>Correo Electrónico</label>
                <input type="email" name="email" required placeholder="juan@ejemplo.com">
            </div>
            
            <div class="input-group">
                <label>Teléfono (Opcional)</label>
                <input type="tel" name="telefono" placeholder="099...">
            </div>

            <div class="input-group">
                <label>Contraseña</label>
                <input type="password" name="password" required placeholder="Mínimo 6 caracteres">
            </div>

            <button type="submit" class="btn-auth">Crear Cuenta</button>
        </form>

        <div class="auth-footer">
            ¿Ya tienes cuenta? <a href="login.php">Inicia Sesión</a>
        </div>
    </div>
</body>
</html>