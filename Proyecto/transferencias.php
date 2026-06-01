<?php
session_start();
include("php/DataBase.php");

/* ================= TRANSFERENCIAS ================= */
$transfers = $conn->query("
SELECT t.*,
       c1.nombre AS club_origen_nombre,
       c2.nombre AS club_destino_nombre,
       c1.logo AS logo_origen_real,
       c2.logo AS logo_destino_real
FROM transferencias t
LEFT JOIN clubes c1 ON t.club_origen = c1.nombre
LEFT JOIN clubes c2 ON t.club_destino = c2.nombre
ORDER BY t.fecha DESC
");
?>

<!DOCTYPE html>
<html lang="es">

<head>
<meta charset="UTF-8">
<title>Mercado de Transferencias</title>
<link rel="stylesheet" href="CSS/transferencias.css">
</head>

<body>

<!-- NAVBAR -->
<div class="navbar">
    <div class="logo">⚽ Mercado de Transferencias</div>
    <a href="user.php" class="back-btn">⬅ Volver</a>
</div>

<!-- HERO -->
<div class="hero">
    <h1>Mercado de fichajes</h1>
    <p>Movimientos oficiales entre clubes</p>
</div>

<!-- CONTENIDO -->
<div class="container">
<div class="grid">

<?php if($transfers->num_rows == 0): ?>

    <h2 class="empty">No hay transferencias registradas</h2>

<?php else: ?>

<?php while($t = $transfers->fetch_assoc()): ?>

<?php
// FOTO JUGADOR
$foto = !empty($t['foto_jugador'])
    ? $t['foto_jugador']
    : "uploads/default.png";

/* ================= LOGOS CLUBES ================= */
$origen = !empty($t['logo_origen_real'])
    ? "uploads/" . $t['logo_origen_real']
    : "uploads/default.png";

$destino = !empty($t['logo_destino_real'])
    ? "uploads/" . $t['logo_destino_real']
    : "uploads/default.png";
?>

<div class="transfer-card">

    <!-- JUGADOR -->
    <div class="transfer-top">

        <img
            src="<?= htmlspecialchars($foto) ?>"
            class="player-img"
            onerror="this.onerror=null; this.src='uploads/default.png';"
        >

        <div class="transfer-info">

            <h2><?= htmlspecialchars($t['jugador']) ?></h2>

            <p class="position"><?= htmlspecialchars($t['posicion']) ?></p>

            <div class="rating">
                ⭐ <?= htmlspecialchars($t['rating']) ?>/99
            </div>

        </div>
    </div>

    <!-- CLUBS -->
    <div class="clubs">

        <!-- ORIGEN -->
        <div class="club">
            <img
                src="<?= htmlspecialchars($origen) ?>"
                onerror="this.onerror=null; this.src='uploads/default.png';"
            >
            <span><?= htmlspecialchars($t['club_origen_nombre']) ?></span>
        </div>

        <div class="arrow">➜</div>

        <!-- DESTINO -->
        <div class="club">
            <img
                src="<?= htmlspecialchars($destino) ?>"
                onerror="this.onerror=null; this.src='uploads/default.png';"
            >
            <span><?= htmlspecialchars($t['club_destino_nombre']) ?></span>
        </div>

    </div>

    <!-- DETALLES -->
    <div class="transfer-details">

        <p>💰 <?= htmlspecialchars($t['precio']) ?></p>

        <p>📅 <?= htmlspecialchars($t['fecha']) ?></p>

        <p class="estado <?= strtolower($t['estado']) ?>">
            <?= htmlspecialchars($t['estado']) ?>
        </p>

    </div>

    <!-- BOTÓN QUITAR DE VENTA -->
    <form method="POST" action="quitar_venta.php">
        <input type="hidden" name="id" value="<?= $t['id'] ?>">
        <button class="btn-remove">❌ Quitar de venta</button>
    </form>

</div>

<?php endwhile; ?>

<?php endif; ?>

</div>
</div>

<!-- BOTÓN SUBIR -->
<button id="topBtn" onclick="window.scrollTo({top:0, behavior:'smooth'})">⬆</button>

<script>
window.addEventListener("scroll", () => {
    const btn = document.getElementById("topBtn");
    btn.style.opacity = (window.scrollY > 300) ? "1" : "0";
});
</script>

</body>
</html>