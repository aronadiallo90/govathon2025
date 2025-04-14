<?php
namespace Projet;

require_once __DIR__ . '/../../config/database.php';

class ProjetService {
    public static function getAll() {
        global $pdo;
        $stmt = $pdo->query("SELECT p.*, s.nom AS secteur_nom FROM projets p 
                             LEFT JOIN secteurs s ON p.secteur_id = s.id");
        return $stmt->fetchAll();
    }

    public static function add($data) {
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO projets 
            (nom_equipe, nom_projet, etablissement, email, telephone, secteur_id, theme, avancement_prototype, play_video_url, detail)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        return $stmt->execute([
            $data['nom_equipe'],
            $data['nom_projet'],
            $data['etablissement'] ?? null,
            $data['email'],
            $data['telephone'],
            $data['secteur_id'],
            $data['theme'],
            $data['avancement_prototype'],
            $data['play_video_url'],
            $data['detail']
        ]);
    }
}
