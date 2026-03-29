<?php

session_start();
require_once '../config.php';
require_once '../includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

$stmt = $pdo->prepare("SELECT sessions_lecture.id, sessions_lecture.titre, sessions_lecture.type, 
sessions_lecture.statut, sessions_lecture.date_debut, livres.titre as titre_livre
FROM sessions_lecture
INNER JOIN livres ON sessions_lecture.livre_id = livres.id");
$stmt->execute();
$sessions_lecture = $stmt->fetchAll();
?>

<main>
    <div class="liste-container">
        <h2>Sessions de lecture</h2>

        <?php if (empty($sessions_lecture)): ?>
            <p class="vide">Aucune session disponible pour le moment.</p>
        <?php else: ?>
            <?php foreach ($sessions_lecture as $session): ?>
                <a href="detail.php?id=<?php echo $session['id']; ?>" class="liste_session">
                    <div class="liste_session-info">
                        <h3><?php echo $session['titre']; ?></h3>
                        <p class="liste_session-livre"><?php echo $session['titre_livre']; ?></p>
                        <p class="liste_session-meta"><?php echo $session['date_debut']; ?></p>
                        <p class="liste_session-meta">Type : <?php echo ucfirst($session['type']); ?></p>
                        <span class="liste_session-statut <?php echo strtolower($session['statut']); ?>">
                            <?php echo $session['statut']; ?>
                        </span>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>