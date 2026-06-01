<?php
session_start();
include("php/DataBase.php");

/* LOGIN */
if (!isset($_SESSION["user_id"]) || $_SESSION["rol"] == 3) {
    header("Location: login.php");
    exit();
}

/* CONFIG */
$basePath = "uploads/";

/* ================= ADD PLAYER ================= */

if (isset($_POST["add"])) {

    $nombre = $_POST["nombre"];
    $edad = $_POST["edad"];
    $posicion = $_POST["posicion"];
    $equipo = $_POST["equipo"];
    $rating = $_POST["rating"];

    $foto = "uploads/default.png";

    if(isset($_FILES["foto"]) && $_FILES["foto"]["error"] == 0){

        $fileName = time() . "_" . basename($_FILES["foto"]["name"]);
        $path = $basePath . $fileName;

        move_uploaded_file($_FILES["foto"]["tmp_name"], $path);

        $foto = $path;
    }

    $stmt = $conn->prepare("
        INSERT INTO jugadores
        (nombre, edad, posicion, equipo, foto, rating)
        VALUES (?,?,?,?,?,?)
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

/* ================= UPDATE PLAYER ================= */

if(isset($_POST["update"])){

    $id = $_POST["id"];
    $nombre = $_POST["nombre"];
    $edad = $_POST["edad"];
    $posicion = $_POST["posicion"];
    $equipo = $_POST["equipo"];
    $rating = $_POST["rating"];

    $stmt = $conn->prepare("
        SELECT foto
        FROM jugadores
        WHERE id=?
    ");

    $stmt->bind_param("i",$id);
    $stmt->execute();

    $old = $stmt->get_result()->fetch_assoc();

    $foto = $old["foto"];

    if(isset($_FILES["foto"]) && $_FILES["foto"]["error"] == 0){

        if($foto && file_exists($foto)){
            unlink($foto);
        }

        $fileName = time() . "_" . basename($_FILES["foto"]["name"]);
        $path = $basePath . $fileName;

        move_uploaded_file($_FILES["foto"]["tmp_name"], $path);

        $foto = $path;
    }

    $stmt = $conn->prepare("
        UPDATE jugadores
        SET nombre=?, edad=?, posicion=?, equipo=?, foto=?, rating=?
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

/* ================= DELETE PLAYER ================= */

if(isset($_POST["delete_id"])){

    $id = $_POST["delete_id"];

    $stmt = $conn->prepare("
        DELETE FROM jugadores
        WHERE id=?
    ");

    $stmt->bind_param("i",$id);
    $stmt->execute();

    header("Location: dashboard.php");
    exit();
}

/* ================= ADD MATCH ================= */

if(isset($_POST["add_match"])){

    $local = $_POST["equipo_local"];
    $visitante = $_POST["equipo_visitante"];

    $golesL = $_POST["goles_local"];
    $golesV = $_POST["goles_visitante"];

    $fecha = $_POST["fecha"];
    $estado = $_POST["estado"];

    $resultado = $golesL . " - " . $golesV;

    $logoLocal = "uploads/default.png";
    $logoVisitante = "uploads/default.png";

    if(isset($_FILES["logo_local"]) && $_FILES["logo_local"]["error"] == 0){

        $file = time() . "_" . basename($_FILES["logo_local"]["name"]);
        $path = $basePath . $file;

        move_uploaded_file($_FILES["logo_local"]["tmp_name"], $path);

        $logoLocal = $path;
    }

    if(isset($_FILES["logo_visitante"]) && $_FILES["logo_visitante"]["error"] == 0){

        $file = time() . "_" . basename($_FILES["logo_visitante"]["name"]);
        $path = $basePath . $file;

        move_uploaded_file($_FILES["logo_visitante"]["tmp_name"], $path);

        $logoVisitante = $path;
    }

    $stmt = $conn->prepare("
        INSERT INTO partidos
        (
            equipo_local,
            equipo_visitante,
            goles_local,
            goles_visitante,
            fecha,
            estado,
            logo_local,
            logo_visitante,
            resultado
        )
        VALUES (?,?,?,?,?,?,?,?,?)
    ");

    $stmt->bind_param(
        "ssiisssss",
        $local,
        $visitante,
        $golesL,
        $golesV,
        $fecha,
        $estado,
        $logoLocal,
        $logoVisitante,
        $resultado
    );

    $stmt->execute();

    header("Location: dashboard.php");
    exit();
}

/* ================= UPDATE MATCH ================= */

if(isset($_POST["update_match"])){

    $id = $_POST["match_id"];

    $local = $_POST["equipo_local"];
    $visitante = $_POST["equipo_visitante"];

    $golesL = $_POST["goles_local"];
    $golesV = $_POST["goles_visitante"];

    $fecha = $_POST["fecha"];
    $estado = $_POST["estado"];

    $resultado = $golesL . " - " . $golesV;

    $stmt = $conn->prepare("
        UPDATE partidos
        SET
        equipo_local=?,
        equipo_visitante=?,
        goles_local=?,
        goles_visitante=?,
        fecha=?,
        estado=?,
        resultado=?
        WHERE id=?
    ");

    $stmt->bind_param(
        "ssiisssi",
        $local,
        $visitante,
        $golesL,
        $golesV,
        $fecha,
        $estado,
        $resultado,
        $id
    );

    $stmt->execute();

    header("Location: dashboard.php#partidos");
    exit();
}

/* ================= DELETE MATCH ================= */

if(isset($_POST["delete_match_id"])){

    $id = $_POST["delete_match_id"];

    $stmt = $conn->prepare("
        DELETE FROM partidos
        WHERE id=?
    ");

    $stmt->bind_param("i",$id);
    $stmt->execute();

    header("Location: dashboard.php");
    exit();
}

/* DATA */

$result = $conn->query("
    SELECT *
    FROM jugadores
");

$matches = $conn->query("
    SELECT *
    FROM partidos
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

<div class="navbar">

    ⚽ Elite Players Dashboard

    <span>
        <?= $_SESSION["user"] ?>
    </span>

</div>

<div class="container">

<!-- SIDEBAR -->

<div class="sidebar">

    <a href="#jugadores" class="nav-link">
        👤 Jugadores
    </a>

    <a href="#partidos" class="nav-link">
        ⚽ Partidos
    </a>

    <button onclick="openAdd()">
        ➕ Añadir jugador
    </button>

    <button onclick="openMatch()">
        ⚽ Añadir partido
    </button>

</div>

<!-- CONTENT -->

<div class="content">

<!-- PLAYERS -->

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

<?php while($r = $result->fetch_assoc()): ?>

<tr>

<td>
<img src="<?= $r['foto'] ?>" class="player-table-img">
</td>

<td><?= $r["nombre"] ?></td>
<td><?= $r["edad"] ?></td>
<td><?= $r["posicion"] ?></td>
<td><?= $r["equipo"] ?></td>
<td>⭐ <?= $r["rating"] ?></td>

<td>

<button onclick="openEdit(
'<?= $r['id'] ?>',
'<?= $r['nombre'] ?>',
'<?= $r['edad'] ?>',
'<?= $r['posicion'] ?>',
'<?= $r['equipo'] ?>',
'<?= $r['rating'] ?>'
)">
✏️
</button>

<button onclick="openDelete(<?= $r['id'] ?>)">
🗑️
</button>

</td>

</tr>

<?php endwhile; ?>

</table>

</div>

<!-- MATCHES -->

<div class="card" id="partidos">

<h2>⚽ Partidos</h2>

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

<td class="team-cell">

<img
src="<?= !empty($p['logo_local']) ? $p['logo_local'] : 'uploads/default.png' ?>"
class="team-logo"
>

<?= $p["equipo_local"] ?>

</td>

<td class="resultado">

<?= $p["goles_local"] ?>
-
<?= $p["goles_visitante"] ?>

</td>

<td class="team-cell">

<img
src="<?= !empty($p['logo_visitante']) ? $p['logo_visitante'] : 'uploads/default.png' ?>"
class="team-logo"
>

<?= $p["equipo_visitante"] ?>

</td>

<td><?= $p["fecha"] ?></td>

<td>

<span class="estado">
<?= $p["estado"] ?>
</span>

</td>

<td>

<button onclick="openEditMatch(
'<?= $p['id'] ?>',
'<?= $p['equipo_local'] ?>',
'<?= $p['equipo_visitante'] ?>',
'<?= $p['goles_local'] ?>',
'<?= $p['goles_visitante'] ?>',
'<?= $p['fecha'] ?>',
'<?= $p['estado'] ?>'
)">
✏️
</button>

<form method="POST" style="display:inline;">

<input
type="hidden"
name="delete_match_id"
value="<?= $p['id'] ?>"
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

<!-- ADD PLAYER -->

<div id="addModal" class="modal">

<div class="modal-content">

<h3>Añadir jugador</h3>

<form method="POST" enctype="multipart/form-data">

<input name="nombre" placeholder="Nombre">

<input name="edad" type="number" placeholder="Edad">

<input name="posicion" placeholder="Posición">

<input name="equipo" placeholder="Equipo">

<input name="rating" type="number" placeholder="Rating">

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

<!-- EDIT PLAYER -->

<div id="editModal" class="modal">

<div class="modal-content">

<h3>Editar jugador</h3>

<form method="POST" enctype="multipart/form-data">

<input type="hidden" id="eid" name="id">

<input id="ename" name="nombre">

<input id="eage" name="edad">

<input id="epos" name="posicion">

<input id="eteam" name="equipo">

<input id="erating" name="rating">

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

<!-- DELETE -->

<div id="deleteModal" class="modal">

<div class="modal-content">

<h3>⚠️ Confirmar</h3>

<form method="POST">

<input
type="hidden"
id="delete_id"
name="delete_id"
>

<button class="danger">
Eliminar
</button>

</form>

<button onclick="closeModals()">
Cancelar
</button>

</div>

</div>

<!-- ADD MATCH -->

<div id="matchModal" class="modal">

<div class="modal-content">

<h3>⚽ Añadir partido</h3>

<form method="POST" enctype="multipart/form-data">

<input
name="equipo_local"
placeholder="Equipo local"
>

<input
name="equipo_visitante"
placeholder="Equipo visitante"
>

<input
name="goles_local"
type="number"
placeholder="Goles local"
>

<input
name="goles_visitante"
type="number"
placeholder="Goles visitante"
>

<input
name="fecha"
type="datetime-local"
>

<select name="estado">

<option>Pendiente</option>
<option>Finalizado</option>
<option>En juego</option>

</select>

<p>Logo local</p>

<input type="file" name="logo_local">

<p>Logo visitante</p>

<input type="file" name="logo_visitante">

<button name="add_match">
Guardar partido
</button>

</form>

<button onclick="closeModals()">
Cerrar
</button>

</div>

</div>

<!-- EDIT MATCH -->

<div id="editMatchModal" class="modal">

<div class="modal-content">

<h3>⚽ Editar partido</h3>

<form method="POST">

<input type="hidden" id="mid" name="match_id">

<input id="mlocal" name="equipo_local">

<input id="mvisit" name="equipo_visitante">

<input id="mgl" type="number" name="goles_local">

<input id="mgv" type="number" name="goles_visitante">

<input id="mfecha" type="datetime-local" name="fecha">

<select id="mestado" name="estado">

<option>Pendiente</option>
<option>Finalizado</option>
<option>En juego</option>

</select>

<button name="update_match">
Actualizar partido
</button>

</form>

<button onclick="closeModals()">
Cerrar
</button>

</div>

</div>

<script>

function openAdd(){
    document.getElementById("addModal").style.display="flex";
}

function openMatch(){
    document.getElementById("matchModal").style.display="flex";
}

function openEdit(id,n,a,p,t,r){

    document.getElementById("editModal").style.display="flex";

    document.getElementById("eid").value=id;
    document.getElementById("ename").value=n;
    document.getElementById("eage").value=a;
    document.getElementById("epos").value=p;
    document.getElementById("eteam").value=t;
    document.getElementById("erating").value=r;
}

function openDelete(id){

    document.getElementById("deleteModal").style.display="flex";

    document.getElementById("delete_id").value=id;
}

function openEditMatch(
id,
local,
visit,
gl,
gv,
fecha,
estado
){

    document.getElementById("editMatchModal")
    .style.display = "flex";

    document.getElementById("mid").value = id;
    document.getElementById("mlocal").value = local;
    document.getElementById("mvisit").value = visit;
    document.getElementById("mgl").value = gl;
    document.getElementById("mgv").value = gv;

    document.getElementById("mfecha").value =
    fecha.replace(" ","T");

    document.getElementById("mestado").value =
    estado;
}

function closeModals(){

    document.querySelectorAll(".modal").forEach(m=>{

        m.style.display="none";

    });
}

</script>

</body>
</html>