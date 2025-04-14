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
$projetController = new ProjetController();
$projets = $projetController->getAllProjets();

// Gestion des actions (ajout, suppression, modification)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['csrf_token'] === $_SESSION['csrf_token']) {
        if ($_POST['action'] === 'add') {
            $projetController->addProjet($_POST);
            header('Location: dashboard.php?success=add');
            exit;
        } elseif ($_POST['action'] === 'delete') {
            $projetController->deleteProjet($_POST['id']);
            header('Location: dashboard.php?success=delete');
            exit;
        }
    }
}

// Messages de succès et d'erreur
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../assets/css/plugins.min.css" />
    <link rel="stylesheet" href="../assets/css/kaiadmin.min.css" />
    <link rel="stylesheet" href="../assets/css/demo.css" />
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
    <style>
        .range-slider {
            -webkit-appearance: none;
            width: 100%;
            height: 8px;
            border-radius: 5px;
            background: linear-gradient(to right, #4caf50 50%, #ddd 50%);
            outline: none;
            transition: background 0.3s;
        }

        .range-slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #4caf50;
            cursor: pointer;
        }

        .range-slider::-moz-range-thumb {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #4caf50;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="main-panel">
            <div class="container">
                <div class="page-inner">
                    <div class="page-header">
                        <h3 class="fw-bold mb-3">Dashboard</h3>
                        <p>Bienvenue <?= htmlspecialchars($admin['nom']) ?> !</p>
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
                                    <div class="d-flex align-items-center">
                                        <h4 class="card-title">Liste des projets</h4>
                                        <button class="btn btn-primary btn-round ms-auto" data-bs-toggle="modal" data-bs-target="#addRowModal">
                                            <i class="fa fa-plus"></i> Ajouter un projet
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="basic-datatables" class="display table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Nom Projet</th>
                                                    <th>Équipe</th>
                                                    <th>Email</th>
                                                    <th>Secteur</th>
                                                    <th>Avancement(%)</th>
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
                                                            <a href="edit-projet.php?id=<?= $p['id'] ?>" class="btn btn-icon btn-primary btn-sm" title="Modifier">
                                                                <i class="fa fa-edit"></i>
                                                            </a>
                                                            <button type="button" class="btn btn-icon btn-danger btn-sm" title="Supprimer" onclick="confirmDelete(<?= $p['id'] ?>, '<?= htmlspecialchars($p['nom_projet']) ?>')">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
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

                    <!-- Modal for Adding Row -->
                    <div class="modal fade" id="addRowModal" tabindex="-1" role="dialog" aria-labelledby="addRowModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addRowModalLabel">Ajouter un projet</h5>
                                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form method="post" action="dashboard.php" onsubmit="return confirm('Êtes-vous sûr de vouloir ajouter ce projet ?');">
                                    <div class="modal-body">
                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                        <input type="hidden" name="action" value="add">

                                        <div class="form-group">
                                            <label>Nom du projet :</label>
                                            <input name="nom_projet" class="form-control" placeholder="Nom du projet" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Nom de l'équipe :</label>
                                            <input name="nom_equipe" class="form-control" placeholder="Nom de l'équipe" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Email :</label>
                                            <input name="email" type="email" class="form-control" placeholder="Email" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Téléphone :</label>
                                            <input name="telephone" class="form-control" placeholder="Téléphone">
                                        </div>
                                        <div class="form-group">
                                            <label>Établissement :</label>
                                            <input name="etablissement" class="form-control" placeholder="Établissement">
                                        </div>
                                        <div class="form-group">
                                            <label>Thème :</label>
                                            <input name="theme" class="form-control" placeholder="Thème" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Avancement (%):</label>
                                            <input name="avancement_prototype" type="range" class="form-control range-slider" min="1" max="100" value="50" oninput="updateRangeColor(this)" required>
                                            <output>50</output>
                                        </div>
                                        <div class="form-group">
                                            <label>Lien vidéo :</label>
                                            <input name="play_video_url" class="form-control" placeholder="Lien vidéo">
                                        </div>
                                        <div class="form-group">
                                            <label>Détails :</label>
                                            <textarea name="detail" class="form-control" rows="5" placeholder="Détails"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label>Secteur :</label>
                                            <select name="secteur_id" class="form-control" required>
                                                <option value="">-- Secteur --</option>
                                                <?php
                                                    $secteurs = $pdo->query("SELECT id, nom FROM secteurs ORDER BY nom")->fetchAll(PDO::FETCH_ASSOC);
                                                    foreach ($secteurs as $secteur) {
                                                        echo '<option value="' . htmlspecialchars($secteur['id']) . '">' . htmlspecialchars($secteur['nom']) . '</option>';
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Ajouter</button>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Core JS Files -->
        <script src="../assets/js/core/jquery-3.7.1.min.js"></script>
        <script src="../assets/js/core/popper.min.js"></script>
        <script src="../assets/js/core/bootstrap.min.js"></script>
        <script src="../assets/js/plugin/datatables/datatables.min.js"></script>
        <script src="../assets/js/plugin/sweetalert/sweetalert.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#basic-datatables').DataTable();
            });

            function confirmDelete(id, name) {
                swal({
                    title: "Êtes-vous sûr ?",
                    text: `Voulez-vous vraiment supprimer le projet "${name}" ?`,
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = 'dashboard.php';

                        const csrfInput = document.createElement('input');
                        csrfInput.type = 'hidden';
                        csrfInput.name = 'csrf_token';
                        csrfInput.value = '<?= $_SESSION['csrf_token'] ?>';
                        form.appendChild(csrfInput);

                        const actionInput = document.createElement('input');
                        actionInput.type = 'hidden';
                        actionInput.name = 'action';
                        actionInput.value = 'delete';
                        form.appendChild(actionInput);

                        const idInput = document.createElement('input');
                        idInput.type = 'hidden';
                        idInput.name = 'id';
                        idInput.value = id;
                        form.appendChild(idInput);

                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            }

            function updateRangeColor(rangeInput) {
                const value = rangeInput.value;
                const percentage = (value - rangeInput.min) / (rangeInput.max - rangeInput.min) * 100;
                rangeInput.style.background = `linear-gradient(to right, #4caf50 ${percentage}%, #ddd ${percentage}%)`;
                rangeInput.nextElementSibling.value = value;
            }
        </script>
    </div>
</body>
</html>