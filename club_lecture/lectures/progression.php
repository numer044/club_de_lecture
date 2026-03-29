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
    $pourcentage = $_POST['pourcentage'];

    $stmt = $pdo->prepare("INSERT INTO lectures (utilisateur_id, livre_id, pourcentage)
VALUES (?, ?, ?)
ON DUPLICATE KEY UPDATE pourcentage = VALUES(pourcentage)");
    $stmt->execute([$_SESSION['user_id'], $livre_id, $pourcentage]);
    header('Location: ../livres/detail.php?id=' . $livre_id);
    exit();
}
?>

<main>
    <div class="progression-container">
        <h2>Ma progression</h2>
        <form action="progression.php?livre_id=<?php echo $livre_id; ?>" method="post" class="progression-form">
            <label for="pourcentage">Progression (%)</label>
            <div class="progression-input-group">
                <input type="number" name="pourcentage" id="pourcentage" min="0" max="100" required>
                <span>%</span>
            </div>
            <div class="progress-bar">
                <div class="progress-fill" id="preview" style="width: 0%"></div>
            </div>
            <button type="submit" class="btn">Enregistrer</button>
            <a href="../livres/detail.php?id=<?php echo $livre_id; ?>" class="btn-retour">← Retour au livre</a>
        </form>
    </div>
</main>

<script>
    document.getElementById('pourcentage').addEventListener('input', function() {
        document.getElementById('preview').style.width = this.value + '%';
    });
</script>

<?php require_once '../includes/footer.php'; ?>