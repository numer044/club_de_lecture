<?php

session_start();
require_once '../config.php';
require_once '../includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

$livre_id = (int) $_GET['livre_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $note = $_POST['note'];
    $titre = $_POST['titre'];
    $contenu = $_POST['contenu'];
    $spoiler = isset($_POST['spoiler']) ? 1 : 0;

    $stmt = $pdo->prepare("INSERT INTO avis (utilisateur_id, livre_id, note, titre, contenu, spoiler)
    VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $livre_id, $note, $titre, $contenu, $spoiler]);
    header('Location: ../livres/detail.php?id=' .$livre_id);
    exit();
}


?>

<form action="ajouter.php" method="post">
    <section class="ajout_avis">
        <div class="avis_titre">
            <label for="">Titre</label>
            <input type="text" name="titre" required>

            <label for="livre">Livre</label>

            <label for="note">Note</label>
            <input type="number" name="note" min="1" max="5" required>
        </div>
        <div class="avis_content">
            <label for="">Contenu</label>
            <input type="text" name="contenu">

            <label for="spoiler">Spoiler</label>
            <input type="checkbox" name="spoiler" value="1">
        </div>

        <button type="submit">Publier l'avis</button>
    </section>

</form>