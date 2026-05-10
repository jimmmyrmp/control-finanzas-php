<?php
// classes/Login.php

require_once 'Conexion.php';

class Login {
    private $pdo;
    private $error = '';

    public function __construct() {
        $this->pdo = Conexion::obtener();
    }

    public function iniciarSesion($email, $password) {
        if (empty($email) || empty($password)) {
            $this->error = 'Por favor completa todos los campos.';
            return false;
        }

        $stmt = $this->pdo->prepare("SELECT id, nombre, email, password FROM usuarios WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch();

        if ($usuario && password_verify($password, $usuario['password'])) {
            $_SESSION['usuario_id']     = $usuario['id'];
            $_SESSION['usuario_nombre'] = $usuario['nombre'];
            $_SESSION['usuario_email']  = $usuario['email'];
            return true;
        } else {
            $this->error = 'Correo o contraseña incorrectos.';
            return false;
        }
    }

    public static function cerrarSesion() {
        session_start();
        session_unset();
        session_destroy();
        header('Location: index.php');
        exit;
    }

    public static function verificarSesion() {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php');
            exit;
        }
    }

    public function getError() {
        return $this->error;
    }
}
?>