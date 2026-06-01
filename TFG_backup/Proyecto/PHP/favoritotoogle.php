<?php
require_once __DIR__ . "/encoding.php";

session_start();
include("../php/DataBase.php");

if (!isset($_SESSION["user_id"])) {
    die("Debes iniciar sesión");
}

$user_id = $_SESSION["user_id"];
$jugador_id = intval($_POST["jugador_id"]);

$redirect = $_POST["redirect"] ?? "player";

/* CHECK */
$check = $conn->prepare("
    SELECT id 
    FROM favoritos 
    WHERE user_id=? AND jugador_id=?
");

$check->bind_param("ii", $user_id, $jugador_id);
$check->execute();
$res = $check->get_result();

if ($res->num_rows > 0) {

    $del = $conn->prepare("
        DELETE FROM favoritos 
        WHERE user_id=? AND jugador_id=?
    ");
    $del->bind_param("ii", $user_id, $jugador_id);
    $del->execute();

} else {

    $ins = $conn->prepare("
        INSERT INTO favoritos(user_id, jugador_id)
        VALUES (?,?)
    ");
    $ins->bind_param("ii", $user_id, $jugador_id);
    $ins->execute();
}

/* REDIRECT INTELIGENTE */
if ($redirect == "favorito") {
    header("Location: ../favorito.php");
} else {
    header("Location: ../player.php?id=" . $jugador_id);
}

exit();
?>