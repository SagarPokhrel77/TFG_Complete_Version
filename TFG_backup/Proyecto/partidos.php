<?php

require_once __DIR__ . '/php/init.php';
requireLogin();
require_once __DIR__ . '/php/empty-state.php';

$sql = "SELECT * FROM partidos ORDER BY fecha ASC";
$result = $conn->query($sql);

$partidos = [];
while ($p = $result->fetch_assoc()) {
    $partidos[] = $p;
}

function resolveMatchLogo(?string $value): string
{
    $value = trim((string) $value);
    if ($value === "") {
        return "uploads/default.png";
    }
    if (str_starts_with($value, "uploads/") || str_starts_with($value, "http://") || str_starts_with($value, "https://")) {
        return $value;
    }
    return "uploads/" . $value;
}

$activePage = "partidos";
$pageTitle = "Partidos";
$extraStylesheets = ["CSS/partidos.css"];
include __DIR__ . '/php/header.php';
?>

<?php include("php/navbar.php"); ?>
<?php renderFlash(); ?>

<div class="page-hero">
    <h1 class="btn-with-icon" style="justify-content:center;"><?= icon('trophy', 28) ?> Partidos</h1>
</div>

<div class="matches">
<?php if (count($partidos) === 0): ?>
    <?php
    $ctaUrl = isAdmin() ? 'gestionar_partidos.php' : 'user.php';
    $ctaLabel = isAdmin() ? 'Añadir primer partido' : 'Volver al inicio';
    renderEmptyState(
        'trophy',
        'No hay partidos programados',
        'Cuando se registren partidos, aparecerán aquí con resultado, fecha y estadio.',
        $ctaUrl,
        $ctaLabel
    );
    ?>
<?php else: ?>
<?php foreach ($partidos as $p): ?>
<div class="match-card">
    <div class="teams-row">
        <div class="team-side">
            <img
                src="<?= htmlspecialchars(resolveMatchLogo($p['logo_local'] ?? null)) ?>"
                class="team-logo"
                alt=""
                onerror="this.onerror=null; this.src='uploads/default.png';"
            >
            <h2><?= htmlspecialchars($p["equipo_local"]) ?></h2>
        </div>
        <div class="vs">VS</div>
        <div class="team-side">
            <img
                src="<?= htmlspecialchars(resolveMatchLogo($p['logo_visitante'] ?? null)) ?>"
                class="team-logo"
                alt=""
                onerror="this.onerror=null; this.src='uploads/default.png';"
            >
            <h2><?= htmlspecialchars($p["equipo_visitante"]) ?></h2>
        </div>
    </div>
    <div class="score"><?= $p["goles_local"] ?> - <?= $p["goles_visitante"] ?></div>
    <div class="info">
        <p class="info-line"><?= icon('calendar', 16) ?> <?= htmlspecialchars($p["fecha"]) ?></p>
        <p class="info-line"><?= icon('stadium', 16) ?> <?= htmlspecialchars($p["estadio"] ?? "") ?></p>
        <span class="status"><?= htmlspecialchars($p["estado"]) ?></span>
    </div>
</div>
<?php endforeach; ?>
<?php endif; ?>
</div>

<?php include __DIR__ . '/php/footer.php'; ?>
