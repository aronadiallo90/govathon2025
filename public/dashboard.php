<?php
session_start();
require_once '../config/database.php';
require_once '../src/Auth/AuthService.php';
require_once '../controllers/projetController.php';

$auth = new AuthService($pdo);

if (!$auth->isAuthenticated()) {
    header('Location: login.php');
    exit;
}

$admin = $auth->getAdmin();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>
<body>
    <h1>Bienvenue <?= htmlspecialchars($admin['nom']) ?> !</h1>
    <p>Vous êtes connecté en tant que <?= $admin['is_superadmin'] ? 'Superadmin' : 'Admin' ?>.</p>
    <a href="logout.php">Se déconnecter</a>




    <h2>Liste des projets</h2>
<table border="1">
    <tr>
        <th>Nom Projet</th><th>Équipe</th><th>Email</th><th>Secteur</th><th>Avancement</th>
    </tr>
    <?php foreach ($projets as $p): ?>
        <tr>
            <td><?= htmlspecialchars($p['nom_projet']) ?></td>
            <td><?= htmlspecialchars($p['nom_equipe']) ?></td>
            <td><?= htmlspecialchars($p['email']) ?></td>
            <td><?= htmlspecialchars($p['secteur_nom']) ?></td>
            <td><?= htmlspecialchars($p['avancement_prototype']) ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<h2>Ajouter un projet</h2>
<form method="post">
    <input name="nom_projet" placeholder="Nom du projet" required><br>
    <input name="nom_equipe" placeholder="Nom de l'équipe" required><br>
    <input name="email" placeholder="Email" required><br>
    <input name="telephone" placeholder="Téléphone"><br>
    <input name="etablissement" placeholder="Établissement"><br>
    <input name="theme" placeholder="Thème" required><br>
    <input name="avancement_prototype" placeholder="Avancement" required><br>
    <input name="play_video_url" placeholder="Lien vidéo"><br>
    <textarea name="detail" placeholder="Détails"></textarea><br>
    <select name="secteur_id" required>
    <option value="">-- Secteur --</option>
    <?php
        require_once '../config/database.php';

        try {
            $stmt = $pdo->query("SELECT id, nom FROM secteurs ORDER BY nom");
            $secteurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($secteurs as $secteur) {
                echo '<option value="' . htmlspecialchars($secteur['id']) . '">' . htmlspecialchars($secteur['nom']) . '</option>';
            }
        } catch (PDOException $e) {
            echo '<option disabled>Erreur lors du chargement</option>';
        }
    ?>
</select><br>

    <button type="submit">Ajouter</button>
</form>



    
</body>
</html>
