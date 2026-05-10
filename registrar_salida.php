<?php
session_start();
require_once 'classes/Login.php';
require_once 'classes/Salidas.php';

Login::verificarSesion();

$mensaje = "";
$tipo = "";

if ($_POST) {
    $tipoSalida = $_POST['tipo_salida'];
    $monto = $_POST['monto'];
    $fecha = $_POST['fecha'];
    $archivo = $_FILES['factura'];

    if (empty($tipoSalida) || empty($monto) || empty($fecha)) {
        $mensaje = 'Por favor completa todos los campos obligatorios.';
        $tipo = 'error';
    } else {
        $salida = new Salidas();
        if ($salida->registrar($tipoSalida, $monto, $fecha, $archivo)) {
            $mensaje = 'Salida registrada correctamente.';
            $tipo = 'exito';
        } else {
            $mensaje = 'Error al registrar. Verifica el archivo de factura.';
            $tipo = 'error';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Salida — Control de Finanzas</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        <?php include 'assets/css/base.css'; ?>
    </style>
</head>
<body>
<?php include 'includes/sidebar.php'; ?>
<main class="main">
    <div class="topbar">
        <h1>Registrar Salida</h1>
        <div class="usuario">
            <div class="avatar"><?= strtoupper(substr($_SESSION['usuario_nombre'], 0, 1)) ?></div>
            <?= htmlspecialchars($_SESSION['usuario_nombre']) ?>
        </div>
    </div>
    <div class="form-card">
        <div class="form-header s">
            <span>💸</span>
            <div>
                <h2>Nueva salida</h2>
                <p>Registra un gasto con su detalle y factura</p>
            </div>
        </div>
        
        <?php if ($mensaje != ""): ?>
            <div class="alerta <?= $tipo ?>"><?= $mensaje ?></div>
        <?php endif; ?>

        <form method="POST" action="registrar_salida.php" enctype="multipart/form-data">
            <div class="form-grid">
                <div class="grupo">
                    <label>Tipo de salida *</label>
                    <select name="tipo_salida" required>
                        <option value="">— Selecciona —</option>
                        <option value="Luz">Luz</option>
                        <option value="Agua">Agua</option>
                        <option value="Gas">Gas</option>
                        <option value="Ropa">Ropa</option>
                        <option value="Comida">Comida</option>
                        <option value="Casa">Casa / Alquiler</option>
                        <option value="Transporte">Transporte</option>
                        <option value="Otras">Otras</option>
                    </select>
                </div>
                <div class="grupo">
                    <label>Monto de salida ($) *</label>
                    <input type="number" name="monto" min="0.01" step="0.01" placeholder="0.00" required>
                </div>
                <div class="grupo">
                    <label>Fecha de salida *</label>
                    <input type="date" name="fecha" value="<?= date('Y-m-d') ?>" required>
                </div>
                <div class="grupo">
                    <label>Factura de salida (imagen / PDF)</label>
                    <input type="file" name="factura" accept="image/*,.pdf">
                    <small>Formatos: JPG, PNG, GIF, WEBP, PDF</small>
                </div>
            </div>
            <div class="form-actions">
                <a href="dashboard.php" class="btn-sec">Cancelar</a>
                <button type="submit" class="btn-pri s">Registrar salida</button>
            </div>
        </form>
    </div>
</main>
</body>
</html>