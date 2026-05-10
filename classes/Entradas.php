<?php
// classes/Entradas.php

require_once 'Conexion.php';

class Entradas {
    private $pdo;

    public function __construct() {
        $this->pdo = Conexion::obtener();
    }

    public function registrar($tipo, $monto, $fecha, $archivo) {
        $rutaFactura = "";

        if (!empty($archivo['name'])) {
            $rutaFactura = $this->subirFactura($archivo);
            if ($rutaFactura == false) {
                return false;
            }
        }

        $sql = "INSERT INTO entradas (tipo_entrada, monto, fecha, factura) VALUES (?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$tipo, $monto, $fecha, $rutaFactura]);
    }

    public function obtenerTodas() {
        $sql = "SELECT * FROM entradas ORDER BY fecha DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    public function totalEntradas() {
        $sql = "SELECT SUM(monto) as total FROM entradas";
        $stmt = $this->pdo->query($sql);
        $resultado = $stmt->fetch();
        return $resultado['total'] ? $resultado['total'] : 0;
    }

    private function subirFactura($archivo) {
        $nombreOriginal = $archivo['name'];
        $extension = strtolower(pathinfo($nombreOriginal, PATHINFO_EXTENSION));
        $permitidos = array('jpg', 'jpeg', 'png', 'pdf');

        if (in_array($extension, $permitidos)) {
            $nuevoNombre = 'entrada_' . time() . '_' . $nombreOriginal;
            $destino = 'uploads/' . $nuevoNombre;

            if (move_uploaded_file($archivo['tmp_name'], $destino)) {
                return $destino;
            }
        }
        return false;
    }
}
?>