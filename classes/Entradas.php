<?php
// classes/Entradas.php
// Clase que maneja el registro y consulta de entradas

require_once __DIR__ . '/Conexion.php';

class Entradas {
    private $pdo;
    private $carpetaFacturas = '../uploads/';

    public function __construct() {
        $this->pdo = Conexion::obtener();
    }

    // Registra una entrada nueva en la BD
    public function registrar(string $tipo, float $monto, string $fecha, array $archivo = []): bool {
        $rutaFactura = null;

        // Subir imagen de factura si se proporcionó
        if (!empty($archivo['name'])) {
            $rutaFactura = $this->subirFactura($archivo);
            if ($rutaFactura === false) return false;
        }

        $stmt = $this->pdo->prepare(
            "INSERT INTO entradas (tipo_entrada, monto, fecha, factura)
             VALUES (?, ?, ?, ?)"
        );
        return $stmt->execute([$tipo, $monto, $fecha, $rutaFactura]);
    }

    // Obtiene todas las entradas ordenadas por fecha descendente
    public function obtenerTodas(): array {
        $stmt = $this->pdo->query(
            "SELECT * FROM entradas ORDER BY fecha DESC, creado_en DESC"
        );
        return $stmt->fetchAll();
    }

    // Suma total de todas las entradas
    public function totalEntradas(): float {
        $stmt = $this->pdo->query("SELECT COALESCE(SUM(monto), 0) AS total FROM entradas");
        return (float) $stmt->fetch()['total'];
    }

    // Sube el archivo de factura a la carpeta uploads/
    private function subirFactura(array $archivo) {
        $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf'];
        $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));

        if (!in_array($extension, $extensionesPermitidas)) {
            return false;
        }

        $nombreArchivo = 'entrada_' . time() . '_' . uniqid() . '.' . $extension;
        $destino       = $this->carpetaFacturas . $nombreArchivo;

        if (move_uploaded_file($archivo['tmp_name'], $destino)) {
            return 'uploads/' . $nombreArchivo;  // ruta relativa que se guarda en BD
        }
        return false;
    }
}
