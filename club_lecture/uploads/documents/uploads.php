<?php
session_start();
require_once '../config.php';
require_once '../includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

if ($_SESSION['user_role'] !== 'admin') {
    header("Location: ../dashboard.php");
    exit();
}

$stmt_livres = $pdo->prepare("SELECT id, titre FROM livres");
$stmt_livres->execute();
$livres = $stmt_livres->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $livre_id = (int) $_POST['livre_id'];
    $document = $_FILES['document'];

    $nom_fichier = uniqid() . '_' . $document['name'];
    $chemin = '../uploads/documents/' . $nom_fichier;
    $type_mime = $document['type'];
    $taille = $document['size'];

    if ($type_mime !== 'application/pdf') {
        $erreur = "Seuls les fichiers PDF sont acceptés";
    } else {
        move_uploaded_file($document['tmp_name'], $chemin);

        $stmt = $pdo->prepare("INSERT INTO documents (livre_id, nom_fichier, chemin, type_mime, taille, uploade_par)
        VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$livre_id, $nom_fichier, $chemin, $type_mime, $taille, $_SESSION['user_id']]);

        header('Location: ../livres/detail.php?id=' . $livre_id);
        exit();
    }
}
?>

<form action="upload.php" method="post" enctype="multipart/form-data">
    <label for="livre_id">Livre</label>
    <select name="livre_id" required>
        <?php foreach ($livres as $livre): ?>
            <option value="<?php echo $livre['id']; ?>"><?php echo $livre['titre']; ?></option>
        <?php endforeach; ?>
    </select>

    <label for="document">Document PDF</label>
    <input type="file" name="document" accept="application/pdf" required>

    <?php if (isset($erreur)): ?>
        <p style="color:red;"><?php echo $erreur; ?></p>
    <?php endif; ?>

    <button type="submit">Uploader le document</button>
</form>

<?php require_once '../includes/footer.php'; ?>