<?php
// classes/Login.php
// Clase que maneja autenticación de usuarios

require_once __DIR__ . '/Conexion.php';

class Login {
    private $pdo;
    private $error = '';

    public function __construct() {
        $this->pdo = Conexion::obtener();
    }

    // Intenta iniciar sesión con email y contraseña
    public function iniciarSesion(string $email, string $password): bool {
        if (empty($email) || empty($password)) {
            $this->error = 'Por favor completa todos los campos.';
            return false;
        }

        $stmt = $this->pdo->prepare(
            "SELECT id, nombre, email, password FROM usuarios WHERE email = ? LIMIT 1"
        );
        $stmt->execute([$email]);
        $usuario = $stmt->fetch();

        if (!$usuario || !password_verify($password, $usuario['password'])) {
            $this->error = 'Correo o contraseña incorrectos.';
            return false;
        }

        // Guardar datos del usuario en la sesión
        $_SESSION['usuario_id']     = $usuario['id'];
        $_SESSION['usuario_nombre'] = $usuario['nombre'];
        $_SESSION['usuario_email']  = $usuario['email'];
        return true;
    }

    // Cierra la sesión del usuario
    public static function cerrarSesion(): void {
        session_start();
        session_unset();
        session_destroy();
        header('Location: index.php');
        exit;
    }

    // Verifica que el usuario tenga sesión activa (usar en cada página protegida)
    public static function verificarSesion(): void {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php');
            exit;
        }
    }

    public function getError(): string {
        return $this->error;
    }
}
