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

// Récupération des messages d'erreur passés par le contrôleur
$errors = isset($errors) ? $errors : [];
$successMessage = isset($_GET['success']) ? "Projet ajouté avec succès!" : "";

$successMessage = "";
if (isset($_GET['success'])) {
    switch ($_GET['success']) {
        case 'add':
            $successMessage = "Projet ajouté avec succès!";
            break;
        case 'update':
            $successMessage = "Projet mis à jour avec succès!";
            break;
        case 'delete':
            $successMessage = "Projet supprimé avec succès!";
            break;
    }
}

// Message d'erreur
$errorMessage = "";
if (isset($_GET['error'])) {
    switch ($_GET['error']) {
        case 'notfound':
            $errorMessage = "Projet non trouvé.";
            break;
        case 'noid':
            $errorMessage = "ID de projet non spécifié.";
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <style>
        /* Styles existants */
        .btn { padding: 5px 10px; margin: 2px; text-decoration: none; display: inline-block; border-radius: 3px; }
        .btn-edit { background: #2196F3; color: white; }
        .btn-delete { background: #f44336; color: white; }
        .modal { display: none; position: fixed; z-index: 1; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.4); }
        .modal-content { background-color: #fefefe; margin: 15% auto; padding: 20px; border: 1px solid #888; width: 50%; }
        .close { color: #aaa; float: right; font-size: 28px; font-weight: bold; cursor: pointer; }
    </style>
</head>
<body>
    <h1>Bienvenue <?= htmlspecialchars($admin['nom']) ?> !</h1>
    <p>Vous êtes connecté en tant que <?= $admin['is_superadmin'] ? 'Superadmin' : 'Admin' ?>.</p>
    <a href="logout.php">Se déconnecter</a>

    <?php if (!empty($successMessage)): ?>
        <div class="success"><?= htmlspecialchars($successMessage) ?></div>
    <?php endif; ?>

    <?php if (!empty($errorMessage)): ?>
        <div class="error"><?= htmlspecialchars($errorMessage) ?></div>
    <?php endif; ?>

    <h2>Liste des projets</h2>
    <table>
        <tr>
            <th>Nom Projet</th>
            <th>Équipe</th>
            <th>Email</th>
            <th>Secteur</th>
            <th>Avancement</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($projets as $p): ?>
            <tr>
                <td><?= htmlspecialchars($p['nom_projet']) ?></td>
                <td><?= htmlspecialchars($p['nom_equipe']) ?></td>
                <td><?= htmlspecialchars($p['email']) ?></td>
                <td><?= htmlspecialchars($p['secteur_nom'] ?? 'Non défini') ?></td>
                <td><?= htmlspecialchars($p['avancement_prototype']) ?></td>
                <td>
                    <a href="edit-projet.php?id=<?= $p['id'] ?>" class="btn btn-edit">Modifier</a>
                    <a href="#" onclick="confirmDelete(<?= $p['id'] ?>, '<?= htmlspecialchars(addslashes($p['nom_projet'])) ?>')" class="btn btn-delete">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h3>Confirmer la suppression</h3>
            <p>Êtes-vous sûr de vouloir supprimer le projet <span id="projectName"></span>?</p>
            <form method="post" action="dashboard.php">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" id="deleteId">
                <button type="submit" class="btn btn-delete">Confirmer la suppression</button>
                <button type="button" onclick="closeModal()" class="btn">Annuler</button>
            </form>
        </div>
    </div>

    <h2>Ajouter un projet</h2>
    <form method="post">
        <!-- Token CSRF pour sécuriser le formulaire -->
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        
        <input name="nom_projet" placeholder="Nom du projet" required>
        <input name="nom_equipe" placeholder="Nom de l'équipe" required>
        <input name="email" type="email" placeholder="Email" required>
        <input name="telephone" placeholder="Téléphone">
        <input name="etablissement" placeholder="Établissement">
        <input name="theme" placeholder="Thème" required>
        <input name="avancement_prototype" placeholder="Avancement" required>
        <input name="play_video_url" placeholder="Lien vidéo">
        <textarea name="detail" placeholder="Détails"></textarea>
        <select name="secteur_id" required>
            <option value="">-- Secteur --</option>
            <?php
                try {
                    $stmt = $pdo->query("SELECT id, nom FROM secteurs ORDER BY nom");
                    $secteurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($secteurs as $secteur) {
                        echo '<option value="' . htmlspecialchars($secteur['id']) . '">' . 
                             htmlspecialchars($secteur['nom']) . '</option>';
                    }
                } catch (PDOException $e) {
                    echo '<option disabled>Erreur lors du chargement</option>';
                }
            ?>
        </select>

        <button type="submit">Ajouter</button>
    </form>
</body>
<script>
        // Fonction pour afficher le modal de confirmation de suppression
        function confirmDelete(id, name) {
            document.getElementById('deleteId').value = id;
            document.getElementById('projectName').textContent = name;
            document.getElementById('deleteModal').style.display = 'block';
        }

        // Fonction pour fermer le modal
        function closeModal() {
            document.getElementById('deleteModal').style.display = 'none';
        }

        // Fermer le modal si l'utilisateur clique en dehors
        window.onclick = function(event) {
            var modal = document.getElementById('deleteModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    </script>

</html>