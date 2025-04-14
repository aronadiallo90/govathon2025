<?php
require_once '../config/database.php';

// Récupérer les secteurs
$stmt = $pdo->query("SELECT id, nom FROM secteurs ORDER BY nom");
$secteurs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un projet</title>
</head>
<body>
    <h2>Ajout d’un projet</h2>

    <form method="post" action="traiter_ajout_projet.php">
        <label>Nom de l'équipe :</label>
        <input type="text" name="nom_equipe" required><br>

        <label>Nom du projet :</label>
        <input type="text" name="nom_projet" required><br>

        <label>Établissement :</label>
        <input type="text" name="etablissement"><br>

        <label>Email :</label>
        <input type="email" name="email" required><br>

        <label>Téléphone :</label>
        <input type="text" name="telephone"><br>

        <label>Secteur :</label>
        <select name="secteur_id" required>
            <option value="">-- Sélectionner un secteur --</option>
            <?php foreach ($secteurs as $secteur): ?>
                <option value="<?= htmlspecialchars($secteur['id']) ?>">
                    <?= htmlspecialchars($secteur['nom']) ?>
                </option>
            <?php endforeach; ?>
        </select><br>

        <label>Thème :</label>
        <input type="text" name="theme"><br>

        <label>Avancement prototype :</label>
        <input type="text" name="avancement_prototype"><br>

        <label>Vidéo (URL YouTube ou autre) :</label>
        <input type="url" name="play_video_url"><br>

        <label>Détail :</label><br>
        <textarea name="detail" rows="4" cols="50"></textarea><br>

        <button type="submit">Enregistrer</button>
    </form>
</body>
</html>
