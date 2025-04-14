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

// Génération du token CSRF si non existant
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$admin = $auth->getAdmin();

// Vérifier si un ID de projet est fourni
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: dashboard.php?error=noid');
    exit;
}

// Récupérer les données du projet
$projet = $projetController->getProjetById($_GET['id']);
if (!$projet) {
    header('Location: dashboard.php?error=notfound');
    exit;
}

// Récupération des messages d'erreur passés par le contrôleur
$errors = isset($errors) ? $errors : [];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un projet</title>
    <style>
        .error { color: red; background: #f2dede; padding: 10px; margin: 10px 0; border-radius: 5px; }
        form { margin: 20px 0; }
        input, select, textarea { margin-bottom: 10px; padding: 8px; width: 100%; box-sizing: border-box; }
        button { padding: 10px; margin-right: 10px; }
        .btn-primary { background: #4CAF50; color: white; border: none; cursor: pointer; }
        .btn-primary:hover { background: #45a049; }
        .btn-danger { background: #f44336; color: white; border: none; cursor: pointer; }
        .btn-danger:hover { background: #d32f2f; }
    </style>
</head>
<body>
    <h1>Modifier le projet: <?= htmlspecialchars($projet['nom_projet']) ?></h1>
    <a href="dashboard.php">Retour au dashboard</a>

    <?php if (!empty($errors)): ?>
        <div class="error">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" action="dashboard.php">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        <input type="hidden" name="action" value="update">
        <input type="hidden" name="id" value="<?= $projet['id'] ?>">
        
        <label for="nom_projet">Nom du projet</label>
        <input id="nom_projet" name="nom_projet" value="<?= htmlspecialchars($projet['nom_projet']) ?>" required>
        
        <label for="nom_equipe">Nom de l'équipe</label>
        <input id="nom_equipe" name="nom_equipe" value="<?= htmlspecialchars($projet['nom_equipe']) ?>" required>
        
        <label for="email">Email</label>
        <input id="email" type="email" name="email" value="<?= htmlspecialchars($projet['email']) ?>" required>
        
        <label for="telephone">Téléphone</label>
        <input id="telephone" name="telephone" value="<?= htmlspecialchars($projet['telephone'] ?? '') ?>">
        
        <label for="etablissement">Établissement</label>
        <input id="etablissement" name="etablissement" value="<?= htmlspecialchars($projet['etablissement'] ?? '') ?>">
        
        <label for="theme">Thème</label>
        <input id="theme" name="theme" value="<?= htmlspecialchars($projet['theme']) ?>" required>
        
        <label for="avancement_prototype">Avancement</label>
        <input id="avancement_prototype" name="avancement_prototype" value="<?= htmlspecialchars($projet['avancement_prototype']) ?>" required>
        
        <label for="play_video_url">Lien vidéo</label>
        <input id="play_video_url" name="play_video_url" value="<?= htmlspecialchars($projet['play_video_url'] ?? '') ?>">
        
        <label for="detail">Détails</label>
        <textarea id="detail" name="detail" rows="5"><?= htmlspecialchars($projet['detail'] ?? '') ?></textarea>
        
        <label for="secteur_id">Secteur</label>
        <select id="secteur_id" name="secteur_id" required>
            <option value="">-- Secteur --</option>
            <?php
                try {
                    $stmt = $pdo->query("SELECT id, nom FROM secteurs ORDER BY nom");
                    $secteurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($secteurs as $secteur) {
                        $selected = ($secteur['id'] == $projet['secteur_id']) ? 'selected' : '';
                        echo '<option value="' . htmlspecialchars($secteur['id']) . '" ' . $selected . '>' . 
                             htmlspecialchars($secteur['nom']) . '</option>';
                    }
                } catch (PDOException $e) {
                    echo '<option disabled>Erreur lors du chargement</option>';
                }
            ?>
        </select>

        <div>
            <button type="submit" class="btn-primary">Enregistrer les modifications</button>
            <a href="dashboard.php" class="btn-danger">Annuler</a>
        </div>
    </form>
</body>
</html>