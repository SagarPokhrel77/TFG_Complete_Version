<?php
require_once __DIR__ . "/encoding.php";

$host = "localhost";
$user = "sagar";
$password = "123";
$db = "futbol";

$conn = new mysqli($host, $user, $password, $db);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

if (!$conn->set_charset("utf8mb4")) {
    die("Error al configurar UTF-8: " . $conn->error);
}

$conn->query("SET NAMES utf8mb4 COLLATE utf8mb4_general_ci");
