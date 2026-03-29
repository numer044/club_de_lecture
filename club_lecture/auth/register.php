<?php
require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];

    if (empty($nom) || empty($email) || empty($mot_de_passe)) {
        $erreur = "Veuillez remplir tous les champs obligatoires";
    }

    $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ?");
    $stmt->execute([$email]);
    $existant = $stmt->fetch();
    if ($existant) {
        $erreur = "L'email est déjà utilisé";
    }

    if (!isset($erreur)) {
        $hash = password_hash($mot_de_passe, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, email, mot_de_passe) VALUES (?, ?, ?)");
        $stmt->execute([$nom, $email, $hash]);
        header("Location: login.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Club de Lecture — Inscription</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h1>📚 Rejoindre le club</h1>

            <?php if (isset($erreur)): ?>
                <p class="erreur"><?php echo $erreur; ?></p>
            <?php endif; ?>

            <form action="register.php" method="post">
                <label for="nom">Nom</label>
                <input type="text" id="nom" name="nom" required>

                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>

                <label for="mot_de_passe">Mot de passe</label>
                <input type="password" id="mot_de_passe" name="mot_de_passe" required>

                <button type="submit">S'inscrire</button>
            </form>

            <div class="register-link">
                <a href="login.php">Déjà membre ? Connectez-vous !</a>
            </div>
        </div>
    </div>
</body>
</html>