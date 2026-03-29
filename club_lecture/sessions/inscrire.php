<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

$session_id = (int) $_GET['session_id'];

if($_SERVER['REQUEST_METHOD'] === 'POST'){
$stmt = $pdo->prepare("INSERT INTO details_session (session_id, utilisateur_id)
values (?, ?)");
$stmt->execute([$session_id, $_SESSION['user_id']]);

header("Location: detail.php?id=" . $session_id);
exit();
}
?>