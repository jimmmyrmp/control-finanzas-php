<?php
session_start();

if (isset($_SESSION['usuario_id'])) {
    header('Location: dashboard.php');
    exit;
}

require_once 'classes/Login.php';
$error = '';

if ($_POST) {
    $login = new Login();
    
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if ($login->iniciarSesion($email, $password)) {
        header('Location: dashboard.php');
        exit;
    } else {
        $error = $login->getError();
    }
}
?>
<!DOCTYPE html>

<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Control de Finanzas — Iniciar Sesión</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --verde:    #1a7a4a;
      --verde-c:  #25a96a;
      --rojo:     #c0392b;
      --oscuro:   #0f1c14;
      --gris:     #f4f6f4;
      --blanco:   #ffffff;
      --texto:    #1e2d25;
      --suave:    #6b7c70;
    }

    body {
      min-height: 100vh;
      display: flex;
      font-family: 'DM Sans', sans-serif;
      background: var(--oscuro);
      color: var(--texto);
    }

    /* Panel izquierdo decorativo */
    .panel-izq {
      width: 42%;
      background: linear-gradient(145deg, #0f2d1c 0%, #1a5c35 60%, #0f1c14 100%);
      display: flex;
      flex-direction: column;
      justify-content: center;
      padding: 60px 50px;
      position: relative;
      overflow: hidden;
    }

    .panel-izq::before {
      content: '';
      position: absolute;
      width: 400px; height: 400px;
      border-radius: 50%;
      background: rgba(37,169,106,0.12);
      top: -80px; right: -80px;
    }

    .panel-izq::after {
      content: '';
      position: absolute;
      width: 250px; height: 250px;
      border-radius: 50%;
      border: 2px solid rgba(37,169,106,0.2);
      bottom: 60px; left: 30px;
    }

    .logo-marca {
      font-family: 'Syne', sans-serif;
      font-size: 2.4rem;
      font-weight: 800;
      color: var(--blanco);
      line-height: 1.1;
      position: relative;
    }

    .logo-marca span { color: var(--verde-c); }

    .tagline {
      margin-top: 18px;
      color: rgba(255,255,255,0.55);
      font-size: 0.95rem;
      line-height: 1.7;
      max-width: 280px;
      position: relative;
    }

    .stats-preview {
      margin-top: 50px;
      display: flex;
      flex-direction: column;
      gap: 14px;
      position: relative;
    }

    .stat-card {
      background: rgba(255,255,255,0.06);
      border: 1px solid rgba(255,255,255,0.1);
      border-radius: 12px;
      padding: 16px 20px;
      display: flex;
      align-items: center;
      gap: 14px;
    }

    .stat-icon {
      width: 38px; height: 38px;
      border-radius: 10px;
      display: flex; align-items: center; justify-content: center;
      font-size: 1.1rem;
    }

    .stat-icon.e { background: rgba(37,169,106,0.25); }
    .stat-icon.s { background: rgba(192,57,43,0.25);  }

    .stat-label { font-size: 0.78rem; color: rgba(255,255,255,0.45); }
    .stat-val   { font-size: 1.05rem; font-weight: 600; color: var(--blanco); margin-top: 2px; }

    /* Panel derecho — formulario */
    .panel-der {
      flex: 1;
      background: var(--blanco);
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 40px 30px;
    }

    .card-login {
      width: 100%;
      max-width: 420px;
    }

    .card-login h1 {
      font-family: 'Syne', sans-serif;
      font-size: 2rem;
      font-weight: 700;
      color: var(--texto);
      margin-bottom: 6px;
    }

    .subtitulo {
      color: var(--suave);
      font-size: 0.9rem;
      margin-bottom: 36px;
    }

    .alerta-error {
      background: #fdecea;
      border-left: 4px solid var(--rojo);
      color: var(--rojo);
      padding: 12px 16px;
      border-radius: 8px;
      font-size: 0.88rem;
      margin-bottom: 22px;
    }

    .grupo {
      margin-bottom: 20px;
    }

    label {
      display: block;
      font-size: 0.83rem;
      font-weight: 500;
      color: var(--suave);
      text-transform: uppercase;
      letter-spacing: .06em;
      margin-bottom: 8px;
    }

    input[type="email"],
    input[type="password"] {
      width: 100%;
      padding: 13px 16px;
      border: 1.5px solid #dde3e0;
      border-radius: 10px;
      font-size: 0.97rem;
      font-family: 'DM Sans', sans-serif;
      color: var(--texto);
      transition: border-color .2s, box-shadow .2s;
      outline: none;
    }

    input:focus {
      border-color: var(--verde-c);
      box-shadow: 0 0 0 3px rgba(37,169,106,0.15);
    }

    .btn-login {
      width: 100%;
      padding: 14px;
      background: var(--verde);
      color: var(--blanco);
      border: none;
      border-radius: 10px;
      font-family: 'Syne', sans-serif;
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      margin-top: 10px;
      transition: background .2s, transform .1s;
      letter-spacing: .03em;
    }

    .btn-login:hover  { background: var(--verde-c); }
    .btn-login:active { transform: scale(0.98); }

    .credenciales {
      margin-top: 24px;
      padding: 14px 16px;
      background: #f0faf5;
      border-radius: 10px;
      font-size: 0.83rem;
      color: var(--suave);
      line-height: 1.8;
      border: 1px dashed #b8dfc8;
    }

    .credenciales strong { color: var(--verde); }

    @media (max-width: 700px) {
      .panel-izq { display: none; }
    }
  </style>
</head>
<body>

  <!-- Panel decorativo izquierdo -->
  <div class="panel-izq">
    <div class="logo-marca">Control de<br><span>Finanzas</span></div>
    <p class="tagline">Administra tus entradas y salidas de forma sencilla y clara.</p>
    <div class="stats-preview">
      <div class="stat-card">
        <div class="stat-icon e">💰</div>
        <div>
          <div class="stat-label">Entradas registradas</div>
          <div class="stat-val">Ingresos del mes</div>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon s">📉</div>
        <div>
          <div class="stat-label">Salidas registradas</div>
          <div class="stat-val">Gastos del mes</div>
        </div>
      </div>
    </div>
  </div>

  <!-- Panel del formulario -->
  <div class="panel-der">
    <div class="card-login">
      <h1>Iniciar sesión</h1>
      <p class="subtitulo">Ingresa tus credenciales para continuar</p>

      <?php if ($error): ?>
        <div class="alerta-error">⚠️ <?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <form method="POST" action="index.php">
        <div class="grupo">
          <label for="email">Correo electrónico</label>
          <input type="email" id="email" name="email"
                 placeholder="usuario@correo.com"
                 value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                 required>
        </div>
        <div class="grupo">
          <label for="password">Contraseña</label>
          <input type="password" id="password" name="password"
                 placeholder="••••••••" required>
        </div>
        <button type="submit" class="btn-login">Entrar al sistema →</button>
      </form>

      <div class="credenciales">
        <strong>Credenciales de prueba:</strong><br>
        📧 admin@finanzas.com<br>
        🔑 admin123
      </div>
    </div>
  </div>

</body>
</html>
