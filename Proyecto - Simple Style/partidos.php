<?php
session_start();
include("php/DataBase.php");

/* PARTIDOS */
$sql = "SELECT * FROM partidos ORDER BY fecha ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">

<title>Partidos</title>

<link rel="stylesheet" href="CSS/partidos.css">

</head>

<body>

<!-- TOP BAR -->
<div class="top-bar">

    <!-- LOGO -->
    <div class="logo">
        <a href="user.php">
            <img src="uploads/logo.png" alt="Logo">
        </a>
    </div>

    <!-- TITLE -->
    <h1> Partidos</h1>

    <!-- BACK BUTTON -->
    <a href="user.php" class="back-btn">
        Volver
    </a>

</div>

<!-- MATCHES -->
<div class="matches">

<?php while($p = $result->fetch_assoc()): ?>

<div class="match-card">

    <!-- TEAMS -->
    <div class="teams-row">

        <!-- LOCAL -->
        <div class="team-side">

            <img
                src="uploads/<?= $p['logo_local'] ?>"
                class="team-logo"
                alt="<?= $p['equipo_local'] ?>"
            >

            <h2><?= $p['equipo_local'] ?></h2>

        </div>

        <!-- VS -->
        <div class="vs">
            VS
        </div>

        <!-- VISITANTE -->
        <div class="team-side">

            <img
                src="uploads/<?= $p['logo_visitante'] ?>"
                class="team-logo"
                alt="<?= $p['equipo_visitante'] ?>"
            >

            <h2><?= $p['equipo_visitante'] ?></h2>

        </div>

    </div>

    <!-- SCORE -->
    <div class="score">

        <?= $p['goles_local'] ?>

        -

        <?= $p['goles_visitante'] ?>

    </div>

    <!-- INFO -->
    <div class="info">

        <p>
            <b> <?= $p['fecha'] ?> </b>
        </p>

        <p>
         <?= $p['estadio'] ?>
        </p>

        <span class="status">

            <?= $p['estado'] ?>

        </span>

    </div>

</div>

<?php endwhile; ?>

</div>

</body>
</html>