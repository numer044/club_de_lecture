<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

$avis_id = (int) $_GET['avis_id'];

$stmt = $pdo->prepare("SELECT utilisateur_id FROM avis WHERE id = ?");
$stmt->execute([$avis_id]);
$avis = $stmt->fetch();

if (!$avis || $avis['utilisateur_id'] !== $_SESSION['user_id']) {
    header('Location: ../livres/liste.php');
    exit();
}


$stmt = $pdo->prepare("DELETE FROM avis WHERE id = ?");
$stmt->execute([$avis_id]);

header('Location: ../livres/liste.php');
exit();
?>