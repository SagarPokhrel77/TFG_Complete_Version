<?php
require_once __DIR__ . '/php/encoding.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include("php/DataBase.php");
require_once __DIR__ . '/php/icons.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user = $_POST["user"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM usuarios WHERE user = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {

        if ($password == $row["pass"]) {

            $_SESSION["user_id"] = $row["id"];
            $_SESSION["user"] = $row["user"];
            $_SESSION["rol"] = $row["rol"];

            header("Location: user.php");
            exit();

        } else {
            $error = "Contraseña incorrecta";
        }

    } else {
        $error = "Usuario no encontrado";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Login</title>
<link rel="stylesheet" href="CSS/login.css">
</head>

<body>

<a href="user.php" class="navbar logo-link">
    <img src="uploads/elite-player-logo.png" alt="Elite Player" class="site-logo-img" width="48" height="48">
    <span class="logo-text">Elite Players</span>
</a>

<div class="login-container">
    <h2>Iniciar Sesión</h2>

    <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>

    <form method="POST">
        <input type="text" name="user" placeholder="Usuario" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <button type="submit">Entrar</button>
    </form>

    <div class="footer">© 2026 - Cristian and  Sagar</div>
</div>

</body>
</html>