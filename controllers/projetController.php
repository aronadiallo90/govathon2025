<?php
// controllers/projetController.php
require_once __DIR__ . '/../src/Projet/ProjetService.php';
use Projet\ProjetService;

class ProjetController {
    public function getAllProjets() {
        return ProjetService::getAll();
    }
    
    public function getProjetById($id) {
        return ProjetService::getById($id);
    }
    
    public function addProjet($data) {
        // Validation des données
        $errors = $this->validateProjetData($data);
        
        if (empty($errors)) {
            // Nettoyage des données avant insertion
            $cleanData = $this->sanitizeData($data);
            
            if (ProjetService::add($cleanData)) {
                return ['success' => true];
            } else {
                return ['success' => false, 'errors' => ['Erreur lors de l\'ajout du projet']];
            }
        }
        
        return ['success' => false, 'errors' => $errors];
    }
    
    public function updateProjet($id, $data) {
        // Validation des données
        $errors = $this->validateProjetData($data);
        
        if (empty($errors)) {
            // Nettoyage des données avant mise à jour
            $cleanData = $this->sanitizeData($data);
            
            if (ProjetService::update($id, $cleanData)) {
                return ['success' => true];
            } else {
                return ['success' => false, 'errors' => ['Erreur lors de la mise à jour du projet']];
            }
        }
        
        return ['success' => false, 'errors' => $errors];
    }
    
    public function deleteProjet($id) {
        if (ProjetService::delete($id)) {
            return ['success' => true];
        } else {
            return ['success' => false, 'errors' => ['Erreur lors de la suppression du projet']];
        }
    }
    
    private function validateProjetData($data) {
        $errors = [];
        
        if (empty($data['nom_projet'])) $errors[] = "Le nom du projet est requis";
        if (empty($data['nom_equipe'])) $errors[] = "Le nom de l'équipe est requis";
        
        if (empty($data['email'])) {
            $errors[] = "L'email est requis";
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Format d'email invalide";
        }
        
        if (empty($data['secteur_id'])) $errors[] = "Le secteur est requis";
        if (empty($data['theme'])) $errors[] = "Le thème est requis";
        if (empty($data['avancement_prototype'])) $errors[] = "L'avancement du prototype est requis";
        
        return $errors;
    }
    
    private function sanitizeData($data) {
        $clean = [];
        foreach ($data as $key => $value) {
            $clean[$key] = is_string($value) ? htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8') : $value;
        }
        return $clean;
    }
}

// Initialisation du contrôleur
$projetController = new ProjetController();
$projets = $projetController->getAllProjets();

// Gestion des actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérification CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('CSRF token validation failed');
    }
    
    // Déterminer quelle action effectuer
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                // Ajout d'un projet
                $result = $projetController->addProjet($_POST);
                
                if ($result['success']) {
                    header('Location: dashboard.php?success=add');
                    exit;
                } else {
                    $errors = $result['errors'];
                }
                break;
                
            case 'update':
                // Mise à jour d'un projet
                if (isset($_POST['id']) && is_numeric($_POST['id'])) {
                    $result = $projetController->updateProjet($_POST['id'], $_POST);
                    
                    if ($result['success']) {
                        header('Location: dashboard.php?success=update');
                        exit;
                    } else {
                        $errors = $result['errors'];
                        // Si erreur, on récupère les données du projet pour pré-remplir le formulaire
                        $projet = $projetController->getProjetById($_POST['id']);
                    }
                }
                break;
                
            case 'delete':
                // Suppression d'un projet
                if (isset($_POST['id']) && is_numeric($_POST['id'])) {
                    $result = $projetController->deleteProjet($_POST['id']);
                    
                    if ($result['success']) {
                        header('Location: dashboard.php?success=delete');
                        exit;
                    } else {
                        $errors = $result['errors'];
                    }
                }
                break;
        }
    } else {
        // Si aucune action n'est spécifiée, on considère que c'est un ajout (compatibilité rétroactive)
        $result = $projetController->addProjet($_POST);
        
        if ($result['success']) {
            header('Location: dashboard.php?success=add');
            exit;
        } else {
            $errors = $result['errors'];
        }
    }
}

// Récupération d'un projet pour édition si demandé par l'URL
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $projet = $projetController->getProjetById($_GET['edit']);
    if (!$projet) {
        header('Location: dashboard.php?error=notfound');
        exit;
    }
}