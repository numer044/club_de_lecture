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

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
$nom = $_POST['nom'];
$date_naissance = $_POST['date_naissance'];
$nationalite = $_POST['nationalite'];
$photo = $_FILES['photo'];

$dossier = '../uploads/photos/';
$nom_fichier = uniqid() . '_' . $photo['name'];

move_uploaded_file($photo['tmp_name'], $dossier . $nom_fichier);

$chemin_photo = $dossier . $nom_fichier;

$stmt = $pdo->prepare("INSERT INTO auteurs (nom, photo_url, date_naissance, nationalite) values (?, ?, ?, ?) ");
$stmt->execute([$nom, $chemin_photo, $date_naissance, $nationalite]);
header('Location: ../livres/liste.php');
exit();
}





?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form action="ajouter.php" method="post" enctype="multipart/form-data">
        <label for="auteur">Auteur</label>
        <input type="text" name="nom" required>

        <label for="photo_url">Photo</label>
        <input type="file" name="photo" accept="image/jpeg, image/png, image/webp">

        <label for="date_naissance">Date de naisssance</label>
        <input type="date" name="date_naissance" required>

        <label for="nationalite">Nationalité</label>
        <input type="text" name="nationalite" required>

        <button type="submit">Ajouter l'auteur</button>

    </form>
</body>

</html>