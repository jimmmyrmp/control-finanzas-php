<?php
// ver_entradas.php
session_start();

require_once 'classes/Login.php';
require_once 'classes/Entradas.php';

Login::verificarSesion();

$entradas = new Entradas();
$lista    = $entradas->obtenerTodas();
$total    = $entradas->totalEntradas();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ver Entradas — Control de Finanzas</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <style>
    <?php include 'assets/css/base.css'; ?>

    /* Modal de imagen */
    .modal-overlay {
      display: none;
      position: fixed; inset: 0;
      background: rgba(0,0,0,0.85);
      z-index: 999;
      align-items: center;
      justify-content: center;
    }
    .modal-overlay.abierto { display: flex; }
    .modal-overlay img {
      max-width: 90vw;
      max-height: 85vh;
      border-radius: 12px;
      box-shadow: 0 20px 60px rgba(0,0,0,0.5);
    }
    .modal-cerrar {
      position: absolute;
      top: 20px; right: 28px;
      color: #fff; font-size: 2rem;
      cursor: pointer; background: none; border: none;
    }
  </style>
</head>
<body>
<?php include 'includes/sidebar.php'; ?>
<main class="main">
  <div class="topbar">
    <h1>Ver Entradas</h1>
    <div class="usuario">
      <div class="avatar"><?= strtoupper(substr($_SESSION['usuario_nombre'], 0, 1)) ?></div>
      <?= htmlspecialchars($_SESSION['usuario_nombre']) ?>
    </div>
  </div>

  <div class="tabla-card">
    <div class="tabla-header">
      <div>
        <h2>Listado de entradas</h2>
        <p><?= count($lista) ?> registro(s) encontrado(s)</p>
      </div>
      <div class="total-badge e">Total: $<?= number_format($total, 2) ?></div>
    </div>

    <?php if (empty($lista)): ?>
      <div class="vacio">No hay entradas registradas aún.</div>
    <?php else: ?>
      <div class="tabla-scroll">
        <table>
          <thead>
            <tr>
              <th>#</th>
              <th>Tipo de entrada</th>
              <th>Monto</th>
              <th>Fecha</th>
              <th>Factura</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($lista as $i => $e): ?>
            <tr>
              <td><?= $i + 1 ?></td>
              <td><?= htmlspecialchars($e['tipo_entrada']) ?></td>
              <td class="monto-e">$<?= number_format($e['monto'], 2) ?></td>
              <td><?= date('d/m/Y', strtotime($e['fecha'])) ?></td>
              <td>
                <?php if ($e['factura']): ?>
                  <?php $ext = strtolower(pathinfo($e['factura'], PATHINFO_EXTENSION)); ?>
                  <?php if ($ext === 'pdf'): ?>
                    <a href="<?= htmlspecialchars($e['factura']) ?>" target="_blank" 
                      style="font-size:0.85rem; color:var(--verde); font-weight:600;">
                      📄 Ver PDF
                    </a>
                  <?php else: ?>
                    <img src="<?= htmlspecialchars($e['factura']) ?>"
                        alt="Factura"
                        class="thumb"
                        onclick="abrirModal('<?= htmlspecialchars($e['factura']) ?>')">
                  <?php endif; ?>
                <?php else: ?>
                  <span class="sin-factura">Sin factura</span>
                <?php endif; ?>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
</main>

<!-- Modal de imagen -->
<div class="modal-overlay" id="modal" onclick="cerrarModal()">
  <button class="modal-cerrar" onclick="cerrarModal()">✕</button>
  <img id="modal-img" src="" alt="Factura ampliada">
</div>

<script>
function abrirModal(src) {
  document.getElementById('modal-img').src = src;
  document.getElementById('modal').classList.add('abierto');
}
function cerrarModal() {
  document.getElementById('modal').classList.remove('abierto');
}
</script>
</body>
</html>
