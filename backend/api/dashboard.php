<?php
session_start();
include_once('../components/connect.php');

// Verifica che l'utente sia loggato
if (!isset($_COOKIE['tutor_id'])) {
        $tutor_id = $_COOKIE['tutor_id'];
}else{
    $tutor_id='';
}


// Recupera il profilo dell’utente
$select_profile = $conn->prepare("SELECT * FROM users WHERE id = ?");
$select_profile->bind_param("i", $tutor_id);
$select_profile->execute();
$result = $select_profile->get_result();
$fetch_profile = $result->fetch_assoc();

// Se non è un tutor, reindirizza
if (!$fetch_profile || $fetch_profile['role'] !== 'tutor') {
    header('Location: ../student/home.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Tutor</title>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>
<body>

<!-- Header -->
<header class="header">
    <section class="flex">
        <a href="/Skiddies/back-end/admin/dashboard.php">
            <img src="/Skiddies/front-end/images/logo.png" width="100px" alt="Logo">
        </a>

        <form action="search_page.php" method="post" class="search-form">
            <input type="text" name="search" placeholder="Cerca contenuti..." required maxlength="100">
            <button type="submit" class="bx bx-search-alt-2" name="search-btn"></button>
        </form>

        <div class="icons">
            <div id="menu-btn" class="bx bx-list-plus"></div>
            <div id="search-btn" class="bx bx-search-alt-2-plus"></div>
            <div id="user-btn" class="bx bxs-user"></div>
        </div>

        <div class="profile">
            <img src="../uploaded_files/<?= htmlspecialchars($fetch_profile['image']); ?>" alt="Immagine profilo">
            <h3><?= htmlspecialchars($fetch_profile['name']); ?></h3>
            <p><?= htmlspecialchars($fetch_profile['email']); ?></p>
            <a href="profile.php" class="btn">Profilo</a>
            <a href="../components/admin_logout.php" class="btn" onclick="return confirm('Vuoi uscire?');">Esci</a>
        </div>
    </section>
</header>

<!-- Sidebar -->
<div class="side-bar">
    <div class="profile">
        <img src="../uploaded_files/<?= htmlspecialchars($fetch_profile['image']); ?>" alt="Immagine profilo">
        <h3><?= htmlspecialchars($fetch_profile['name']); ?></h3>
        <p><?= htmlspecialchars($fetch_profile['email']); ?></p>
        <a href="profile.php" class="btn">Esplora profilo</a>
    </div>

    <nav class="navbar">
        <a href="dashboard.php"><i class="bx bxs-home-heart"></i><span>Dashboard</span></a>
        <a href="playlists.php"><i class="bx bxs-movie-play"></i><span>Playlist</span></a>
        <a href="contents.php"><i class="bx bxs-video"></i><span>Contenuti</span></a>
        <a href="comments.php"><i class="bx bxs-comment"></i><span>Commenti</span></a>
        <a href="../components/admin_logout.php" onclick="return confirm('Esci?');"><i class="bx bx-log-out-circle"></i><span>Esci</span></a>
    </nav>
</div>

<!-- Contenuto Principale -->
<section class="main-content">
    <div class="box">
        <h2>Ciao, <?= htmlspecialchars($fetch_profile['name']); ?> 👋</h2>
        <p>Benvenuto nella tua dashboard. Qui puoi gestire i tuoi contenuti, visualizzare i commenti degli studenti e monitorare i like.</p>
    </div>
</section>

</body>
</html>
