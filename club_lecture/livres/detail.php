<?php
session_start();
require_once '../config.php';
require_once '../includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$id = (int) $_GET['id'];

if ($id === 0) {
    header("Location: liste.php");
    exit();
}

$stmt = $pdo->prepare("SELECT livres.id, livres.titre, auteurs.nom, livres.description,
livres.couverture_url, livres.genre, livres.date_publication
FROM livres
INNER JOIN auteurs ON livres.auteur_id = auteurs.id
WHERE livres.id = ?");
$stmt->execute([$id]);
$livre = $stmt->fetch();

if (!$livre) {
    header("Location: liste.php");
    exit();
}

$stmt = $pdo->prepare("SELECT AVG(pourcentage) as moyenne FROM lectures WHERE livre_id = ?");
$stmt->execute([$id]);
$lectures = $stmt->fetch();

$stmt = $pdo->prepare("SELECT avis.id, avis.titre, avis.note, avis.contenu, avis.spoiler, avis.utilisateur_id, utilisateurs.nom as nom_utilisateur
FROM avis
INNER JOIN utilisateurs ON avis.utilisateur_id = utilisateurs.id
WHERE avis.livre_id = ?");
$stmt->execute([$id]);
$avis = $stmt->fetchAll();
?>

<main>
    <div class="detail_livre">
        <div class="detail_livre-info">
            <h2><?php echo $livre['titre']; ?></h2>
            <p class="detail_auteur"><?php echo $livre['nom']; ?></p>
            <p class="detail_desc"><?php echo $livre['description']; ?></p>
            <p class="detail_meta">Genre : <?php echo $livre['genre']; ?></p>
            <p class="detail_meta">Date : <?php echo $livre['date_publication']; ?></p>
            <p class="detail_meta">Progression moyenne : <?php echo round($lectures['moyenne'], 1); ?>%</p>
            <div class="detail_actions">
                <a href="../avis/ajouter.php?livre_id=<?php echo $id; ?>" class="btn">Laisser un avis</a>
                <a href="../lectures/progression.php?livre_id=<?php echo $id; ?>" class="btn">Mettre à jour ma progression</a>
            </div>
        </div>
        <img src="/club_lecture/uploads/covers/<?php echo $livre['couverture_url']; ?>" alt="<?php echo $livre['titre']; ?>" class="detail_couverture">
    </div>

    <div class="avis-container">
        <h3>Avis des membres</h3>
        <?php if (empty($avis)): ?>
            <p class="vide">Aucun avis pour ce livre.</p>
        <?php else: ?>
            <?php foreach ($avis as $un_avis): ?>
                <div class="avis">
                    <div class="avis-header">
                        <span class="avis-auteur"><?php echo $un_avis['nom_utilisateur']; ?></span>
                        <span class="avis-note"><?php echo $un_avis['note']; ?>/5 ⭐</span>
                        <?php if ($un_avis['spoiler']): ?>
                            <span class="avis-spoiler">⚠️ Spoiler</span>
                        <?php endif; ?>
                    </div>
                    <p class="avis-contenu"><?php echo $un_avis['contenu']; ?></p>
                    <?php if ($un_avis['utilisateur_id'] === $_SESSION['user_id']): ?>
                        <div class="avis-actions">
                            <a href="../avis/modifier.php?avis_id=<?php echo $un_avis['id']; ?>">Modifier</a>
                            <a href="../avis/supprimer.php?avis_id=<?php echo $un_avis['id']; ?>">Supprimer</a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>