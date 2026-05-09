<?php
// balance.php — Reporte de balance con gráfico de pastel y exportar a PDF
session_start();

require_once 'classes/Login.php';
require_once 'classes/ReporteBalance.php';

Login::verificarSesion();

$reporte     = new ReporteBalance();
$entradas    = $reporte->getEntradas();
$salidas     = $reporte->getSalidas();
$totalE      = $reporte->getTotalEntradas();
$totalS      = $reporte->getTotalSalidas();
$balance     = $reporte->getBalance();
$porcentajes = $reporte->getPorcentajes();
$fechaHoy    = date('d/m/Y');
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Balance Mensual — Control de Finanzas</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <!-- Chart.js para el gráfico de pastel -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <!-- html2pdf para exportar a PDF -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
  <style>
    <?php include 'assets/css/base.css'; ?>

    .reporte-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 28px;
    }

    .fecha-reporte {
      font-size: 0.85rem;
      color: var(--suave);
    }

    .btn-pdf {
      display: flex;
      align-items: center;
      gap: 8px;
      padding: 11px 20px;
      background: var(--verde);
      color: #fff;
      border: none;
      border-radius: 10px;
      font-family: 'Syne', sans-serif;
      font-size: 0.9rem;
      font-weight: 600;
      cursor: pointer;
      transition: background .2s;
    }

    .btn-pdf:hover { background: var(--verde-c); }

    /* Zona imprimible */
    #zona-pdf {
      background: #fff;
      border-radius: 16px;
      padding: 32px;
      border: 1.5px solid var(--borde);
    }

    .pdf-titulo {
      font-family: 'Syne', sans-serif;
      font-size: 1.5rem;
      font-weight: 800;
      text-align: center;
      margin-bottom: 4px;
    }

    .pdf-subtitulo {
      text-align: center;
      color: var(--suave);
      font-size: 0.85rem;
      margin-bottom: 28px;
    }

    .tablas-reporte {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 24px;
      margin-bottom: 24px;
    }

    .tabla-reporte h3 {
      font-family: 'Syne', sans-serif;
      font-size: 1rem;
      font-weight: 700;
      margin-bottom: 10px;
      padding: 10px 14px;
      border-radius: 8px;
      color: #fff;
    }

    .tabla-reporte.e h3 { background: var(--verde); }
    .tabla-reporte.s h3 { background: var(--rojo);  }

    .tabla-reporte table {
      width: 100%;
      border-collapse: collapse;
      font-size: 0.85rem;
    }

    .tabla-reporte th {
      background: var(--gris);
      padding: 8px 10px;
      text-align: left;
      font-weight: 600;
      font-size: 0.78rem;
      text-transform: uppercase;
      color: var(--suave);
    }

    .tabla-reporte td {
      padding: 8px 10px;
      border-bottom: 1px solid #eee;
    }

    .tabla-reporte tr:last-child td { border-bottom: none; }

    .fila-total td {
      font-weight: 700;
      background: var(--gris);
    }

    .seccion-balance {
      text-align: center;
      padding: 20px;
      border-radius: 12px;
      margin-bottom: 28px;
      border: 2px dashed var(--borde);
    }

    .seccion-balance .label { color: var(--suave); font-size: 0.85rem; }
    .seccion-balance .valor-balance {
      font-family: 'Syne', sans-serif;
      font-size: 2.2rem;
      font-weight: 800;
    }

    .seccion-balance .valor-balance.pos { color: var(--verde); }
    .seccion-balance .valor-balance.neg { color: var(--rojo);  }

    /* Gráfico */
    .grafico-wrapper {
      text-align: center;
    }

    .grafico-wrapper h3 {
      font-family: 'Syne', sans-serif;
      font-size: 1rem;
      font-weight: 700;
      margin-bottom: 16px;
    }

    #graficoPastel {
      max-width: 300px;
      max-height: 300px;
      margin: 0 auto;
    }
  </style>
</head>
<body>
<?php include 'includes/sidebar.php'; ?>
<main class="main">
  <div class="topbar">
    <h1>Reporte de Balance</h1>
    <div class="usuario">
      <div class="avatar"><?= strtoupper(substr($_SESSION['usuario_nombre'], 0, 1)) ?></div>
      <?= htmlspecialchars($_SESSION['usuario_nombre']) ?>
    </div>
  </div>

  <div class="reporte-header">
    <p class="fecha-reporte">📅 Generado el <?= $fechaHoy ?></p>
    <button class="btn-pdf" onclick="exportarPDF()">🖨️ Exportar a PDF</button>
  </div>

  <!-- Zona que se exportará a PDF -->
  <div id="zona-pdf">
    <p class="pdf-titulo">Reporte Mensual de Finanzas</p>
    <p class="pdf-subtitulo">Fecha de generación: <?= $fechaHoy ?> &nbsp;|&nbsp; Usuario: <?= htmlspecialchars($_SESSION['usuario_nombre']) ?></p>

    <!-- Tablas en paralelo -->
    <div class="tablas-reporte">
      <!-- Entradas -->
      <div class="tabla-reporte e">
        <h3>💰 Entradas</h3>
        <table>
          <thead>
            <tr><th>Tipo</th><th>Monto</th><th>Fecha</th></tr>
          </thead>
          <tbody>
            <?php foreach ($entradas as $e): ?>
            <tr>
              <td><?= htmlspecialchars($e['tipo_entrada']) ?></td>
              <td>$<?= number_format($e['monto'], 2) ?></td>
              <td><?= date('d/m/Y', strtotime($e['fecha'])) ?></td>
            </tr>
            <?php endforeach; ?>
            <tr class="fila-total">
              <td colspan="1"><strong>TOTAL</strong></td>
              <td colspan="2"><strong>$<?= number_format($totalE, 2) ?></strong></td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Salidas -->
      <div class="tabla-reporte s">
        <h3>💸 Salidas</h3>
        <table>
          <thead>
            <tr><th>Tipo</th><th>Monto</th><th>Fecha</th></tr>
          </thead>
          <tbody>
            <?php foreach ($salidas as $s): ?>
            <tr>
              <td><?= htmlspecialchars($s['tipo_salida']) ?></td>
              <td>$<?= number_format($s['monto'], 2) ?></td>
              <td><?= date('d/m/Y', strtotime($s['fecha'])) ?></td>
            </tr>
            <?php endforeach; ?>
            <tr class="fila-total">
              <td colspan="1"><strong>TOTAL</strong></td>
              <td colspan="2"><strong>$<?= number_format($totalS, 2) ?></strong></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Balance resultante -->
    <div class="seccion-balance">
      <p class="label">Balance Mensual (Entradas − Salidas)</p>
      <div class="valor-balance <?= $balance >= 0 ? 'pos' : 'neg' ?>">
        $<?= number_format($balance, 2) ?>
      </div>
    </div>

    <!-- Gráfico de pastel -->
    <div class="grafico-wrapper">
      <h3>Gráfico de balance mensual — Entradas vs Salidas</h3>
      <canvas id="graficoPastel"></canvas>
    </div>
  </div>
</main>

<script>
// ── Gráfico de pastel con Chart.js ──
const ctx = document.getElementById('graficoPastel').getContext('2d');
new Chart(ctx, {
  type: 'pie',
  data: {
    labels: [
      'Entradas (<?= $porcentajes['entradas'] ?>%)',
      'Salidas (<?= $porcentajes['salidas'] ?>%)'
    ],
    datasets: [{
      data: [<?= $totalE ?>, <?= $totalS ?>],
      backgroundColor: ['#25a96a', '#c0392b'],
      borderColor:     ['#1a7a4a', '#922b21'],
      borderWidth: 2,
    }]
  },
  options: {
    responsive: true,
    plugins: {
      legend: { position: 'right' }
    }
  }
});

// ── Exportar a PDF ──
function exportarPDF() {
  const zona = document.getElementById('zona-pdf');
  const opciones = {
    margin:       [10, 10, 10, 10],
    filename:     'reporte_balance.pdf',
    image:        { type: 'jpeg', quality: 0.98 },
    html2canvas:  { scale: 2, useCORS: true },
    jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
  };
  html2pdf().set(opciones).from(zona).save();
}
</script>
</body>
</html>
