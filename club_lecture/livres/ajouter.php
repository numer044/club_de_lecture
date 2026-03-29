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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'];
    $auteur_id = $_POST['auteur_id'];
    $genre = $_POST['genre'];
    $description = $_POST['description'];
    $nombre_pages = $_POST['nombre_pages'];
    $couverture_url = $_FILES['couverture_url'];
    $date_publication = $_POST['date_publication'];

    $dossier = '../uploads/couvertures/';
    $nom_fichier = uniqid() . '_' . $couverture_url['name'];

    move_uploaded_file($couverture_url['tmp_name'], $dossier . $nom_fichier);

    $chemin_couverture = $dossier . $nom_fichier;

    $stmt = $pdo->prepare("INSERT INTO livres (titre, auteur_id, genre, nombre_pages, description, couverture_url, date_publication) values (?, ?, ?, ?, ?, ?, ?) ");
    $stmt->execute([$titre, $auteur_id, $genre, $nombre_pages, $description, $chemin_couverture, $date_publication]);
    header('Location: ../livres/liste.php');
    exit();
}

$stmt_auteurs = $pdo->prepare("SELECT id, nom FROM auteurs");
$stmt_auteurs->execute();
$auteurs = $stmt_auteurs->fetchAll();



require_once '../includes/header.php' ?>

<form action="ajouter.php" method="post" enctype="multipart/form-data">
    <label for="titre">Titre</label>
    <input type="text" name="titre" required>

    <label for="auteur_id">Auteur</label>
    <select name="auteur_id" required>
        <?php foreach ($auteurs as $auteur): ?>
            <option value="<?php echo $auteur['id']; ?>"><?php echo $auteur['nom']; ?></option>
        <?php endforeach; ?>
    </select>
    <label for="genre">Genre</label>
    <input type="text" name="genre" required>

    <label for="description">Description</label>
    <input type="text" name="description" required>

    <label for="nombre_pages">Nombre pages</label>
    <input type="text" name="nombre_pages" required>

    <label for="date_publication">Date de publication</label>
    <input type="date" name="date_publication" required>

    <label for="couverture_url">Couverture</label>
    <input type="file" name="couverture_url" accept="image/jpeg, image/png, image/webp">

    <button type="submit">Ajouter l'auteur</button>


    <?php require_once '../includes/footer.php' ?>