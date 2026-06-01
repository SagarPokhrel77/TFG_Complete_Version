<?php

require_once __DIR__ . '/php/init.php';
requireLogin();
require_once __DIR__ . '/php/empty-state.php';

$user_id = (int) $_SESSION["user_id"];

$stmt = $conn->prepare("
    SELECT j.*
    FROM favoritos f
    INNER JOIN jugadores j ON f.jugador_id = j.id
    WHERE f.user_id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$favoritos = [];
while ($r = $result->fetch_assoc()) {
    $favoritos[] = $r;
}

$activePage = "favoritos";
$pageTitle = "Mis Favoritos";
$extraStylesheets = ["CSS/favorito.css"];
include __DIR__ . '/php/header.php';
?>

<?php include("php/navbar.php"); ?>
<?php renderFlash(); ?>

<div class="page-hero">
    <h1 class="btn-with-icon" style="justify-content:center;"><?= icon('heart', 28) ?> Mis Favoritos</h1>
</div>

<div class="container">
<div class="grid">

<?php if (count($favoritos) === 0): ?>
    <?php renderEmptyState(
        'heart',
        'Sin favoritos',
        'Explora el listado de jugadores y marca los que más te interesen.',
        'user.php',
        'Explorar jugadores'
    ); ?>
<?php else: ?>

<?php foreach ($favoritos as $r): ?>
<div class="card player">
    <div class="rank"><?= icon('star', 16) ?> <?= $r["rating"] ?>/99</div>
    <img src="<?= htmlspecialchars($r["foto"]) ?>" alt="">
    <h3><?= htmlspecialchars($r["nombre"]) ?></h3>
    <p><?= htmlspecialchars($r["equipo"]) ?></p>
    <p><?= htmlspecialchars($r["posicion"]) ?></p>
    <a href="player.php?id=<?= (int) $r['id'] ?>">
        <button type="button" class="profile-btn">Ver perfil</button>
    </a>
    <form action="PHP/favoritotoogle.php" method="POST" style="margin-top:10px;">
        <input type="hidden" name="jugador_id" value="<?= (int) $r['id'] ?>">
        <input type="hidden" name="redirect" value="favorito">
        <button type="submit" class="remove-btn btn-with-icon"><?= icon('heart-broken', 18) ?> Quitar</button>
    </form>
</div>
<?php endforeach; ?>

<?php endif; ?>

</div>
</div>

<?php include __DIR__ . '/php/footer.php'; ?>
