<?php
// classes/Salidas.php
// Clase que maneja el registro y consulta de salidas

require_once __DIR__ . '/Conexion.php';

class Salidas {
    private $pdo;
    private $carpetaFacturas = '../uploads/';

    public function __construct() {
        $this->pdo = Conexion::obtener();
    }

    // Registra una salida nueva en la BD
    public function registrar(string $tipo, float $monto, string $fecha, array $archivo = []): bool {
        $rutaFactura = null;

        if (!empty($archivo['name'])) {
            $rutaFactura = $this->subirFactura($archivo);
            if ($rutaFactura === false) return false;
        }

        $stmt = $this->pdo->prepare(
            "INSERT INTO salidas (tipo_salida, monto, fecha, factura)
             VALUES (?, ?, ?, ?)"
        );
        return $stmt->execute([$tipo, $monto, $fecha, $rutaFactura]);
    }

    // Obtiene todas las salidas
    public function obtenerTodas(): array {
        $stmt = $this->pdo->query(
            "SELECT * FROM salidas ORDER BY fecha DESC, creado_en DESC"
        );
        return $stmt->fetchAll();
    }

    // Suma total de todas las salidas
    public function totalSalidas(): float {
        $stmt = $this->pdo->query("SELECT COALESCE(SUM(monto), 0) AS total FROM salidas");
        return (float) $stmt->fetch()['total'];
    }

    private function subirFactura(array $archivo) {
        $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf'];
        $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));

        if (!in_array($extension, $extensionesPermitidas)) {
            return false;
        }

        $nombreArchivo = 'salida_' . time() . '_' . uniqid() . '.' . $extension;
        $destino       = $this->carpetaFacturas . $nombreArchivo;

        if (move_uploaded_file($archivo['tmp_name'], $destino)) {
            return 'uploads/' . $nombreArchivo;
        }
        return false;
    }
}
