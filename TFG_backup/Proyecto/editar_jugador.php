<?php
require_once __DIR__ . '/php/init.php';
require_once __DIR__ . '/php/upload_image.php';
requireAdmin();

if (!isset($_GET["id"])) {
    header("Location: user.php");
    exit();
}

$id = intval($_GET["id"]);
$basePath = "uploads/";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nombre = trim($_POST["nombre"] ?? "");
    $edad = intval($_POST["edad"] ?? 0);
    $posicion = trim($_POST["posicion"] ?? "");
    $equipo = trim($_POST["equipo"] ?? "");
    $rating = intval($_POST["rating"] ?? 0);

    if ($nombre === "" || $posicion === "" || $equipo === "") {
        $error = "Completa los campos obligatorios.";
    } else {

        $stmt = $conn->prepare("SELECT foto FROM jugadores WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $old = $stmt->get_result()->fetch_assoc();

        if (!$old) {
            header("Location: user.php");
            exit();
        }

        $foto = $old["foto"];

        if (isset($_FILES["foto"]) && $_FILES["foto"]["error"] !== UPLOAD_ERR_NO_FILE) {
            $upload = saveValidatedImageUpload($_FILES["foto"], $basePath, "foto");
            if (!$upload['ok']) {
                $error = $upload['error'];
            } elseif ($upload['path']) {
                if ($foto && file_exists($foto) && strpos($foto, "default") === false) {
                    unlink($foto);
                }
                $foto = $upload['path'];
            }
        }

        if ($error === "") {

        $stmt = $conn->prepare("
            UPDATE jugadores
            SET nombre=?, edad=?, posicion=?, equipo=?, foto=?, rating=?
            WHERE id=?
        ");
        $stmt->bind_param("sisssii", $nombre, $edad, $posicion, $equipo, $foto, $rating, $id);
        $stmt->execute();

        setFlash("success", "Jugador actualizado correctamente.");
        header("Location: user.php");
        exit();
        }
    }
}

$stmt = $conn->prepare("SELECT * FROM jugadores WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$player = $stmt->get_result()->fetch_assoc();

if (!$player) {
    header("Location: user.php");
    exit();
}
$activePage = "inicio";
$pageTitle = "Editar jugador";
$extraStylesheets = ["CSS/editar_jugador.css"];
include __DIR__ . '/php/header.php';
?>

<?php include("php/navbar.php"); ?>
<?php renderFlash(); ?>

<div class="form-page">
    <h1 class="btn-with-icon"><?= icon('edit', 24) ?> Editar jugador</h1>

    <?php if ($error): ?>
        <p class="form-error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="player-form">
        <label>Nombre</label>
        <input type="text" name="nombre" value="<?= htmlspecialchars($player["nombre"]) ?>" required>

        <label>Edad</label>
        <input type="number" name="edad" value="<?= (int) $player["edad"] ?>" required>

        <label>Posición</label>
        <input type="text" name="posicion" value="<?= htmlspecialchars($player["posicion"]) ?>" required>

        <label>Equipo</label>
        <input type="text" name="equipo" value="<?= htmlspecialchars($player["equipo"]) ?>" required>

        <label>Rating</label>
        <input type="number" name="rating" min="0" max="99" value="<?= (int) $player["rating"] ?>" required>

        <label>Foto (opcional)</label>
        <input type="file" name="foto" accept="image/jpeg,image/png,image/gif,image/webp,.jpg,.jpeg,.png,.gif,.webp">

        <?php if (!empty($player["foto"])): ?>
            <img src="<?= htmlspecialchars($player["foto"]) ?>" alt="Foto actual" class="preview-img">
        <?php endif; ?>

        <button type="submit" class="profile-btn">Guardar cambios</button>
    </form>
</div>

<?php include __DIR__ . '/php/footer.php'; ?>
