<?php
require_once __DIR__ . '/php/init.php';
require_once __DIR__ . '/php/upload_image.php';
requireAdmin();

$basePath = "uploads/";
$error = "";

$clubesList = [];
$clubesQ = $conn->query("SELECT nombre FROM clubes ORDER BY nombre ASC");
if ($clubesQ) {
    while ($c = $clubesQ->fetch_assoc()) {
        $clubesList[] = $c["nombre"];
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nombre = trim($_POST["nombre"] ?? "");
    $edad = intval($_POST["edad"] ?? 0);
    $posicion = trim($_POST["posicion"] ?? "");
    $equipo = trim($_POST["equipo"] ?? "");
    $rating = intval($_POST["rating"] ?? 0);
    $valor_mercado = trim($_POST["valor_mercado"] ?? "");
    $observaciones = trim($_POST["observaciones"] ?? "");
    $nacionalidad = trim($_POST["nacionalidad"] ?? "");

    if ($nombre === "" || $posicion === "" || $equipo === "" || $edad <= 0) {
        $error = "Completa los campos obligatorios (nombre, edad, posición y equipo).";
    } elseif ($rating < 0 || $rating > 99) {
        $error = "El rating debe estar entre 0 y 99.";
    } else {

        $foto = "uploads/default.png";

        if (isset($_FILES["foto"]) && $_FILES["foto"]["error"] !== UPLOAD_ERR_NO_FILE) {
            $upload = saveValidatedImageUpload($_FILES["foto"], $basePath, "foto");
            if (!$upload['ok']) {
                $error = $upload['error'];
            } elseif ($upload['path']) {
                $foto = $upload['path'];
            }
        }

        if ($error === "") {

        $stmt = $conn->prepare("
            INSERT INTO jugadores
            (nombre, edad, posicion, equipo, foto, rating, valor_mercado, observaciones, nacionalidad)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "sisssisss",
            $nombre,
            $edad,
            $posicion,
            $equipo,
            $foto,
            $rating,
            $valor_mercado,
            $observaciones,
            $nacionalidad
        );

        $stmt->execute();

        setFlash("success", "Jugador \"" . $nombre . "\" creado correctamente.");
        header("Location: user.php");
        exit();
        }
    }
}

$activePage = "inicio";
$pageTitle = "Nuevo jugador";
$extraStylesheets = ["CSS/editar_jugador.css"];
include __DIR__ . '/php/header.php';
?>

<?php include("php/navbar.php"); ?>
<?php renderFlash(); ?>

<div class="form-page">
    <h1 class="btn-with-icon"><?= icon('add', 24) ?> Nuevo jugador</h1>
    <p class="form-hint">Los campos marcados con * son obligatorios.</p>

    <?php if ($error): ?>
        <p class="form-error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="player-form">
        <label>Nombre *</label>
        <input type="text" name="nombre" value="<?= htmlspecialchars($_POST["nombre"] ?? "") ?>" required>

        <label>Edad *</label>
        <input type="number" name="edad" min="15" max="50" value="<?= htmlspecialchars($_POST["edad"] ?? "") ?>" required>

        <label>Posición *</label>
        <select name="posicion" required>
            <option value="">Selecciona posición</option>
            <?php
            $posiciones = ["Portero", "Defensa", "Centrocampista", "Delantero", "Extremo"];
            $selPos = $_POST["posicion"] ?? "";
            foreach ($posiciones as $pos):
            ?>
            <option value="<?= $pos ?>" <?= $selPos === $pos ? "selected" : "" ?>><?= $pos ?></option>
            <?php endforeach; ?>
        </select>

        <label>Equipo *</label>
        <?php if (!empty($clubesList)): ?>
        <select name="equipo" required>
            <option value="">Selecciona club</option>
            <?php $selEquipo = $_POST["equipo"] ?? ""; ?>
            <?php foreach ($clubesList as $club): ?>
            <option value="<?= htmlspecialchars($club) ?>" <?= $selEquipo === $club ? "selected" : "" ?>>
                <?= htmlspecialchars($club) ?>
            </option>
            <?php endforeach; ?>
        </select>
        <?php else: ?>
        <input type="text" name="equipo" value="<?= htmlspecialchars($_POST["equipo"] ?? "") ?>" required>
        <?php endif; ?>

        <label>Rating * (0-99)</label>
        <input type="number" name="rating" min="0" max="99" value="<?= htmlspecialchars($_POST["rating"] ?? "70") ?>" required>

        <label>Valor de mercado</label>
        <input type="text" name="valor_mercado" placeholder="Ej: 15M€" value="<?= htmlspecialchars($_POST["valor_mercado"] ?? "") ?>">

        <label>Nacionalidad</label>
        <input type="text" name="nacionalidad" placeholder="Ej: España" value="<?= htmlspecialchars($_POST["nacionalidad"] ?? "") ?>">

        <label>Observaciones</label>
        <textarea name="observaciones" rows="4" placeholder="Descripción del jugador..."><?= htmlspecialchars($_POST["observaciones"] ?? "") ?></textarea>

        <label>Foto</label>
        <input type="file" name="foto" accept="image/jpeg,image/png,image/gif,image/webp,.jpg,.jpeg,.png,.gif,.webp">

        <div class="form-actions-row">
            <button type="submit" class="profile-btn btn-confirm-sell">Crear jugador</button>
            <a href="user.php" class="btn-cancel">Cancelar</a>
        </div>
    </form>
</div>

<?php include __DIR__ . '/php/footer.php'; ?>
