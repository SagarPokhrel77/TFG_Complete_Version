<?php

session_start();

include("php/DataBase.php");

/* VERIFICAR ID */

if(isset($_POST["id"])) {

    $id = intval($_POST["id"]);

    /* ELIMINAR */

    $stmt = $conn->prepare("
        DELETE FROM transferencias
        WHERE id = ?
    ");

    $stmt->bind_param("i", $id);

    $stmt->execute();
}

/* VOLVER */

header("Location: transferencias.php");

exit;

?>