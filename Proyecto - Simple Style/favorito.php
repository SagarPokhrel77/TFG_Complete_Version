<?php
session_start();
include("php/DataBase.php");

if (!isset($_SESSION["user_id"])) {
    die("Debes iniciar sesión");
}

$user_id = $_SESSION["user_id"];

$sql = "
    SELECT j.*
    FROM favoritos f
    INNER JOIN jugadores j ON f.jugador_id = j.id
    WHERE f.user_id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Mis Favoritos</title>
<link rel="stylesheet" href="CSS/favorito.css">
</head>

<body>

<!-- NAV SUPERIOR -->
<div style="padding:15px; display:flex; justify-content:space-between; align-items:center;">

    <!-- BOTÓN VOLVER -->
    <a href="user.php">
        <button style="
            padding:10px 15px;
            border:none;
            background:#0e7baa;
            color:white;
            border-radius:8px;
            cursor:pointer;
        ">
         Volver
        </button>
    </a>

    <h2>❤️ Mis Favoritos</h2>

</div>

<div class="container">
<div class="grid">

<?php if ($result->num_rows == 0): ?>

    <h2 style="text-align:center; width: 200%;margin: 420px">
        No tienes jugadores favoritos
    </h2>

<?php else: ?>

<?php while($r = $result->fetch_assoc()): ?>

<div class="card player">

    <div class="rank">
        ⭐ <?= $r["rating"] ?>/99
    </div>

    <img src="<?= $r["foto"] ?>">

    <h3><?= $r["nombre"] ?></h3>
    <p><?= $r["equipo"] ?></p>
    <p><?= $r["posicion"] ?></p>

    <!-- BOTÓN VER -->
    <a href="player.php?id=<?= $r['id'] ?>">
        <button>Ver perfil</button>
    </a>

    <!-- BOTÓN QUITAR FAVORITO -->
    <form action="PHP/favoritotoogle.php" method="POST" style="margin-top:10px;">

        <!-- ID DEL JUGADOR -->
        <input type="hidden" name="jugador_id" value="<?= $r['id'] ?>">

        <!-- 🔥 ESTO ES LO IMPORTANTE -->
        <input type="hidden" name="redirect" value="favorito">

        <button type="submit"
            style="
                background:#ff3b3b;
                color:white;
                border:none;
                padding:8px 12px;
                border-radius:8px;
                cursor:pointer;
            ">
            💔 Quitar
        </button>

    </form>

</div>

<?php endwhile; ?>

<?php endif; ?>

</div>
</div>

</body>
</html>