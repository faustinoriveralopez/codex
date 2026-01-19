<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SICOR-OPEO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            width: 100%;
            max-width: 400px;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            background: white;
        }
        .brand-logo {
            text-align: center;
            margin-bottom: 2rem;
        }
        .brand-logo h3 {
            color: #7d0e32; /* Example corporate color */
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="brand-logo">
        <h3>SICOR-OPEO</h3>
        <p class="text-muted">Control de Oficios</p>
    </div>

    <?php if(isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <form action="<?php echo BASE_URL; ?>login" method="POST">
        <div class="mb-3">
            <label for="email" class="form-label">Correo Institucional</label>
            <input type="email" class="form-control" id="email" name="email" required placeholder="nombre.apellido@oaxaca.gob.mx">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="d-grid">
            <button type="submit" class="btn btn-primary" style="background-color: #7d0e32; border-color: #7d0e32;">Entrar</button>
        </div>
    </form>
    <div class="mt-3 text-center">
        <small class="text-muted">Oficina de Pensiones del Estado de Oaxaca</small>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
