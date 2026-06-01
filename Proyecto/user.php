<?php
session_start();
include("php/DataBase.php");

/* ================= VENTA AUTOMÁTICA ================= */
if (isset($_GET['sell'])) {

    $id = intval($_GET['sell']);

    // obtener jugador
    $stmt = $conn->prepare("SELECT * FROM jugadores WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $player = $stmt->get_result()->fetch_assoc();

    if ($player) {

        // insertar en transferencias
        $ins = $conn->prepare("
            INSERT INTO transferencias
            (jugador, posicion, rating, foto_jugador, club_origen, club_destino, logo_origen, logo_destino, precio, fecha, estado)
            VALUES (?, ?, ?, ?, ?, 'Libre', ?, 'uploads/default.png', ?, CURDATE(), 'En venta')
        ");

        $club = $player['equipo'];

        $ins->bind_param(
            "ssissss",
            $player['nombre'],
            $player['posicion'],
            $player['rating'],
            $player['foto'],
            $club,
            $player['foto'],
            $player['valor_mercado']
        );

        $ins->execute();
    }

    header("Location: user.php");
    exit;
}

/* ================= JUGADORES ================= */
$sql = "SELECT * FROM jugadores";
$result = $conn->query($sql);

/* ================= CLUBES ================= */
$clubs = $conn->query("
    SELECT DISTINCT equipo 
    FROM jugadores 
    ORDER BY equipo ASC
");

/* ================= FAVORITOS ================= */
$favoritesCount = 0;

if (isset($_SESSION["user_id"])) {

    $uid = $_SESSION["user_id"];

    $favQ = $conn->prepare("
        SELECT COUNT(DISTINCT jugador_id) as total
        FROM favoritos
        WHERE user_id = ?
    ");

    $favQ->bind_param("i", $uid);
    $favQ->execute();

    $favRes = $favQ->get_result()->fetch_assoc();

    $favoritesCount = $favRes["total"];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Elite Players</title>
<link rel="stylesheet" href="CSS/user.css">
</head>

<body>

<div class="navbar">

    <div class="logo"> ⚽ Elite Players</div>

    <input type="text" id="search" placeholder="Buscar jugador...">

    <select id="filterClub">
        <option value="">Todos los clubes</option>
        <?php while($club = $clubs->fetch_assoc()): ?>
            <option value="<?= $club['equipo'] ?>">
                <?= $club['equipo'] ?>
            </option>
        <?php endwhile; ?>
    </select>

    <select id="filterPosition">
        <option value="">Todas las posiciones</option>
        <option value="Portero">Portero</option>
        <option value="Defensa">Defensa</option>
        <option value="Centrocampista">Centrocampista</option>
        <option value="Delantero">Delantero</option>
        <option value="Extremo">Extremo</option>
    </select>

    <a href="favorito.php" class="fav-link">
        ❤️ Favoritos
        <span class="fav-count"><?= $favoritesCount ?></span>
    </a>

    <a href="partidos.php" class="fav-link">
        🏆 Partidos
    </a>

    <a href="transferencias.php" class="fav-link">
         Transferencias
    </a>

</div>

<div class="hero">
    <h1>Todos los Jugadores</h1>
</div>

<div class="container">
<div class="grid" id="players">

<?php while($r = $result->fetch_assoc()): ?>

<div class="card player"
     data-name="<?= strtolower($r["nombre"]) ?>"
     data-club="<?= $r["equipo"] ?>"
     data-position="<?= $r["posicion"] ?>">

    <div class="rank">
        ⭐ <?= $r["rating"] ?>/99
    </div>

    <div class="img-container">
        <img src="<?= $r["foto"] ?>" class="player-img">
    </div>

    <h3><?= $r["nombre"] ?></h3>
    <p><?= $r["equipo"] ?></p>
    <p><?= $r["posicion"] ?></p>

    <a href="player.php?id=<?= $r['id'] ?>">
        <button class="profile-btn">Ver perfil</button>
    </a>

    <!-- 🔥 BOTÓN NUEVO -->
    <a href="user.php?sell=<?= $r['id'] ?>">
        <button class="profile-btn">💰 Poner en venta</button>
    </a>

</div>

<?php endwhile; ?>

</div>
</div>

<script>
const search = document.getElementById("search");
const filterClub = document.getElementById("filterClub");
const filterPosition = document.getElementById("filterPosition");
const players = document.querySelectorAll(".player");

function filterPlayers(){
    let text = search.value.toLowerCase();
    let club = filterClub.value;
    let pos = filterPosition.value;

    players.forEach(p => {
        let show =
            p.dataset.name.includes(text) &&
            (club === "" || p.dataset.club === club) &&
            (pos === "" || p.dataset.position === pos);

        p.style.display = show ? "block" : "none";
    });
}

search.addEventListener("keyup", filterPlayers);
filterClub.addEventListener("change", filterPlayers);
filterPosition.addEventListener("change", filterPlayers);
</script>

</body>
</html>