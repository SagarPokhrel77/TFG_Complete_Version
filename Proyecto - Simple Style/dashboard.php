<?php
session_start();
include("php/DataBase.php");

/* =========================
   PROTEGER DASHBOARD
========================= */

if (!isset($_SESSION["user_id"]) || $_SESSION["rol"] == 3) {

    header("Location: login.php");
    exit();
}

$basePath = "uploads/";

/* =========================
   AÑADIR JUGADOR
========================= */

if (isset($_POST["add"])) {

    $nombre   = $_POST["nombre"];
    $edad     = $_POST["edad"];
    $posicion = $_POST["posicion"];
    $equipo   = $_POST["equipo"];
    $rating   = $_POST["rating"];

    $foto = "uploads/default.png";

    // Subir imagen
    if (!empty($_FILES["foto"]["name"])) {

        $fileName = time() . "_" . $_FILES["foto"]["name"];
        $path = $basePath . $fileName;

        move_uploaded_file($_FILES["foto"]["tmp_name"], $path);

        $foto = $path;
    }

    // Guardar jugador
    $stmt = $conn->prepare("
        INSERT INTO jugadores
        (nombre,edad,posicion,equipo,foto,rating)
        VALUES(?,?,?,?,?,?)
    ");

    $stmt->bind_param(
        "sisssi",
        $nombre,
        $edad,
        $posicion,
        $equipo,
        $foto,
        $rating
    );

    $stmt->execute();

    header("Location: dashboard.php");
    exit();
}

/* =========================
   EDITAR JUGADOR
========================= */

if (isset($_POST["update"])) {

    $id       = $_POST["id"];
    $nombre   = $_POST["nombre"];
    $edad     = $_POST["edad"];
    $posicion = $_POST["posicion"];
    $equipo   = $_POST["equipo"];
    $rating   = $_POST["rating"];

    // Obtener foto actual
    $stmt = $conn->prepare("SELECT foto FROM jugadores WHERE id=?");

    $stmt->bind_param("i", $id);

    $stmt->execute();

    $old = $stmt->get_result()->fetch_assoc();

    $foto = $old["foto"];

    // Nueva imagen
    if (!empty($_FILES["foto"]["name"])) {

        $fileName = time() . "_" . $_FILES["foto"]["name"];

        $path = $basePath . $fileName;

        move_uploaded_file($_FILES["foto"]["tmp_name"], $path);

        $foto = $path;
    }

    // Actualizar
    $stmt = $conn->prepare("
        UPDATE jugadores
        SET nombre=?,edad=?,posicion=?,equipo=?,foto=?,rating=?
        WHERE id=?
    ");

    $stmt->bind_param(
        "sisssii",
        $nombre,
        $edad,
        $posicion,
        $equipo,
        $foto,
        $rating,
        $id
    );

    $stmt->execute();

    header("Location: dashboard.php");
    exit();
}

/* =========================
   ELIMINAR JUGADOR
========================= */

if (isset($_POST["delete_id"])) {

    $id = $_POST["delete_id"];

    $stmt = $conn->prepare("DELETE FROM jugadores WHERE id=?");

    $stmt->bind_param("i", $id);

    $stmt->execute();

    header("Location: dashboard.php");
    exit();
}

/* =========================
   AÑADIR PARTIDO
========================= */

if (isset($_POST["add_match"])) {

    $local      = $_POST["equipo_local"];
    $visitante  = $_POST["equipo_visitante"];
    $golesL     = $_POST["goles_local"];
    $golesV     = $_POST["goles_visitante"];
    $fecha      = $_POST["fecha"];
    $estado     = $_POST["estado"];

    $resultado = "$golesL - $golesV";

    $stmt = $conn->prepare("
        INSERT INTO partidos
        (equipo_local,equipo_visitante,goles_local,goles_visitante,fecha,estado,resultado)
        VALUES(?,?,?,?,?,?,?)
    ");

    $stmt->bind_param(
        "ssiisss",
        $local,
        $visitante,
        $golesL,
        $golesV,
        $fecha,
        $estado,
        $resultado
    );

    $stmt->execute();

    header("Location: dashboard.php");
    exit();
}

/* =========================
   ELIMINAR PARTIDO
========================= */

if (isset($_POST["delete_match_id"])) {

    $stmt = $conn->prepare("DELETE FROM partidos WHERE id=?");

    $stmt->bind_param("i", $_POST["delete_match_id"]);

    $stmt->execute();

    header("Location: dashboard.php");
    exit();
}

/* =========================
   CONSULTAS
========================= */

$players = $conn->query("SELECT * FROM jugadores");

$matches = $conn->query("
    SELECT * FROM partidos
    ORDER BY fecha DESC
");
?>

<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">

<title>Dashboard</title>

<link rel="stylesheet" href="CSS/dashboard.css">

</head>

<body>

<!-- =========================
     NAVBAR
========================= -->

<div class="navbar">

    <div class="nav-left">

        <img src="uploads/logo.png" class="logo">

        <span>Dashboard</span>

    </div>

    <div class="profile-dropdown">

        <p>
            <strong>Usuario:</strong>
            <?= $_SESSION["user"] ?>
        </p>

        <p>
            <strong>Rol:</strong>

            <?= $_SESSION["rol"] == 1
                ? "Administrador"
                : "Editor"
            ?>
        </p>

        <a href="logout.php" class="logout-btn">
            Cerrar Sesión
        </a>

    </div>

</div>

<!-- =========================
     CONTENEDOR
========================= -->

<div class="container">

    <!-- SIDEBAR -->

    <div class="sidebar">

        <a href="#jugadores" class="nav-link">
            Jugadores
        </a>

        <a href="#partidos" class="nav-link">
            Partidos
        </a>

        <button onclick="openAdd()">
            Añadir jugador
        </button>

        <button onclick="openMatch()">
            Añadir partido
        </button>

    </div>

    <!-- CONTENIDO -->

    <div class="content">

        <!-- BOTONES -->

        <div class="scroll-buttons">

            <button onclick="goTop()">
                Inicio
            </button>

            <button onclick="goBottom()">
                Final
            </button>

        </div>

        <!-- =========================
             JUGADORES
        ========================= -->

        <div class="card" id="jugadores">

            <h2>Jugadores</h2>

            <table>

                <tr>

                    <th>Foto</th>
                    <th>Nombre</th>
                    <th>Edad</th>
                    <th>Posición</th>
                    <th>Equipo</th>
                    <th>Rating</th>
                    <th>Acciones</th>

                </tr>

                <?php while($r = $players->fetch_assoc()): ?>

                <tr>

                    <td>
                        <img src="<?= $r["foto"] ?>" class="img">
                    </td>

                    <td><?= $r["nombre"] ?></td>

                    <td><?= $r["edad"] ?></td>

                    <td><?= $r["posicion"] ?></td>

                    <td><?= $r["equipo"] ?></td>

                    <td>⭐ <?= $r["rating"] ?></td>

                    <td>

                        <!-- EDITAR -->

                        <button onclick="openEdit(
                        '<?= $r['id'] ?>',
                        '<?= htmlspecialchars($r['nombre'], ENT_QUOTES) ?>',
                        '<?= $r['edad'] ?>',
                        '<?= htmlspecialchars($r['posicion'], ENT_QUOTES) ?>',
                        '<?= htmlspecialchars($r['equipo'], ENT_QUOTES) ?>',
                        '<?= $r['rating'] ?>'
                        )">
                            ✏️
                        </button>

                        <!-- ELIMINAR -->

                        <form method="POST" style="display:inline;">

                            <input
                                type="hidden"
                                name="delete_id"
                                value="<?= $r["id"] ?>"
                            >

                            <button class="danger">
                                🗑️
                            </button>

                        </form>

                    </td>

                </tr>

                <?php endwhile; ?>

            </table>

        </div>

        <!-- =========================
             PARTIDOS
        ========================= -->

        <div class="card" id="partidos">

            <h2>Partidos</h2>

            <table>

                <tr>

                    <th>Local</th>
                    <th>Resultado</th>
                    <th>Visitante</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Acciones</th>

                </tr>

                <?php while($p = $matches->fetch_assoc()): ?>

                <tr>

                    <td><?= $p["equipo_local"] ?></td>

                    <td class="resultado">

                        <?= $p["goles_local"] ?>
                        -
                        <?= $p["goles_visitante"] ?>

                    </td>

                    <td><?= $p["equipo_visitante"] ?></td>

                    <td><?= $p["fecha"] ?></td>

                    <td>

                        <span class="estado">

                            <?= $p["estado"] ?>

                        </span>

                    </td>

                    <td>

                        <form method="POST">

                            <input
                                type="hidden"
                                name="delete_match_id"
                                value="<?= $p["id"] ?>"
                            >

                            <button class="danger">
                                🗑️
                            </button>

                        </form>

                    </td>

                </tr>

                <?php endwhile; ?>

            </table>

        </div>

    </div>

</div>

<!-- =========================
     MODAL AÑADIR JUGADOR
========================= -->

<div id="addModal" class="modal">

    <div class="modal-content">

        <h3>Añadir Jugador</h3>

        <form method="POST" enctype="multipart/form-data">

            <input name="nombre" placeholder="Nombre">

            <input
                name="edad"
                type="number"
                placeholder="Edad"
            >

            <input
                name="posicion"
                placeholder="Posición"
            >

            <input
                name="equipo"
                placeholder="Equipo"
            >

            <input
                name="rating"
                type="number"
                placeholder="Rating"
            >

            <input type="file" name="foto">

            <button name="add">
                Guardar
            </button>

        </form>

        <button onclick="closeModals()">
            Cerrar
        </button>

    </div>

</div>

<!-- =========================
     MODAL EDITAR JUGADOR
========================= -->

<div id="editModal" class="modal">

    <div class="modal-content">

        <h3>Editar Jugador</h3>

        <form method="POST" enctype="multipart/form-data">

            <input type="hidden" name="id" id="edit_id">

            <input
                name="nombre"
                id="edit_nombre"
                placeholder="Nombre"
            >

            <input
                name="edad"
                id="edit_edad"
                type="number"
                placeholder="Edad"
            >

            <input
                name="posicion"
                id="edit_posicion"
                placeholder="Posición"
            >

            <input
                name="equipo"
                id="edit_equipo"
                placeholder="Equipo"
            >

            <input
                name="rating"
                id="edit_rating"
                type="number"
                placeholder="Rating"
            >

            <input type="file" name="foto">

            <button name="update">
                Actualizar
            </button>

        </form>

        <button onclick="closeModals()">
            Cerrar
        </button>

    </div>

</div>

<!-- =========================
     MODAL PARTIDOS
========================= -->

<div id="matchModal" class="modal">

    <div class="modal-content">

        <h3>Añadir Partido</h3>

<form method="POST" enctype="multipart/form-data">

    <input name="equipo_local" placeholder="Equipo Local">

    <input type="file" name="logo_local">

    <input name="equipo_visitante" placeholder="Equipo Visitante">

    <input type="file" name="logo_visitante">

    <input
        name="goles_local"
        type="number"
        placeholder="Goles Local"
    >

    <input
        name="goles_visitante"
        type="number"
        placeholder="Goles Visitante"
    >

    <select name="tipo">
        <option value="Ida">Ida</option>
        <option value="Vuelta">Vuelta</option>
    </select>

    <input
        name="fecha"
        type="datetime-local"
    >

    <select name="estado">
        <option>Pendiente</option>
        <option>En juego</option>
        <option>Finalizado</option>
    </select>

    <button name="add_match">
        Guardar
    </button>

</form>

        <button onclick="closeModals()">
            Cerrar
        </button>

    </div>

</div>

<!-- =========================
     JAVASCRIPT
========================= -->

<script>

// Abrir modal jugador
function openAdd(){

    document.getElementById("addModal").style.display = "flex";
}

// Abrir modal partido
function openMatch(){

    document.getElementById("matchModal").style.display = "flex";
}

// Abrir modal editar
function openEdit(id,nombre,edad,posicion,equipo,rating){

    document.getElementById("edit_id").value = id;
    document.getElementById("edit_nombre").value = nombre;
    document.getElementById("edit_edad").value = edad;
    document.getElementById("edit_posicion").value = posicion;
    document.getElementById("edit_equipo").value = equipo;
    document.getElementById("edit_rating").value = rating;

    document.getElementById("editModal").style.display = "flex";
}

// Cerrar modales
function closeModals(){

    document.querySelectorAll(".modal").forEach(m => {

        m.style.display = "none";
    });
}

// Ir arriba
function goTop(){

    window.scrollTo({

        top:0,
        behavior:"smooth"
    });
}

// Ir abajo
function goBottom(){

    window.scrollTo({

        top:document.body.scrollHeight,
        behavior:"smooth"
    });
}

</script>

</body>
</html>