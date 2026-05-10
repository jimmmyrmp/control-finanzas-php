<?php
session_start();

require_once 'classes/Login.php';
require_once 'classes/Entradas.php';

// Verificamos que no entren sin loguearse
Login::verificarSesion();

$mensaje = "";

// Si enviaron el formulario
if ($_POST) {
    $tipoEntrada = $_POST['tipo_entrada'];
    $monto = $_POST['monto'];
    $fecha = $_POST['fecha'];
    $archivo = $_FILES['factura'];

    if (empty($tipoEntrada) || empty($monto) || empty($fecha)) {
        $mensaje = "Por favor completa todos los campos obligatorios.";
    } else {
        $entrada = new Entradas();
        $resultado = $entrada->registrar($tipoEntrada, $monto, $fecha, $archivo);

        if ($resultado) {
            $mensaje = "Entrada registrada correctamente.";
        } else {
            $mensaje = "Error al registrar. Verifica el archivo de la factura.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Entrada</title>
    <link rel="stylesheet" href="assets/css/base.css">
</head>
<body>

<?php include 'includes/sidebar.php'; ?>

<main class="main">
    <div class="topbar">
        <h1>Registrar Entrada</h1>
        <div class="usuario">
            <?php echo $_SESSION['usuario_nombre']; ?>
        </div>
    </div>

    <div class="form-card">
        <h2>Nueva entrada</h2>
        <p>Registra un ingreso con su detalle y factura</p>

        <?php if ($mensaje != "") { ?>
            <div class="alerta">
                <?php echo $mensaje; ?>
            </div>
        <?php } ?>

        <form action="registrar_entrada.php" method="POST" enctype="multipart/form-data">
            <div class="form-grid">
                
                <div class="grupo">
                    <label>Tipo de entrada *</label>
                    <select name="tipo_entrada" required>
                        <option value="">-- Selecciona --</option>
                        <option value="Sueldo del mes">Sueldo del mes</option>
                        <option value="Cheque de sistema">Cheque de sistema</option>
                        <option value="Remesa">Remesa</option>
                        <option value="Venta">Venta</option>
                        <option value="Otro">Otro</option>
                    </select>
                </div>

                <div class="grupo">
                    <label>Monto ($) *</label>
                    <input type="number" name="monto" step="0.01" required>
                </div>

                <div class="grupo">
                    <label>Fecha *</label>
                    <input type="date" name="fecha" value="<?php echo date('Y-m-d'); ?>" required>
                </div>

                <div class="grupo">
                    <label>Factura (imagen o PDF)</label>
                    <input type="file" name="factura" accept=".jpg, .jpeg, .png, .pdf">
                </div>

            </div>
            
            <br>
            <div class="form-actions">
                <button type="submit" class="btn-pri e">Guardar entrada</button>
                <a href="dashboard.php" class="btn-sec">Cancelar</a>
            </div>
        </form>
    </div>
</main>

</body>
</html>