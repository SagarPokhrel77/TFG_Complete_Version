<?php

require_once __DIR__ . '/php/init.php';
requireLogin();
require_once __DIR__ . '/php/empty-state.php';



/* ================= PONER EN VENTA (POST) ================= */

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["sell_player"])) {



    $id = intval($_POST["jugador_id"] ?? 0);

    $precio = trim($_POST["precio"] ?? "");



    if ($id <= 0 || $precio === "") {

        setFlash("error", "Debes indicar un precio válido para la venta.");

        header("Location: user.php");

        exit;

    }



    $stmt = $conn->prepare("SELECT * FROM jugadores WHERE id = ?");

    $stmt->bind_param("i", $id);

    $stmt->execute();

    $player = $stmt->get_result()->fetch_assoc();



    if (!$player) {

        setFlash("error", "Jugador no encontrado.");

        header("Location: user.php");

        exit;

    }



    $check = $conn->prepare("

        SELECT id FROM transferencias

        WHERE jugador = ? AND estado = 'En venta'

        LIMIT 1

    ");

    $check->bind_param("s", $player["nombre"]);

    $check->execute();



    if ($check->get_result()->num_rows > 0) {

        setFlash("error", $player["nombre"] . " ya está en venta.");

        header("Location: user.php");

        exit;

    }



    $ins = $conn->prepare("

        INSERT INTO transferencias

        (jugador, posicion, rating, foto_jugador, club_origen, club_destino, logo_origen, logo_destino, precio, fecha, estado)

        VALUES (?, ?, ?, ?, ?, 'Libre', ?, 'uploads/default.png', ?, CURDATE(), 'En venta')

    ");



    $club = $player["equipo"];



    $ins->bind_param(

        "ssissss",

        $player["nombre"],

        $player["posicion"],

        $player["rating"],

        $player["foto"],

        $club,

        $player["foto"],

        $precio

    );



    $ins->execute();



    setFlash("success", $player["nombre"] . " se ha puesto en venta por " . $precio . ".");

    header("Location: transferencias.php");

    exit;

}



/* ================= JUGADORES ================= */

$result = $conn->query("SELECT * FROM jugadores");



$clubsList = [];

$clubsResult = $conn->query("SELECT DISTINCT equipo FROM jugadores ORDER BY equipo ASC");

while ($club = $clubsResult->fetch_assoc()) {

    $clubsList[] = $club["equipo"];

}



/* Jugadores ya en venta */

$enVenta = [];

$saleQ = $conn->query("SELECT jugador FROM transferencias WHERE estado = 'En venta'");

while ($sale = $saleQ->fetch_assoc()) {

    $enVenta[$sale["jugador"]] = true;

}



$isAdmin = isAdmin();
$activePage = "inicio";

$players = [];
while ($row = $result->fetch_assoc()) {
    $players[] = $row;
}

$pageTitle = "Elite Players";
include __DIR__ . '/php/header.php';
?>

<?php include("php/navbar.php"); ?>

<?php renderFlash(); ?>



<div class="navbar-filters">

    <input type="text" id="search" placeholder="Buscar jugador...">



    <select id="filterClub">

        <option value="">Todos los clubes</option>

        <?php foreach ($clubsList as $equipo): ?>

            <option value="<?= htmlspecialchars($equipo) ?>"><?= htmlspecialchars($equipo) ?></option>

        <?php endforeach; ?>

    </select>



    <select id="filterPosition">

        <option value="">Todas las posiciones</option>

        <option value="Portero">Portero</option>

        <option value="Defensa">Defensa</option>

        <option value="Centrocampista">Centrocampista</option>

        <option value="Delantero">Delantero</option>

        <option value="Extremo">Extremo</option>

    </select>



    <select id="filterRating">

        <option value="0">Todos los ratings</option>

        <option value="90">Rating 90+</option>

        <option value="85">Rating 85+</option>

        <option value="80">Rating 80+</option>

        <option value="75">Rating 75+</option>

        <option value="70">Rating 70+</option>

    </select>

</div>



<div class="hero hero-admin">

    <h1>Todos los Jugadores</h1>

    <?php if ($isAdmin): ?>
        <a href="nuevo_jugador.php" class="btn-new-player btn-with-icon"><?= icon('add', 18) ?> Nuevo jugador</a>
    <?php endif; ?>

</div>



<div class="container">

<div class="grid" id="players">

<?php if (count($players) === 0): ?>
    <?php renderEmptyState(
        'inbox',
        'No hay jugadores',
        'Aún no hay jugadores en la base de datos.',
        $isAdmin ? 'nuevo_jugador.php' : null,
        $isAdmin ? 'Añadir primer jugador' : null
    ); ?>
<?php endif; ?>

<?php foreach ($players as $r): ?>

<?php $yaEnVenta = isset($enVenta[$r["nombre"]]); ?>



<div class="card player"

     data-name="<?= strtolower($r["nombre"]) ?>"

     data-club="<?= htmlspecialchars($r["equipo"], ENT_QUOTES) ?>"

     data-position="<?= htmlspecialchars($r["posicion"], ENT_QUOTES) ?>"

     data-rating="<?= (int) $r["rating"] ?>">



    <div class="rank"><?= icon('star', 16) ?> <?= $r["rating"] ?>/99</div>



    <?php if ($isAdmin): ?>

    <div class="card-actions">

        <a href="editar_jugador.php?id=<?= (int) $r['id'] ?>" class="btn-card btn-edit" title="Editar"><?= icon('edit', 18) ?></a>

        <a href="eliminar_jugador.php?id=<?= (int) $r['id'] ?>" class="btn-card btn-delete" title="Eliminar"><?= icon('delete', 18) ?></a>

    </div>

    <?php endif; ?>



    <div class="img-container">

        <img src="<?= htmlspecialchars($r["foto"]) ?>" class="player-img" alt="">

    </div>



    <h3><?= htmlspecialchars($r["nombre"]) ?></h3>

    <p><?= htmlspecialchars($r["equipo"]) ?></p>

    <p><?= htmlspecialchars($r["posicion"]) ?></p>



    <a href="player.php?id=<?= (int) $r['id'] ?>">

        <button type="button" class="profile-btn">Ver perfil</button>

    </a>



    <?php if ($yaEnVenta): ?>

        <button type="button" class="profile-btn btn-sell-disabled btn-with-icon" disabled title="Ya está en el mercado">

            <?= icon('check', 18) ?> Ya en venta

        </button>

    <?php else: ?>

        <button type="button"

            class="profile-btn btn-sell btn-with-icon"

            data-id="<?= (int) $r['id'] ?>"

            data-nombre="<?= htmlspecialchars($r['nombre'], ENT_QUOTES) ?>"

            data-precio="<?= htmlspecialchars($r['valor_mercado'] ?? '', ENT_QUOTES) ?>">

            <?= icon('money', 18) ?> Poner en venta

        </button>

    <?php endif; ?>



</div>



<?php endforeach; ?>



</div>

</div>



<!-- Modal venta -->

<div id="sellModal" class="modal-overlay" aria-hidden="true">

    <div class="modal-sell">

        <h3 class="btn-with-icon"><?= icon('money', 22) ?> Poner en venta</h3>

        <p class="modal-player-name" id="sellPlayerName"></p>



        <form method="POST" action="user.php" id="sellForm">

            <input type="hidden" name="sell_player" value="1">

            <input type="hidden" name="jugador_id" id="sellPlayerId" value="">



            <label for="sellPrecio">Valor de la transferencia / venta</label>

            <input type="text" name="precio" id="sellPrecio" placeholder="Ej: 15M€" required>



            <div class="modal-actions">

                <button type="submit" class="profile-btn btn-confirm-sell">Confirmar venta</button>

                <button type="button" class="btn-cancel-sell" id="sellCancel">Cancelar</button>

            </div>

        </form>

    </div>

</div>



<script>

const search = document.getElementById("search");

const filterClub = document.getElementById("filterClub");

const filterPosition = document.getElementById("filterPosition");

const filterRating = document.getElementById("filterRating");

const players = document.querySelectorAll(".player");



function filterPlayers() {

    const text = search.value.toLowerCase();

    const club = filterClub.value;

    const pos = filterPosition.value;

    const minRating = parseInt(filterRating.value, 10) || 0;



    players.forEach(p => {

        const rating = parseInt(p.dataset.rating, 10) || 0;

        const show =

            p.dataset.name.includes(text) &&

            (club === "" || p.dataset.club === club) &&

            (pos === "" || p.dataset.position === pos) &&

            rating >= minRating;



        p.style.display = show ? "block" : "none";

    });

}



search.addEventListener("keyup", filterPlayers);

filterClub.addEventListener("change", filterPlayers);

filterPosition.addEventListener("change", filterPlayers);

filterRating.addEventListener("change", filterPlayers);



const sellModal = document.getElementById("sellModal");

const sellForm = document.getElementById("sellForm");

const sellPlayerId = document.getElementById("sellPlayerId");

const sellPlayerName = document.getElementById("sellPlayerName");

const sellPrecio = document.getElementById("sellPrecio");

const sellCancel = document.getElementById("sellCancel");



document.querySelectorAll(".btn-sell").forEach(btn => {

    btn.addEventListener("click", () => {

        sellPlayerId.value = btn.dataset.id;

        sellPlayerName.textContent = btn.dataset.nombre;

        sellPrecio.value = btn.dataset.precio || "";

        sellModal.classList.add("open");

        sellModal.setAttribute("aria-hidden", "false");

        sellPrecio.focus();

    });

});



function closeSellModal() {

    sellModal.classList.remove("open");

    sellModal.setAttribute("aria-hidden", "true");

    sellForm.reset();

}



sellCancel.addEventListener("click", closeSellModal);

sellModal.addEventListener("click", (e) => {

    if (e.target === sellModal) closeSellModal();

});



</script>

<?php include __DIR__ . '/php/footer.php'; ?>


