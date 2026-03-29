<?php
session_start();
require_once '../config.php';
require_once '../includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

$avis_id = (int) $_GET['avis_id'];

$stmt = $pdo->prepare("SELECT * FROM avis WHERE id = ?");
$stmt->execute([$avis_id]);
$avis = $stmt->fetch();

if (!$avis || $avis['utilisateur_id'] !== $_SESSION['user_id']) {
    header('Location: ../livres/liste.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $note = $_POST['note'];
    $titre = $_POST['titre'];
    $contenu = $_POST['contenu'];
    $spoiler = isset($_POST['spoiler']) ? 1 : 0;

    $stmt = $pdo->prepare("UPDATE avis SET note = ?, titre = ?, contenu = ?, spoiler = ? WHERE id = ?");
    $stmt->execute([$note, $titre, $contenu, $spoiler, $avis_id]);

    header('Location: ../livres/liste.php');
    exit();
}
?>

<form action="modifier.php?avis_id=<?php echo $avis_id; ?>" method="post">
    <label for="note">Note</label>
    <input type="number" name="note" min="1" max="5" required value="<?php echo $avis['note']; ?>">

    <label for="titre">Titre</label>
    <input type="text" name="titre" value="<?php echo $avis['titre']; ?>">

    <label for="contenu">Contenu</label>
    <input type="text" name="contenu" value="<?php echo $avis['contenu']; ?>">

    <label for="spoiler">Spoiler</label>
    <input type="checkbox" name="spoiler" value="1" <?php echo $avis['spoiler'] ? 'checked' : ''; ?>>

    <button type="submit">Modifier l'avis</button>
</form>

<?php require_once '../includes/footer.php'; ?>