<?php
$host = "localhost";
$user = "sagar";
$password = "123";
$db = "futbol";

$conn = new mysqli($host, $user, $password, $db);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>