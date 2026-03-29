<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="assets/style.css">

</head>

<body>
    <section class="header">
    <nav class="navbar">
        <h1 id="user_nom"><?php echo $_SESSION['user_nom']; ?></h1>
        <ul>
            <li><a href="/club_lecture/livres/index.php">Livres</a></li>
            <li><a href="/club_lecture/lectures/progression.php?livre_id=<?php echo $livre_id; ?>">Progression</a></li>
            <li><a href="/club_lecture/lectures/index.php">Mes lectures</a></li>
        </ul>

        <a href="/club_lecture/auth/logout.php">Déconnexion</a>
    </nav>
    <main>
    </section>