<?php
/**
 * Reimporta la BD con codificación UTF-8 correcta.
 * Ejecutar una vez: php tools/reimport_utf8.php
 */
$sqlFile = dirname(__DIR__, 2) . "/FutBol Base de Datos.sql";

if (!file_exists($sqlFile)) {
    die("No se encuentra el archivo SQL.\n");
}

$host = "localhost";
$user = "root";
$password = "";

$conn = new mysqli($host, $user, $password);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error . "\n");
}

$conn->set_charset("utf8mb4");
$conn->query("DROP DATABASE IF EXISTS futbol");
$conn->query("CREATE DATABASE futbol CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
$conn->select_db("futbol");
$conn->query("SET NAMES utf8mb4 COLLATE utf8mb4_general_ci");

$sql = file_get_contents($sqlFile);
if ($sql === false) {
    die("No se pudo leer el SQL.\n");
}

if (!$conn->multi_query($sql)) {
    die("Error importando: " . $conn->error . "\n");
}

do {
    if ($result = $conn->store_result()) {
        $result->free();
    }
} while ($conn->more_results() && $conn->next_result());

if ($conn->error) {
    die("Error durante importación: " . $conn->error . "\n");
}

$conn->query("CREATE USER IF NOT EXISTS 'sagar'@'localhost' IDENTIFIED BY '123'");
$conn->query("GRANT ALL PRIVILEGES ON futbol.* TO 'sagar'@'localhost'");
$conn->query("FLUSH PRIVILEGES");

$test = $conn->query("SELECT observaciones FROM jugadores WHERE id = 1")->fetch_assoc();
echo "Importación OK.\n";
echo "Prueba: " . $test["observaciones"] . "\n";
