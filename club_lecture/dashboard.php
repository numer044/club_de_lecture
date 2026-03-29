<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

require_once 'includes/header.php';

$stmt = $pdo->prepare("SELECT livres.titre, livres.id as livre_id, lectures.pourcentage, lectures.page_actuelle 
FROM lectures 
JOIN livres ON lectures.livre_id = livres.id
WHERE lectures.utilisateur_id = ? 
AND lectures.statut = 'En cours'");
$stmt->execute([$_SESSION['user_id']]);
$lectures = $stmt->fetchAll();
?>

<main>
    <div class="dashboard">

        <h2 id="bienvenue">Bienvenue <?php echo ucfirst($_SESSION['user_nom']); ?> ! 👋</h2>
        <script>
            setTimeout(() => {
                document.getElementById('bienvenue').style.display = 'none';
            }, 3000);
        </script>

        <div class="dashboard-links">
            <a href="livres/liste.php">📚 Voir les livres</a>
            <a href="sessions/liste.php">📅 Voir les sessions</a>
            <?php if ($_SESSION['user_role'] === 'admin'): ?>
                <a href="users/liste.php">👥 Gérer les membres</a>
                <a href="livres/ajouter.php">➕ Ajouter un livre</a>
                <a href="auteurs/ajouter.php">✍️ Ajouter un auteur</a>
            <?php endif; ?>
            <?php if ($_SESSION['user_role'] === 'admin' || $_SESSION['user_role'] === 'moderateur'): ?>
                <a href="sessions/creer.php">📝 Créer une session</a>
            <?php endif; ?>
        </div>

        <h3 class="section-titre">Mes lectures en cours</h3>

        <?php if (empty($lectures)): ?>
            <p class="vide">Aucune lecture en cours.</p>
        <?php else: ?>
            <div class="lectures-grid">
                <?php foreach ($lectures as $lecture): ?>
                    <div class="lecture-card">
                        <h4><?php echo $lecture['titre']; ?></h4>
                        <p>Page : <?php echo $lecture['page_actuelle']; ?></p>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: <?php echo $lecture['pourcentage']; ?>%"></div>
                        </div>
                        <p><?php echo $lecture['pourcentage']; ?>%</p>
                        <a href="livres/detail.php?id=<?php echo $lecture['livre_id']; ?>">Voir le livre</a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</main>

<?php require_once 'includes/footer.php'; ?>