<?php
session_start();
require_once '../config.php';
require_once '../includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// infos de l'utilisateur connecté
$stmt = $pdo->prepare("SELECT id, nom, email, role, date_inscription FROM utilisateurs WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$profil = $stmt->fetch();

// ses lectures en cours
$stmt = $pdo->prepare("SELECT livres.id, livres.titre, lectures.pourcentage, lectures.statut
FROM lectures
INNER JOIN livres ON lectures.livre_id = livres.id
WHERE lectures.utilisateur_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$lectures = $stmt->fetchAll();

// ses avis
$stmt = $pdo->prepare("SELECT avis.id, avis.note, avis.contenu, livres.titre as titre_livre
FROM avis
INNER JOIN livres ON avis.livre_id = livres.id
WHERE avis.utilisateur_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$avis = $stmt->fetchAll();
?>

<main>
    <div class="profil-container">

        <div class="profil-card">
            <h2><?php echo ucfirst($profil['nom']); ?></h2>
            <p class="profil-email">📧 <?php echo $profil['email']; ?></p>
            <p class="profil-role">
                <span class="role-badge <?php echo $profil['role']; ?>">
                    <?php echo ucfirst($profil['role']); ?>
                </span>
            </p>
            <p class="profil-date">Membre depuis le <?php echo $profil['date_inscription']; ?></p>
        </div>

        <div class="profil-section">
            <h3>Mes lectures</h3>
            <?php if (empty($lectures)): ?>
                <p class="vide">Aucune lecture enregistrée.</p>
            <?php else: ?>
                <div class="lectures-grid">
                    <?php foreach ($lectures as $lecture): ?>
                        <a href="../livres/detail.php?id=<?php echo $lecture['id']; ?>" class="lecture-card">
                            <h4><?php echo $lecture['titre']; ?></h4>
                            <p><?php echo ucfirst($lecture['statut']); ?></p>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: <?php echo $lecture['pourcentage']; ?>%"></div>
                            </div>
                            <p><?php echo $lecture['pourcentage']; ?>%</p>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="profil-section">
            <h3>Mes avis</h3>
            <?php if (empty($avis)): ?>
                <p class="vide">Aucun avis publié.</p>
            <?php else: ?>
                <?php foreach ($avis as $un_avis): ?>
                    <div class="avis">
                        <div class="avis-header">
                            <span class="avis-livre"><?php echo $un_avis['titre_livre']; ?></span>
                            <span class="avis-note"><?php echo $un_avis['note']; ?>/5 ⭐</span>
                        </div>
                        <p class="avis-contenu"><?php echo $un_avis['contenu']; ?></p>
                        <div class="avis-actions">
                            <a href="../avis/modifier.php?avis_id=<?php echo $un_avis['id']; ?>">Modifier</a>
                            <a href="../avis/supprimer.php?avis_id=<?php echo $un_avis['id']; ?>">Supprimer</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </div>
</main>

<?php require_once '../includes/footer.php'; ?>