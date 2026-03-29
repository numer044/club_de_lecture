<?php
session_start();
require_once '../config.php';
require_once '../includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

$id = (int) $_GET['id'];

if ($id === 0) {
    header("Location: liste.php");
    exit();
}

$stmt = $pdo->prepare("SELECT sessions_lecture.*, livres.titre as titre_livre
FROM sessions_lecture
INNER JOIN livres ON sessions_lecture.livre_id = livres.id
WHERE sessions_lecture.id = ?");
$stmt->execute([$id]);
$session = $stmt->fetch();

if (!$session) {
    header("Location: liste.php");
    exit();
}

if ($_SESSION['user_role'] === 'admin' || $_SESSION['user_role'] === 'moderateur') {
    $stmt = $pdo->prepare("SELECT utilisateurs.nom
FROM details_session
INNER JOIN utilisateurs ON details_session.utilisateur_id = utilisateurs.id
WHERE details_session.session_id = ?");
    $stmt->execute([$id]);
    $inscrits = $stmt->fetchAll();
}


?>

<div class="detail">
    <p><?php echo $session['titre']; ?></p>
    <p><?php echo $session['titre_livre']; ?></p>
    <p><?php echo $session['statut']; ?></p>
    <p><?php echo $session['date_debut']; ?></p>
</div>

<div>
    <?php if ($_SESSION['user_role'] === 'admin' || $_SESSION['user_role'] === 'moderateur'): ?>
        <ul>
            <?php foreach ($inscrits as $inscrit): ?>
                <li><?php echo $inscrit['nom']; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>

<div>
    <form action="inscrire.php?session_id=<?php echo $id; ?>" method="post">
        <button type="submit">S'inscrire</button>
    </form>
</div>
<?php
require_once '../includes/footer.php';
?>