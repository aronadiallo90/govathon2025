<?php
require_once '../controllers/loginController.php';

if (isset($admin) && !$admin) {
    error_log("Aucun utilisateur trouvé pour l'email.");
} elseif (isset($admin) && !isset($admin['mot_de_passe_hash'])) {
    error_log("La colonne 'mot_de_passe_hash' est manquante.");
} elseif (isset($admin) && !password_verify($password, $admin['mot_de_passe_hash'])) {
    error_log("Mot de passe incorrect pour l'utilisateur.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion Admin</title>
</head>
<body>
    <h2>Connexion</h2>

    <?php if (isset($error) && $error) : ?>
        <p style="color:red;">Identifiants invalides. Veuillez réessayer.</p>
    <?php endif; ?>

    <form method="post" action="">
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="mot_de_passe" placeholder="Mot de passe" required><br>
        <button type="submit">Se connecter</button>
    </form>
</body>
</html>
