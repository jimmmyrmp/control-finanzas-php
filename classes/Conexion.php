<?php
// classes/Conexion.php
// Clase de conexión a la base de datos con PDO

class Conexion {
    private static $instancia = null;
    private $pdo;

    // Ajusta estos valores según tu XAMPP
    private $host     = '127.0.0.1';
    private $dbname   = 'finanzas_db';
    private $usuario  = 'root';
    private $password = '';
    private $charset  = 'utf8mb4';

    private function __construct() {
        $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset={$this->charset}";
        $opciones = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
            $this->pdo = new PDO($dsn, $this->usuario, $this->password, $opciones);
        } catch (PDOException $e) {
            die("Error de conexión a la BD: " . $e->getMessage());
        }
    }

    // Patrón Singleton — siempre la misma instancia
    public static function obtener(): PDO {
        if (self::$instancia === null) {
            self::$instancia = new self();
        }
        return self::$instancia->pdo;
    }
}
