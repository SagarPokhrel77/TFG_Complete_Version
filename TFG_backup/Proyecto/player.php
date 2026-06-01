<?php
require_once __DIR__ . '/php/init.php';
requireLogin();

if (!isset($_GET["id"])) {
    die("Jugador no encontrado");
}

$id = intval($_GET["id"]);

$stmt = $conn->prepare("SELECT * FROM jugadores WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Jugador no encontrado");
}

$player = $result->fetch_assoc();

/* COLOR CARD */
$cardClass = "gold";

if ($player["rating"] < 75) {
    $cardClass = "bronze";
}
elseif ($player["rating"] < 88) {
    $cardClass = "silver";
}

/* ================= FAVORITOS (DB) ================= */
$isFav = false;

if (isset($_SESSION["user_id"])) {

    $user_id = $_SESSION["user_id"];

    $fav = $conn->prepare("
        SELECT id 
        FROM favoritos 
        WHERE user_id=? AND jugador_id=?
    ");

    $fav->bind_param("ii", $user_id, $id);
    $fav->execute();

    $resFav = $fav->get_result();

    $isFav = ($resFav->num_rows > 0);
}
?>

<?php
$activePage = "inicio";
$pageTitle = $player["nombre"];
$extraStylesheets = ["CSS/player.css"];
include __DIR__ . '/php/header.php';
?>

<?php include("php/navbar.php"); ?>

<div class="background-glow"></div>

<div class="page">

    <!-- LEFT -->
    <div class="left">

        <div class="card-wrapper" id="flipCard">

            <!-- FRONT -->
            <div class="fifa-card front <?= $cardClass ?>">

                <div class="card-top">

                    <div class="rating">
                        <?= $player["rating"] ?>
                    </div>

                    <div class="position">
                        <?= $player["posicion"] ?>
                    </div>

                </div>

                <div class="player-image">
                    <img src="<?= $player["foto"] ?>">
                </div>

                <div class="player-name">
                    <?= $player["nombre"] ?>
                </div>

                <div class="club">
                    <?= $player["equipo"] ?>
                </div>

            </div>

            <!-- BACK -->
            <div class="fifa-card back <?= $cardClass ?>">

                <div class="back-title">
                    PLAYER STATS
                </div>

                <div class="stats-grid">

                    <div class="stat-box">
                        <span>⚽ GOLES</span>
                        <h2><?= $player["goles"] ?></h2>
                    </div>

                    <div class="stat-box">
                        <span>🎯 ASIST.</span>
                        <h2><?= $player["asistencias"] ?></h2>
                    </div>

                    <div class="stat-box">
                        <span>🏟 PARTIDOS</span>
                        <h2><?= $player["partidos"] ?></h2>
                    </div>

                    <div class="stat-box">
                        <span>⭐ RATING</span>
                        <h2><?= $player["rating"] ?></h2>
                    </div>

                </div>

            </div>

        </div>

        <button class="flip-btn" onclick="flipCard()">
            ⚡ Transformar carta FIFA
        </button>

    </div>

    <!-- RIGHT -->
    <div class="right">

        <div class="info-box">

            <h1><?= $player["nombre"] ?></h1>

            <div class="badges">
                <span><?= $player["equipo"] ?></span>
                <span><?= $player["posicion"] ?></span>
                <span><?= $player["edad"] ?> años</span>
            </div>

            <!-- ================= FAVORITO FIX ================= -->
            <form action="PHP/favoritotoogle.php" method="POST">

                <input type="hidden" name="jugador_id" value="<?= $player['id'] ?>">

                <button type="submit" class="fav-btn">

                    <?php if($isFav): ?>
                        💔 Quitar de favoritos
                    <?php else: ?>
                        ❤️ Añadir a favoritos
                    <?php endif; ?>

                </button>

            </form>

            <!-- DESCRIPTION -->
            <div class="description">
                <?= nl2br($player["observaciones"]) ?>
            </div>

            <!-- STATS -->
            <div class="stats">

                <div class="stat">
                    <span>Goles</span>
                    <b><?= $player["goles"] ?></b>
                </div>

                <div class="stat">
                    <span>Asistencias</span>
                    <b><?= $player["asistencias"] ?></b>
                </div>

                <div class="stat">
                    <span>Partidos</span>
                    <b><?= $player["partidos"] ?></b>
                </div>

            </div>

            <!-- RATING -->
            <div class="rating-area">

                <div class="rating-text">Overall Rating</div>

                <div class="rating-bar">
                    <div class="rating-fill" style="width: <?= $player["rating"] ?>%"></div>
                </div>

            </div>

            <a href="user.php">
                <button class="back-btn">⬅ Volver</button>
            </a>

        </div>

    </div>

</div>

<script>

function flipCard(){
    document.getElementById("flipCard").classList.toggle("flipped");
}

</script>

<?php include __DIR__ . '/php/footer.php'; ?>