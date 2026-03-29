<?php
session_start();
require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];

    if (empty($email) || empty($mot_de_passe)) {
        $erreur = "Veuillez remplir tous les champs";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
        $stmt->execute([$email]);
        $utilisateur = $stmt->fetch();

        if ($utilisateur && password_verify($mot_de_passe, $utilisateur['mot_de_passe'])) {
            $_SESSION['user_id'] = $utilisateur['id'];
            $_SESSION['user_nom'] = $utilisateur['nom'];
            $_SESSION['user_role'] = $utilisateur['role'];
            header("Location: ../dashboard.php");
            exit();
        } else {
            $erreur = "Email ou mot de passe incorrect.";
        }
    }
}
?>
    <?php
    if (file_exists('../assets/style.css')) {
        $bg = "body::before { background-image: url('/club_lecture/assets/ordi-livre.jpg'); }";
    }
    ?>


    <div class="login-container">
        <div class="login-box">
            <h1>Club de Lecture</h1>

            <?php if (isset($erreur)): ?>
                <p class="erreur"><?php echo $erreur; ?></p>
            <?php endif; ?>

            <form action="login.php" method="post">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>

                <label for="mot_de_passe">Mot de passe</label>
                <input type="password" id="mot_de_passe" name="mot_de_passe" required>

                <button type="submit">Se connecter</button>
            </form>

            <div class="register-link">
                <a href="register.php">Pas encore membre ? Rejoignez-nous !</a>
            </div>
        </div>
    </div>
<?php require_once '../includes/footer.php' ?>