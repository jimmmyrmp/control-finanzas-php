<?php
// classes/Conexion.php

class Conexion {
    private static $instancia = null;
    private $pdo;

    private $host     = '127.0.0.1';
    private $dbname   = 'finanzas_db';
    private $usuario  = 'root';
    private $password = '';
    private $charset  = 'utf8mb4';

    private function __construct() {
        // Mantenemos el puerto 3307 que necesita tu computadora
        $dsn = "mysql:host={$this->host};port=3307;dbname={$this->dbname};charset={$this->charset}";
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

    public static function obtener() {
        if (self::$instancia == null) {
            self::$instancia = new self();
        }
        return self::$instancia->pdo;
    }
}
?>