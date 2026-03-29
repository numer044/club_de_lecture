<?php
session_start();
require_once '../config.php';
require_once '../includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$stmt = $pdo->prepare("SELECT livres.id, livres.titre, livres.couverture_url, auteurs.nom, livres.date_publication
FROM livres
INNER JOIN auteurs ON livres.auteur_id = auteurs.id");
$stmt->execute();
$livres = $stmt->fetchAll();
?>

<main>
    <div class="liste-container">
        <?php foreach ($livres as $livre): ?>
            <a href="detail.php?id=<?php echo $livre['id']; ?>" class="liste_livre">
                <img src="/club_lecture/uploads/covers/<?php echo $livre['couverture_url']; ?>" alt="<?php echo $livre['titre']; ?>">
                <div class="liste_livre-info">
                    <h3><?php echo $livre['titre']; ?></h3>
                    <p class="auteur"><?php echo $livre['nom']; ?></p>
                    <p class="date"><?php echo $livre['date_publication']; ?></p>
                    <span class="voir-plus">Voir plus →</span>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>