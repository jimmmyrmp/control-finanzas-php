<?php
// dashboard.php — Panel de administración principal
session_start();

require_once 'classes/Login.php';
require_once 'classes/Entradas.php';
require_once 'classes/Salidas.php';

Login::verificarSesion();

$nombre   = $_SESSION['usuario_nombre'];
$entradas = new Entradas();
$salidas  = new Salidas();

$totalE   = $entradas->totalEntradas();
$totalS   = $salidas->totalSalidas();
$balance  = $totalE - $totalS;
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard — Control de Finanzas</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --verde:    #1a7a4a;
      --verde-c:  #25a96a;
      --verde-bg: #f0faf5;
      --rojo:     #c0392b;
      --rojo-bg:  #fdecea;
      --amarillo: #e67e22;
      --oscuro:   #0f1c14;
      --texto:    #1e2d25;
      --suave:    #6b7c70;
      --borde:    #dde3e0;
      --blanco:   #ffffff;
      --gris:     #f7f9f7;
    }

    body {
      font-family: 'DM Sans', sans-serif;
      background: var(--gris);
      color: var(--texto);
      min-height: 100vh;
      display: flex;
    }

    /* ── Sidebar ── */
    .sidebar {
      width: 240px;
      background: var(--oscuro);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      position: fixed;
      top: 0; left: 0;
      z-index: 100;
    }

    .sidebar-logo {
      padding: 28px 24px 22px;
      border-bottom: 1px solid rgba(255,255,255,0.08);
    }

    .sidebar-logo h2 {
      font-family: 'Syne', sans-serif;
      font-size: 1.2rem;
      font-weight: 800;
      color: var(--blanco);
      line-height: 1.2;
    }

    .sidebar-logo h2 span { color: var(--verde-c); }

    .sidebar-logo p {
      font-size: 0.75rem;
      color: rgba(255,255,255,0.35);
      margin-top: 4px;
    }

    .sidebar-nav {
      flex: 1;
      padding: 16px 12px;
      display: flex;
      flex-direction: column;
      gap: 4px;
    }

    .nav-label {
      font-size: 0.68rem;
      text-transform: uppercase;
      letter-spacing: .1em;
      color: rgba(255,255,255,0.3);
      padding: 10px 12px 6px;
    }

    .nav-link {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 11px 14px;
      border-radius: 10px;
      color: rgba(255,255,255,0.65);
      text-decoration: none;
      font-size: 0.9rem;
      transition: background .2s, color .2s;
    }

    .nav-link:hover,
    .nav-link.activo {
      background: rgba(37,169,106,0.18);
      color: var(--blanco);
    }

    .nav-link .icon { font-size: 1rem; width: 20px; text-align: center; }

    .sidebar-footer {
      padding: 16px 12px;
      border-top: 1px solid rgba(255,255,255,0.08);
    }

    .btn-salir {
      display: flex;
      align-items: center;
      gap: 10px;
      width: 100%;
      padding: 11px 14px;
      border-radius: 10px;
      background: rgba(192,57,43,0.15);
      color: #f08070;
      border: none;
      cursor: pointer;
      font-size: 0.9rem;
      font-family: 'DM Sans', sans-serif;
      text-decoration: none;
      transition: background .2s;
    }

    .btn-salir:hover { background: rgba(192,57,43,0.28); }

    /* ── Main content ── */
    .main {
      margin-left: 240px;
      flex: 1;
      padding: 36px 40px;
    }

    .topbar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 32px;
    }

    .topbar h1 {
      font-family: 'Syne', sans-serif;
      font-size: 1.7rem;
      font-weight: 700;
    }

    .topbar .usuario {
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 0.88rem;
      color: var(--suave);
    }

    .avatar {
      width: 36px; height: 36px;
      border-radius: 50%;
      background: var(--verde);
      color: var(--blanco);
      font-family: 'Syne', sans-serif;
      font-weight: 700;
      display: flex; align-items: center; justify-content: center;
      font-size: 0.9rem;
    }

    /* Bienvenida */
    .bienvenida {
      background: linear-gradient(135deg, var(--verde) 0%, #0f5c32 100%);
      border-radius: 16px;
      padding: 28px 32px;
      color: var(--blanco);
      margin-bottom: 28px;
      position: relative;
      overflow: hidden;
    }

    .bienvenida::after {
      content: '💰';
      position: absolute;
      right: 32px; top: 50%;
      transform: translateY(-50%);
      font-size: 3.5rem;
      opacity: 0.2;
    }

    .bienvenida h2 {
      font-family: 'Syne', sans-serif;
      font-size: 1.4rem;
      font-weight: 700;
    }

    .bienvenida p {
      margin-top: 6px;
      opacity: 0.8;
      font-size: 0.9rem;
    }

    /* Tarjetas de resumen */
    .tarjetas {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 18px;
      margin-bottom: 32px;
    }

    .tarjeta {
      background: var(--blanco);
      border-radius: 14px;
      padding: 22px 24px;
      border: 1.5px solid var(--borde);
    }

    .tarjeta .etiqueta {
      font-size: 0.78rem;
      text-transform: uppercase;
      letter-spacing: .08em;
      color: var(--suave);
      margin-bottom: 10px;
    }

    .tarjeta .valor {
      font-family: 'Syne', sans-serif;
      font-size: 1.9rem;
      font-weight: 700;
    }

    .tarjeta.e .valor { color: var(--verde); }
    .tarjeta.s .valor { color: var(--rojo); }
    .tarjeta.b .valor { color: <?= $balance >= 0 ? 'var(--verde)' : 'var(--rojo)' ?>; }

    .tarjeta .icono {
      font-size: 1.6rem;
      margin-bottom: 10px;
    }

    /* Acceso rápido — menú con las 5 opciones */
    .seccion-titulo {
      font-family: 'Syne', sans-serif;
      font-size: 1.05rem;
      font-weight: 700;
      margin-bottom: 16px;
    }

    .menu-rapido {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
      gap: 14px;
    }

    .menu-btn {
      background: var(--blanco);
      border: 1.5px solid var(--borde);
      border-radius: 14px;
      padding: 22px 20px;
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      gap: 10px;
      cursor: pointer;
      text-decoration: none;
      color: var(--texto);
      transition: border-color .2s, box-shadow .2s, transform .15s;
    }

    .menu-btn:hover {
      border-color: var(--verde-c);
      box-shadow: 0 4px 18px rgba(37,169,106,0.12);
      transform: translateY(-2px);
    }

    .menu-btn .mb-icon { font-size: 1.6rem; }
    .menu-btn .mb-num  {
      font-family: 'Syne', sans-serif;
      font-size: 0.68rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: .1em;
      color: var(--suave);
    }
    .menu-btn .mb-label {
      font-family: 'Syne', sans-serif;
      font-size: 0.95rem;
      font-weight: 600;
      line-height: 1.3;
    }

    @media (max-width: 900px) {
      .sidebar { width: 200px; }
      .main    { margin-left: 200px; padding: 24px 20px; }
      .tarjetas { grid-template-columns: 1fr 1fr; }
    }
  </style>
</head>
<body>

  <!-- ── Sidebar ── -->
  <nav class="sidebar">
    <div class="sidebar-logo">
      <h2>Control de<br><span>Finanzas</span></h2>
      <p>Sistema de gestión</p>
    </div>
    <div class="sidebar-nav">
      <span class="nav-label">Menú principal</span>
      <a href="dashboard.php" class="nav-link activo">
        <span class="icon">🏠</span> Dashboard
      </a>
      <a href="registrar_entrada.php" class="nav-link">
        <span class="icon">➕</span> Registrar entrada
      </a>
      <a href="registrar_salida.php" class="nav-link">
        <span class="icon">➖</span> Registrar salida
      </a>
      <a href="ver_entradas.php" class="nav-link">
        <span class="icon">📋</span> Ver entradas
      </a>
      <a href="ver_salidas.php" class="nav-link">
        <span class="icon">📋</span> Ver salidas
      </a>
      <a href="balance.php" class="nav-link">
        <span class="icon">📊</span> Mostrar balance
      </a>
    </div>
    <div class="sidebar-footer">
      <a href="logout.php" class="btn-salir">
        <span>🚪</span> Cerrar sesión
      </a>
    </div>
  </nav>

  <!-- ── Contenido principal ── -->
  <main class="main">
    <div class="topbar">
      <h1>Dashboard</h1>
      <div class="usuario">
        <div class="avatar"><?= strtoupper(substr($nombre, 0, 1)) ?></div>
        <?= htmlspecialchars($nombre) ?>
      </div>
    </div>

    <!-- Bienvenida -->
    <div class="bienvenida">
      <h2>¡Bienvenido, <?= htmlspecialchars($nombre) ?>! 👋</h2>
      <p>Desde aquí puedes administrar todas tus entradas y salidas. Usa el menú lateral para navegar.</p>
    </div>

    <!-- Tarjetas de resumen -->
    <div class="tarjetas">
      <div class="tarjeta e">
        <div class="icono">💰</div>
        <div class="etiqueta">Total de Entradas</div>
        <div class="valor">$<?= number_format($totalE, 2) ?></div>
      </div>
      <div class="tarjeta s">
        <div class="icono">💸</div>
        <div class="etiqueta">Total de Salidas</div>
        <div class="valor">$<?= number_format($totalS, 2) ?></div>
      </div>
      <div class="tarjeta b">
        <div class="icono"><?= $balance >= 0 ? '📈' : '📉' ?></div>
        <div class="etiqueta">Balance Mensual</div>
        <div class="valor">$<?= number_format($balance, 2) ?></div>
      </div>
    </div>

    <!-- Menú rápido (5 opciones) -->
    <p class="seccion-titulo">Opciones del sistema</p>
    <div class="menu-rapido">
      <a href="registrar_entrada.php" class="menu-btn">
        <div class="mb-icon">💵</div>
        <div class="mb-num">Opción 1</div>
        <div class="mb-label">Registrar entrada</div>
      </a>
      <a href="registrar_salida.php" class="menu-btn">
        <div class="mb-icon">💳</div>
        <div class="mb-num">Opción 2</div>
        <div class="mb-label">Registrar salida</div>
      </a>
      <a href="ver_entradas.php" class="menu-btn">
        <div class="mb-icon">📥</div>
        <div class="mb-num">Opción 3</div>
        <div class="mb-label">Ver entradas</div>
      </a>
      <a href="ver_salidas.php" class="menu-btn">
        <div class="mb-icon">📤</div>
        <div class="mb-num">Opción 4</div>
        <div class="mb-label">Ver salidas</div>
      </a>
      <a href="balance.php" class="menu-btn">
        <div class="mb-icon">📊</div>
        <div class="mb-num">Opción 5</div>
        <div class="mb-label">Mostrar balance</div>
      </a>
    </div>
  </main>

</body>
</html>
