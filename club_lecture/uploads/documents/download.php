<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$document_id = (int) $_GET['id'];

if ($document_id === 0) {
    header("Location: ../dashboard.php");
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM documents WHERE id = ?");
$stmt->execute([$document_id]);
$document = $stmt->fetch();

if (!$document) {
    header("Location: ../dashboard.php");
    exit();
}

$chemin = $document['chemin'];

if (!file_exists($chemin)) {
    die("Fichier introuvable");
}

header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="' . $document['nom_fichier'] . '"');
header('Content-Length: ' . filesize($chemin));

readfile($chemin);
exit();
?>