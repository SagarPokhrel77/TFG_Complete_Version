<?php
require_once __DIR__ . '/php/init.php';
require_once __DIR__ . '/php/upload_image.php';
requireAdmin();

$activePage = "gestionar_partidos";
$basePath = "uploads/";

/**
 * @return array{ok: bool, path: ?string, error: string}
 */
function saveUpload(string $field, string $basePath): array
{
    if (!isset($_FILES[$field])) {
        return ['ok' => true, 'path' => null, 'error' => ''];
    }
    return saveValidatedImageUpload($_FILES[$field], $basePath, $field);
}

/* ================= CRUD PARTIDOS ================= */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (isset($_POST["add_match"])) {
        $local = trim($_POST["equipo_local"] ?? "");
        $visit = trim($_POST["equipo_visitante"] ?? "");
        $gL = intval($_POST["goles_local"] ?? 0);
        $gV = intval($_POST["goles_visitante"] ?? 0);
        $fecha = trim($_POST["fecha"] ?? "");
        $estadio = trim($_POST["estadio"] ?? "");
        $estado = trim($_POST["estado"] ?? "Pendiente");

        $upLocal = saveUpload("logo_local", $basePath);
        if (!$upLocal['ok']) {
            setFlash("error", $upLocal['error']);
            header("Location: gestionar_partidos.php");
            exit();
        }
        $upVisit = saveUpload("logo_visitante", $basePath);
        if (!$upVisit['ok']) {
            setFlash("error", $upVisit['error']);
            header("Location: gestionar_partidos.php");
            exit();
        }
        $logoLocal = $upLocal['path'];
        $logoVisit = $upVisit['path'];

        $resultado = $gL . " - " . $gV;

        $stmt = $conn->prepare("
            INSERT INTO partidos
            (equipo_local, equipo_visitante, goles_local, goles_visitante, fecha, estadio, estado, logo_local, logo_visitante, resultado)
            VALUES (?,?,?,?,?,?,?,?,?,?)
        ");
        $stmt->bind_param(
            "ssiissssss",
            $local,
            $visit,
            $gL,
            $gV,
            $fecha,
            $estadio,
            $estado,
            $logoLocal,
            $logoVisit,
            $resultado
        );
        $stmt->execute();

        setFlash("success", "Partido creado correctamente.");
        header("Location: gestionar_partidos.php");
        exit();
    }

    if (isset($_POST["update_match"])) {
        $id = intval($_POST["match_id"] ?? 0);
        $local = trim($_POST["equipo_local"] ?? "");
        $visit = trim($_POST["equipo_visitante"] ?? "");
        $gL = intval($_POST["goles_local"] ?? 0);
        $gV = intval($_POST["goles_visitante"] ?? 0);
        $fecha = trim($_POST["fecha"] ?? "");
        $estadio = trim($_POST["estadio"] ?? "");
        $estado = trim($_POST["estado"] ?? "Pendiente");

        $stmt = $conn->prepare("SELECT logo_local, logo_visitante FROM partidos WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $old = $stmt->get_result()->fetch_assoc();

        if (!$old) {
            setFlash("error", "Partido no encontrado.");
            header("Location: gestionar_partidos.php");
            exit();
        }

        $logoLocal = $old["logo_local"];
        $logoVisit = $old["logo_visitante"];

        $newLocal = saveUpload("logo_local", $basePath);
        if (!$newLocal['ok']) {
            setFlash("error", $newLocal['error']);
            header("Location: gestionar_partidos.php");
            exit();
        }
        $newVisit = saveUpload("logo_visitante", $basePath);
        if (!$newVisit['ok']) {
            setFlash("error", $newVisit['error']);
            header("Location: gestionar_partidos.php");
            exit();
        }

        if ($newLocal['path']) {
            $logoLocal = $newLocal['path'];
        }
        if ($newVisit['path']) {
            $logoVisit = $newVisit['path'];
        }

        $resultado = $gL . " - " . $gV;

        $stmt = $conn->prepare("
            UPDATE partidos
            SET equipo_local=?, equipo_visitante=?, goles_local=?, goles_visitante=?, fecha=?, estadio=?, estado=?, logo_local=?, logo_visitante=?, resultado=?
            WHERE id=?
        ");
        $stmt->bind_param(
            "ssiissssssi",
            $local,
            $visit,
            $gL,
            $gV,
            $fecha,
            $estadio,
            $estado,
            $logoLocal,
            $logoVisit,
            $resultado,
            $id
        );
        $stmt->execute();

        setFlash("success", "Partido actualizado correctamente.");
        header("Location: gestionar_partidos.php");
        exit();
    }

    if (isset($_POST["delete_match_id"])) {
        $id = intval($_POST["delete_match_id"] ?? 0);
        $stmt = $conn->prepare("DELETE FROM partidos WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        setFlash("success", "Partido eliminado correctamente.");
        header("Location: gestionar_partidos.php");
        exit();
    }
}

$clubs = [];
$clubsQ = $conn->query("SELECT nombre FROM clubes ORDER BY nombre ASC");
while ($c = $clubsQ->fetch_assoc()) {
    $clubs[] = $c["nombre"];
}

$matches = $conn->query("SELECT * FROM partidos ORDER BY fecha DESC");

$pageTitle = "Gestionar Partidos";
$extraStylesheets = ["CSS/gestionar_partidos.css"];
include __DIR__ . '/php/header.php';
?>

<?php include("php/navbar.php"); ?>
<?php renderFlash(); ?>

<div class="page-hero admin-page">
    <h1 class="btn-with-icon" style="justify-content:center;"><?= icon('settings', 28) ?> Gestionar partidos</h1>
</div>

<div class="container admin-page">
    <div class="admin-card">
        <h2><?= icon('add', 22) ?> Nuevo partido</h2>
        <form method="POST" enctype="multipart/form-data" class="admin-form-grid">
            <select name="equipo_local" required>
                <option value="">Equipo local</option>
                <?php foreach ($clubs as $club): ?>
                    <option value="<?= htmlspecialchars($club) ?>"><?= htmlspecialchars($club) ?></option>
                <?php endforeach; ?>
            </select>
            <select name="equipo_visitante" required>
                <option value="">Equipo visitante</option>
                <?php foreach ($clubs as $club): ?>
                    <option value="<?= htmlspecialchars($club) ?>"><?= htmlspecialchars($club) ?></option>
                <?php endforeach; ?>
            </select>
            <input type="number" name="goles_local" placeholder="Goles local" value="0">
            <input type="number" name="goles_visitante" placeholder="Goles visitante" value="0">
            <input type="datetime-local" name="fecha" required>
            <input type="text" name="estadio" placeholder="Estadio">
            <select name="estado">
                <option>Pendiente</option>
                <option>Finalizado</option>
                <option>En vivo</option>
            </select>
            <div>
                <label>Logo local</label>
                <input type="file" name="logo_local" accept="image/jpeg,image/png,image/gif,image/webp,.jpg,.jpeg,.png,.gif,.webp">
            </div>
            <div>
                <label>Logo visitante</label>
                <input type="file" name="logo_visitante" accept="image/jpeg,image/png,image/gif,image/webp,.jpg,.jpeg,.png,.gif,.webp">
            </div>
            <div class="span-full">
                <button type="submit" name="add_match" class="profile-btn btn-confirm-sell">Crear partido</button>
            </div>
        </form>
    </div>

    <div class="admin-card">
        <h2><?= icon('list', 22) ?> Partidos</h2>
        <div class="admin-table-wrap">
        <table>
            <tr>
                <th>ID</th>
                <th>Local</th>
                <th>Visitante</th>
                <th>Resultado</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
            <?php while ($p = $matches->fetch_assoc()): ?>
            <tr>
                <td><?= (int) $p["id"] ?></td>
                <td><?= htmlspecialchars($p["equipo_local"]) ?></td>
                <td><?= htmlspecialchars($p["equipo_visitante"]) ?></td>
                <td><?= (int) $p["goles_local"] ?> - <?= (int) $p["goles_visitante"] ?></td>
                <td><?= htmlspecialchars($p["fecha"]) ?></td>
                <td><?= htmlspecialchars($p["estado"]) ?></td>
                <td>
                    <details>
                        <summary><?= icon('edit', 16) ?> Editar</summary>
                        <form method="POST" enctype="multipart/form-data" class="admin-form-grid" style="margin-top:10px;">
                            <input type="hidden" name="match_id" value="<?= (int) $p["id"] ?>">
                            <input name="equipo_local" value="<?= htmlspecialchars($p["equipo_local"], ENT_QUOTES) ?>" required>
                            <input name="equipo_visitante" value="<?= htmlspecialchars($p["equipo_visitante"], ENT_QUOTES) ?>" required>
                            <input type="number" name="goles_local" value="<?= (int) $p["goles_local"] ?>">
                            <input type="number" name="goles_visitante" value="<?= (int) $p["goles_visitante"] ?>">
                            <input type="datetime-local" name="fecha" value="<?= htmlspecialchars(str_replace(" ", "T", $p["fecha"])) ?>" required>
                            <input name="estadio" value="<?= htmlspecialchars($p["estadio"] ?? "", ENT_QUOTES) ?>">
                            <select name="estado">
                                <?php foreach (["Pendiente","Finalizado","En vivo"] as $st): ?>
                                    <option <?= ($p["estado"] === $st) ? "selected" : "" ?>><?= $st ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div>
                                <label>Logo local</label>
                                <input type="file" name="logo_local" accept="image/jpeg,image/png,image/gif,image/webp,.jpg,.jpeg,.png,.gif,.webp">
                            </div>
                            <div>
                                <label>Logo visitante</label>
                                <input type="file" name="logo_visitante" accept="image/jpeg,image/png,image/gif,image/webp,.jpg,.jpeg,.png,.gif,.webp">
                            </div>
                            <div class="span-full">
                                <button type="submit" name="update_match" class="profile-btn">Guardar</button>
                            </div>
                        </form>
                    </details>

                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="delete_match_id" value="<?= (int) $p["id"] ?>">
                        <button type="submit" class="danger" title="Eliminar"><?= icon('delete', 18) ?></button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
        </div>
    </div>
</div>

<?php include __DIR__ . '/php/footer.php'; ?>

