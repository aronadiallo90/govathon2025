<?php
require_once __DIR__ . '/../src/Projet/ProjetService.php';
use Projet\ProjetService;

$projets = ProjetService::getAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST;
    ProjetService::add($data);
    header('Location: dashboard.php');
    exit;
}
