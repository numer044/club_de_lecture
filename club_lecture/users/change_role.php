<?php
session_start();

require_once '../config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

if ($_SESSION['user_role'] !== 'admin') {
    header("Location: ../dashboard.php");
    exit();
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
$stmt = $pdo->prepare("UPDATE utilisateurs SET role = ? WHERE id = ?");
$user_id = $_POST['user_id'];
$role = $_POST['role'];
$stmt->execute([$role, $user_id]);

header("Location: liste.php");
exit();
}
?>