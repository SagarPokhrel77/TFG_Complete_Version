<?php
// Asegúrate de que la sesión esté iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Usuario por defecto si no hay sesión
$usuario = isset($_SESSION["usuario"]) ? $_SESSION["usuario"] : "Invitado";

// Fecha y hora actual
$fechaHora = date('Y-m-d H:i:s');
?>

<style>
footer {
    background-color: #222; /* Fondo oscuro */
    color: #fff;            /* Texto blanco */
    text-align: center;     /* Centrado */
    padding: 15px 0;        /* Espaciado */
    font-family: Arial, sans-serif;
    font-size: 14px;
}
</style>

<footer>
    Designed by: @<?= htmlspecialchars($usuario) ?> | <?= $fechaHora ?>
</footer>