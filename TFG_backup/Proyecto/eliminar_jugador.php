<?php
require_once __DIR__ . '/php/init.php';
requireAdmin();

/**
 * Elimina un jugador y sus datos relacionados.
 */
function eliminarJugadorPorId(mysqli $conn, int $id): array
{
    $stmt = $conn->prepare("SELECT id, nombre, foto FROM jugadores WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $player = $stmt->get_result()->fetch_assoc();

    if (!$player) {
        return ["ok" => false, "msg" => "El jugador no existe o ya fue eliminado."];
    }

    $conn->begin_transaction();

    try {
        // Transferencias (por nombre, no hay FK)
        $delTrans = $conn->prepare("DELETE FROM transferencias WHERE jugador = ?");
        $delTrans->bind_param("s", $player["nombre"]);
        $delTrans->execute();

        // Favoritos (CASCADE, por si acaso)
        $delFav = $conn->prepare("DELETE FROM favoritos WHERE jugador_id = ?");
        $delFav->bind_param("i", $id);
        $delFav->execute();

        // Jugador
        $del = $conn->prepare("DELETE FROM jugadores WHERE id = ?");
        $del->bind_param("i", $id);
        $del->execute();

        if ($del->affected_rows < 1) {
            throw new Exception("No se pudo eliminar el jugador de la base de datos.");
        }

        $conn->commit();

        if (
            !empty($player["foto"]) &&
            file_exists($player["foto"]) &&
            strpos($player["foto"], "default") === false
        ) {
            @unlink($player["foto"]);
        }

        return ["ok" => true, "msg" => "Jugador \"" . $player["nombre"] . "\" eliminado correctamente."];
    } catch (Exception $e) {
        $conn->rollback();
        return ["ok" => false, "msg" => $e->getMessage()];
    }
}

/* ================= CONFIRMAR ELIMINACIÓN (POST) ================= */
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["confirmar"])) {

    $id = intval($_POST["id"] ?? $_GET["id"] ?? 0);

    if ($id <= 0) {
        setFlash("error", "Identificador de jugador no válido.");
        header("Location: user.php");
        exit();
    }

    $resultado = eliminarJugadorPorId($conn, $id);

    if ($resultado["ok"]) {
        setFlash("success", $resultado["msg"]);
    } else {
        setFlash("error", $resultado["msg"]);
    }

    header("Location: user.php");
    exit();
}

/* ================= FORMULARIO DE CONFIRMACIÓN (GET) ================= */
$id = intval($_GET["id"] ?? 0);

if ($id <= 0) {
    header("Location: user.php");
    exit();
}

$stmt = $conn->prepare("SELECT id, nombre FROM jugadores WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$player = $stmt->get_result()->fetch_assoc();

if (!$player) {
    setFlash("error", "El jugador no existe o ya fue eliminado.");
    header("Location: user.php");
    exit();
}

$activePage = "inicio";
$pageTitle = "Eliminar jugador";
$extraStylesheets = ["CSS/editar_jugador.css"];
include __DIR__ . '/php/header.php';
?>

<?php include("php/navbar.php"); ?>
<?php renderFlash(); ?>

<div class="form-page">
    <h1 class="btn-with-icon"><?= icon('delete', 24) ?> Eliminar jugador</h1>
    <p class="confirm-text">
        ¿Seguro que quieres eliminar a
        <strong><?= htmlspecialchars($player["nombre"]) ?></strong>?
        También se quitarán sus favoritos y transferencias asociadas.
        Esta acción no se puede deshacer.
    </p>

    <form method="POST" action="eliminar_jugador.php" class="confirm-actions">
        <input type="hidden" name="confirmar" value="1">
        <input type="hidden" name="id" value="<?= (int) $player["id"] ?>">
        <button type="submit" class="btn-confirm-delete">Sí, eliminar</button>
        <a href="user.php" class="btn-cancel">Cancelar</a>
    </form>
</div>

<?php include __DIR__ . '/php/footer.php'; ?>
