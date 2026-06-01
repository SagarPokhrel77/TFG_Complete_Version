<?php

require_once __DIR__ . '/php/init.php';
requireLogin();
require_once __DIR__ . '/php/empty-state.php';

/* ================= ACCIONES ADMIN ================= */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (!isAdmin()) {
        setFlash("error", "No tienes permisos para realizar esta acción.");
        header("Location: transferencias.php");
        exit();
    }

    if (isset($_POST["quitar_venta_id"])) {
        $id = intval($_POST["quitar_venta_id"]);
        $stmt = $conn->prepare("DELETE FROM transferencias WHERE id = ? AND estado = 'En venta'");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        setFlash("success", "Venta retirada correctamente.");
        header("Location: transferencias.php");
        exit();
    }

    if (isset($_POST["confirmar_venta_id"])) {
        $id = intval($_POST["confirmar_venta_id"]);
        $destino = trim($_POST["club_destino"] ?? "");
        $precio = trim($_POST["precio"] ?? "");

        if ($destino === "") {
            setFlash("error", "Selecciona un club destino.");
            header("Location: transferencias.php");
            exit();
        }

        $stmt = $conn->prepare("
            UPDATE transferencias
            SET club_destino = ?, estado = 'Confirmado', fecha = CURDATE(), precio = IF(?, ?, precio)
            WHERE id = ? AND estado = 'En venta'
        ");
        $stmt->bind_param("sssi", $destino, $precio, $precio, $id);
        $stmt->execute();

        setFlash("success", "Transferencia confirmada.");
        header("Location: transferencias.php");
        exit();
    }
}

$clubs = [];
$clubsQ = $conn->query("SELECT nombre FROM clubes ORDER BY nombre ASC");
while ($c = $clubsQ->fetch_assoc()) {
    $clubs[] = $c["nombre"];
}



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



$activePage = "transferencias";
$isAdmin = isAdmin();

$pageTitle = "Mercado de Transferencias";
$extraStylesheets = ["CSS/transferencias.css"];
include __DIR__ . '/php/header.php';
?>

<?php include("php/navbar.php"); ?>

<?php renderFlash(); ?>



<div class="hero">

    <h1>Mercado de fichajes</h1>

    <p>Movimientos oficiales entre clubes</p>

</div>



<div class="container">

<div class="grid">



<?php if ($transfers->num_rows == 0): ?>
    <?php renderEmptyState(
        'briefcase',
        'Mercado vacío',
        'No hay transferencias registradas. Puedes poner jugadores en venta desde el listado principal.',
        'user.php',
        'Ver jugadores'
    ); ?>
<?php else: ?>



<?php while ($t = $transfers->fetch_assoc()): ?>



<?php

$foto = !empty($t['foto_jugador']) ? $t['foto_jugador'] : "uploads/default.png";

$origen = !empty($t['logo_origen_real']) ? "uploads/" . $t['logo_origen_real'] : "uploads/default.png";

$destino = !empty($t['logo_destino_real']) ? "uploads/" . $t['logo_destino_real'] : "uploads/default.png";

?>



<div class="transfer-card">

    <div class="transfer-top">

        <img src="<?= htmlspecialchars($foto) ?>" class="player-img" onerror="this.onerror=null; this.src='uploads/default.png';">

        <div class="transfer-info">

            <h2><?= htmlspecialchars($t['jugador']) ?></h2>

            <p class="position"><?= htmlspecialchars($t['posicion']) ?></p>

            <div class="rating"><?= icon('star', 16) ?> <?= htmlspecialchars($t['rating']) ?>/99</div>

        </div>

    </div>

    <div class="clubs">

        <div class="club">

            <img src="<?= htmlspecialchars($origen) ?>" onerror="this.onerror=null; this.src='uploads/default.png';">

            <span><?= htmlspecialchars($t['club_origen_nombre'] ?? $t['club_origen']) ?></span>

        </div>

        <div class="arrow"><?= icon('arrow-right', 24) ?></div>

        <div class="club">

            <img src="<?= htmlspecialchars($destino) ?>" onerror="this.onerror=null; this.src='uploads/default.png';">

            <span><?= htmlspecialchars($t['club_destino_nombre'] ?? $t['club_destino']) ?></span>

        </div>

    </div>

    <div class="transfer-details">

        <p class="info-line"><?= icon('money', 16) ?> <?= htmlspecialchars($t['precio']) ?></p>

        <p class="info-line"><?= icon('calendar', 16) ?> <?= htmlspecialchars($t['fecha']) ?></p>

        <p class="estado <?= strtolower(str_replace(' ', '-', $t['estado'])) ?>"><?= htmlspecialchars($t['estado']) ?></p>

    </div>

    <?php if ($isAdmin && $t["estado"] === "En venta"): ?>
        <div class="admin-transfer-actions">
            <form method="POST" class="admin-inline">
                <input type="hidden" name="confirmar_venta_id" value="<?= (int) $t["id"] ?>">
                <select name="club_destino" required>
                    <option value="">Club destino</option>
                    <?php foreach ($clubs as $club): ?>
                        <option value="<?= htmlspecialchars($club) ?>"><?= htmlspecialchars($club) ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="text" name="precio" placeholder="Precio (opcional)">
                <button type="submit" class="btn-confirm btn-with-icon"><?= icon('check', 16) ?> Confirmar</button>
            </form>

            <form method="POST" class="admin-inline">
                <input type="hidden" name="quitar_venta_id" value="<?= (int) $t["id"] ?>">
                <button type="submit" class="btn-remove btn-with-icon"><?= icon('close', 16) ?> Quitar</button>
            </form>
        </div>
    <?php endif; ?>

</div>



<?php endwhile; ?>

<?php endif; ?>



</div>

</div>



<button id="topBtn" type="button" onclick="window.scrollTo({top:0, behavior:'smooth'})" aria-label="Volver arriba"><?= icon('chevron-up', 22) ?></button>



<script>

window.addEventListener("scroll", () => {

    const btn = document.getElementById("topBtn");

    btn.style.opacity = (window.scrollY > 300) ? "1" : "0";

});

</script>

<?php include __DIR__ . '/php/footer.php'; ?>


