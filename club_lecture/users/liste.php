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

$stmt = $pdo->prepare("SELECT id, nom, email, role, date_inscription FROM utilisateurs");
$stmt->execute();
$utilisateurs = $stmt->fetchAll();
?>

<main>
    <div class="form-container">
        <h2>Gestion des membres</h2>

        <div class="membres-liste">
            <?php foreach ($utilisateurs as $utilisateur): ?>
                <div class="membre-card">
                    <div class="membre-info">
                        <h3><?php echo ucfirst($utilisateur['nom']); ?></h3>
                        <p class="membre-email">📧 <?php echo $utilisateur['email']; ?></p>
                        <p class="membre-date">Inscrit le : <?php echo $utilisateur['date_inscription']; ?></p>
                    </div>

                    <div class="membre-role">
                        <span class="role-badge <?php echo $utilisateur['role']; ?>">
                            <?php echo ucfirst($utilisateur['role']); ?>
                        </span>
                        <form action="changer_role.php" method="post" style="display:flex; align-items:center; gap:10px;">
                            <input type="hidden" name="user_id" value="<?php echo $utilisateur['id']; ?>">
                            <select name="role">
                                <option value="membre" <?php echo $utilisateur['role'] === 'membre' ? 'selected' : ''; ?>>Membre</option>
                                <option value="moderateur" <?php echo $utilisateur['role'] === 'moderateur' ? 'selected' : ''; ?>>Modérateur</option>
                                <option value="admin" <?php echo $utilisateur['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                            </select>
                            <button type="submit" class="btn-small">Changer</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>