<?php
namespace Projet;

require_once __DIR__ . '/../../config/database.php';

class ProjetService {
    public static function getAll() {
        global $pdo;
        try {
            $stmt = $pdo->query("SELECT p.*, s.nom AS secteur_nom FROM projets p 
                                LEFT JOIN secteurs s ON p.secteur_id = s.id 
                                ORDER BY p.id DESC");
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            // Log de l'erreur
            error_log("Erreur lors de la récupération des projets: " . $e->getMessage());
            return [];
        }
    }

    public static function add($data) {
        global $pdo;
        try {
            $stmt = $pdo->prepare("INSERT INTO projets 
                (nom_equipe, nom_projet, etablissement, email, telephone, secteur_id, theme, avancement_prototype, play_video_url, detail)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

            return $stmt->execute([
                $data['nom_equipe'],
                $data['nom_projet'],
                $data['etablissement'] ?? null,
                $data['email'],
                $data['telephone'] ?? null,
                $data['secteur_id'],
                $data['theme'],
                $data['avancement_prototype'],
                $data['play_video_url'] ?? null,
                $data['detail'] ?? null
            ]);
        } catch (\PDOException $e) {
            // Log de l'erreur
            error_log("Erreur lors de l'ajout du projet: " . $e->getMessage());
            return false;
        }
    }
    
    public static function getById($id) {
        global $pdo;
        try {
            $stmt = $pdo->prepare("SELECT p.*, s.nom AS secteur_nom FROM projets p 
                                  LEFT JOIN secteurs s ON p.secteur_id = s.id 
                                  WHERE p.id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Erreur lors de la récupération du projet: " . $e->getMessage());
            return false;
        }
    }
    public static function update($id, $data) {
        global $pdo;
        try {
            $stmt = $pdo->prepare("UPDATE projets SET 
                nom_equipe = ?, 
                nom_projet = ?, 
                etablissement = ?, 
                email = ?, 
                telephone = ?, 
                secteur_id = ?, 
                theme = ?, 
                avancement_prototype = ?, 
                play_video_url = ?, 
                detail = ?
                WHERE id = ?");

            return $stmt->execute([
                $data['nom_equipe'],
                $data['nom_projet'],
                $data['etablissement'] ?? null,
                $data['email'],
                $data['telephone'] ?? null,
                $data['secteur_id'],
                $data['theme'],
                $data['avancement_prototype'],
                $data['play_video_url'] ?? null,
                $data['detail'] ?? null,
                $id
            ]);
        } catch (\PDOException $e) {
            error_log("Erreur lors de la mise à jour du projet: " . $e->getMessage());
            return false;
        }
    }
    
    public static function delete($id) {
        global $pdo;
        try {
            $stmt = $pdo->prepare("DELETE FROM projets WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (\PDOException $e) {
            error_log("Erreur lors de la suppression du projet: " . $e->getMessage());
            return false;
        }
    }

}