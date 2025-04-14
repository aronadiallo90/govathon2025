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
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Dashboard - Kaiadmin Bootstrap 5 Admin Dashboard</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <link rel="icon" href="../assets/img/kaiadmin/favicon.ico" type="image/x-icon" />

    <!-- Fonts and icons -->
    <script src="../assets/js/plugin/webfont/webfont.min.js"></script>
    <script>
        WebFont.load({
            google: { families: ["Public Sans:300,400,500,600,700"] },
            custom: {
                families: [
                    "Font Awesome 5 Solid",
                    "Font Awesome 5 Regular",
                    "Font Awesome 5 Brands",
                    "simple-line-icons",
                ],
                urls: ["../assets/css/fonts.min.css"],
            },
            active: function () {
                sessionStorage.fonts = true;
            },
        });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../assets/css/plugins.min.css" />
    <link rel="stylesheet" href="../assets/css/kaiadmin.min.css" />

    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link rel="stylesheet" href="../assets/css/demo.css" />
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <div class="sidebar" data-background-color="dark">
            <div class="sidebar-logo">
                <a href="index.html" class="logo">
                    <img src="../assets/img/kaiadmin/logo_light.svg" alt="navbar brand" class="navbar-brand" height="20" />
                </a>
            </div>
        </div>
        <!-- End Sidebar -->

        <div class="main-panel">
            <div class="main-header">
                <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
                    <div class="container-fluid">
                        <h3 class="fw-bold mb-3">Dashboard</h3>
                    </div>
                </nav>
            </div>

            <div class="container">
                <div class="page-inner">
                    <div class="page-header">
                        <h4 class="page-title">Bienvenue <?= htmlspecialchars($admin['nom']) ?> !</h4>
                        <p>Vous êtes connecté en tant que <?= $admin['is_superadmin'] ? 'Superadmin' : 'Admin' ?>.</p>
                        <a href="logout.php" class="btn btn-danger">Se déconnecter</a>
                    </div>

                    <?php if (!empty($successMessage)): ?>
                        <div class="alert alert-success"><?= htmlspecialchars($successMessage) ?></div>
                    <?php endif; ?>

                    <?php if (!empty($errorMessage)): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($errorMessage) ?></div>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Liste des projets</h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Nom Projet</th>
                                                    <th>Équipe</th>
                                                    <th>Email</th>
                                                    <th>Secteur</th>
                                                    <th>Avancement</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($projets as $p): ?>
                                                    <tr>
                                                        <td><?= htmlspecialchars($p['nom_projet']) ?></td>
                                                        <td><?= htmlspecialchars($p['nom_equipe']) ?></td>
                                                        <td><?= htmlspecialchars($p['email']) ?></td>
                                                        <td><?= htmlspecialchars($p['secteur_nom'] ?? 'Non défini') ?></td>
                                                        <td><?= htmlspecialchars($p['avancement_prototype']) ?></td>
                                                        <td>
                                                            <a href="edit-projet.php?id=<?= $p['id'] ?>" class="btn btn-primary btn-sm">Modifier</a>
                                                            <a href="#" onclick="confirmDelete(<?= $p['id'] ?>, '<?= htmlspecialchars(addslashes($p['nom_projet'])) ?>')" class="btn btn-danger btn-sm">Supprimer</a>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h2>Ajouter un projet</h2>
                    <form method="post">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                        <div class="form-group">
                            <label for="nom_projet">Nom du projet</label>
                            <input id="nom_projet" name="nom_projet" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="nom_equipe">Nom de l'équipe</label>
                            <input id="nom_equipe" name="nom_equipe" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input id="email" name="email" type="email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="secteur_id">Secteur</label>
                            <select id="secteur_id" name="secteur_id" class="form-control" required>
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
                        </div>
                        <button type="submit" class="btn btn-success">Ajouter</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Core JS Files -->
    <script src="../assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="../assets/js/core/popper.min.js"></script>
    <script src="../assets/js/core/bootstrap.min.js"></script>
    <script src="../assets/js/plugin/datatables/datatables.min.js"></script>
</body>
</html>