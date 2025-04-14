<?php
class AuthService {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function login($email, $password) {
        $stmt = $this->pdo->prepare("SELECT * FROM admins WHERE email = ?");
        $stmt->execute([$email]);
        $admin = $stmt->fetch();

        if ($admin && isset($admin['mot_de_passe_hash']) && password_verify($password, $admin['mot_de_passe_hash'])) {
            $_SESSION['admin'] = $admin;
            return true;
        }

        return false;
    }

    public function isAuthenticated() {
        return isset($_SESSION['admin']);
    }

    public function logout() {
        session_destroy();
    }

    public function getAdmin() {
        return $_SESSION['admin'] ?? null;
    }
}
