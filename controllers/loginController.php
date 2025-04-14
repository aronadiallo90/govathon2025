<?php
require_once '../src/Auth/AuthService.php';
session_start();

$pdo = new PDO('mysql:host=localhost;dbname=govathon2025', 'root', '');
$authService = new AuthService($pdo);

$admin = null; // Initialisation de la variable
$error = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['mot_de_passe'];

    if ($authService->login($email, $password)) {
        $admin = $authService->getAdmin();
        header('Location: dashboard.php'); // Redirection après connexion réussie
        exit;
    } else {
        $error = true; // Identifiants invalides
    }
}
