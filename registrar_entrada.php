<?php
session_start();

require_once 'classes/Login.php';
require_once 'classes/Entradas.php';

Login::verificarSesion();

$mensaje = "";
$tipo    = "";

if ($_POST) {
    $tipoEntrada = $_POST['tipo_entrada'];
    $monto       = $_POST['monto'];
    $fecha       = $_POST['fecha'];
    $archivo     = $_FILES['factura'];

    if (empty($tipoEntrada) || empty($monto) || empty($fecha)) {
        $mensaje = "Por favor completa todos los campos obligatorios.";
        $tipo    = "error";
    } else {
        $entrada   = new Entradas();
        $resultado = $entrada->registrar($tipoEntrada, $monto, $fecha, $archivo);

        if ($resultado) {
            $mensaje = "Entrada registrada correctamente.";
            $tipo    = "exito";
        } else {
            $mensaje = "Error al registrar. Verifica el archivo de la factura.";
            $tipo    = "error";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Entrada — Control de Finanzas</title>
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
        <h1>Registrar Entrada</h1>
        <div class="usuario">
            <div class="avatar"><?= strtoupper(substr($_SESSION['usuario_nombre'], 0, 1)) ?></div>
            <?= htmlspecialchars($_SESSION['usuario_nombre']) ?>
        </div>
    </div>

    <div class="form-card">
        <div class="form-header e">
            <span>💰</span>
            <div>
                <h2>Nueva entrada</h2>
                <p>Registra un ingreso con su detalle y factura</p>
            </div>
        </div>

        <?php if ($mensaje != ""): ?>
            <div class="alerta <?= $tipo ?>"><?= $mensaje ?></div>
        <?php endif; ?>

        <form method="POST" action="registrar_entrada.php" enctype="multipart/form-data">
            <div class="form-grid">
                <div class="grupo">
                    <label>Tipo de entrada *</label>
                    <select name="tipo_entrada" required>
                        <option value="">— Selecciona —</option>
                        <option value="Sueldo del mes">Sueldo del mes</option>
                        <option value="Cheque de sistema">Cheque de sistema</option>
                        <option value="Remesa">Remesa</option>
                        <option value="Venta">Venta</option>
                        <option value="Otro">Otro</option>
                    </select>
                </div>
                <div class="grupo">
                    <label>Monto ($) *</label>
                    <input type="number" name="monto" min="0.01" step="0.01" placeholder="0.00" required>
                </div>
                <div class="grupo">
                    <label>Fecha *</label>
                    <input type="date" name="fecha" value="<?= date('Y-m-d') ?>" required>
                </div>
                <div class="grupo">
                    <label>Factura (imagen o PDF)</label>
                    <input type="file" name="factura" accept="image/*,.pdf">
                    <small>Formatos: JPG, PNG, GIF, WEBP, PDF</small>
                </div>
            </div>
            <div class="form-actions">
                <a href="dashboard.php" class="btn-sec">Cancelar</a>
                <button type="submit" class="btn-pri e">Registrar entrada</button>
            </div>
        </form>
    </div>
</main>
</body>
</html>