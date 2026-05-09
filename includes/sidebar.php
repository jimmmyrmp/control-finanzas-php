<?php
// includes/sidebar.php — Barra lateral compartida por todas las páginas internas
$paginaActual = basename($_SERVER['PHP_SELF']);
?>
<nav class="sidebar">
  <div class="sidebar-logo">
    <h2>Control de<br><span>Finanzas</span></h2>
    <p>Sistema de gestión</p>
  </div>
  <div class="sidebar-nav">
    <span class="nav-label">Menú principal</span>
    <a href="dashboard.php"         class="nav-link <?= $paginaActual === 'dashboard.php'         ? 'activo' : '' ?>"><span class="icon">🏠</span> Dashboard</a>
    <a href="registrar_entrada.php" class="nav-link <?= $paginaActual === 'registrar_entrada.php' ? 'activo' : '' ?>"><span class="icon">➕</span> Registrar entrada</a>
    <a href="registrar_salida.php"  class="nav-link <?= $paginaActual === 'registrar_salida.php'  ? 'activo' : '' ?>"><span class="icon">➖</span> Registrar salida</a>
    <a href="ver_entradas.php"      class="nav-link <?= $paginaActual === 'ver_entradas.php'      ? 'activo' : '' ?>"><span class="icon">📋</span> Ver entradas</a>
    <a href="ver_salidas.php"       class="nav-link <?= $paginaActual === 'ver_salidas.php'       ? 'activo' : '' ?>"><span class="icon">📋</span> Ver salidas</a>
    <a href="balance.php"           class="nav-link <?= $paginaActual === 'balance.php'           ? 'activo' : '' ?>"><span class="icon">📊</span> Mostrar balance</a>
  </div>
  <div class="sidebar-footer">
    <a href="logout.php" class="btn-salir"><span>🚪</span> Cerrar sesión</a>
  </div>
</nav>
