<?php
session_start();
require_once '../config.php';
require_once '../includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

if ($_SESSION['user_role'] !== 'admin' && $_SESSION['user_role'] !== 'moderateur') {
    header('Location: ../dashboard.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'];
    $date = $_POST['date'];
    $heure = $_POST['heure'];
    $type = $_POST['type'];
    $livre_id = $_POST['livre_id'];
    $statut = 'Planifiée';
    $date_debut = $date . ' ' . $heure;

    $stmt = $pdo->prepare("INSERT INTO sessions_lecture (titre, livre_id, moderateur_id, type, date_debut, statut)
    VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$titre, $livre_id, $_SESSION['user_id'], $type, $date_debut, $statut]);
    header('Location: liste.php');
    exit();
}

$stmt = $pdo->query("SELECT id, titre FROM livres");
$livres = $stmt->fetchAll();
?>

<main>
    <div class="form-container">
        <h2>Créer une session</h2>

        <form action="creer.php" method="POST" class="form-card">

            <label for="titre">Titre</label>
            <input type="text" name="titre" id="titre" required>

            <label for="date">Date</label>
            <input type="date" name="date" id="date" required>

            <label for="heure">Heure</label>
            <input type="time" name="heure" id="heure" required>

            <label for="livre_id">Livre</label>
            <select name="livre_id" id="livre_id" required>
                <?php foreach ($livres as $livre): ?>
                    <option value="<?php echo $livre['id']; ?>"><?php echo $livre['titre']; ?></option>
                <?php endforeach; ?>
            </select>

            <label for="type">Type de session</label>
            <select name="type" id="type" required>
                <option value="discussion">Discussion</option>
                <option value="lecture">Lecture de groupe</option>
            </select>

            <div class="form-actions">
                <button type="submit" class="btn">Créer la session</button>
                <a href="liste.php" class="btn-retour">← Annuler</a>
            </div>

        </form>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>