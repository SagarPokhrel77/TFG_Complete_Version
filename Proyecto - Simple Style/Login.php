<?php
session_start();
include("php/DataBase.php");

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

            // 🔥 guardar sesión
            $_SESSION["user_id"] = $row["id"];
            $_SESSION["user"] = $row["user"];
            $_SESSION["rol"] = $row["rol"];

            // 🔥 redirección segura por rol
            if ($row["rol"] == 1 || $row["rol"] == 2) {
                header("Location: dashboard.php");
            } else {
                header("Location: user.php");
            }

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

<div class="navbar">⚽ Elite Players</div>

<div class="login-container">
    <h2>Iniciar Sesión</h2>

    <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>

    <form method="POST">
        <input type="text" name="user" placeholder="Usuario" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <button type="submit">Entrar</button>

        <!-- BOTÓN REGISTRO -->
        <a href="register.php">
            <button type="button" class="register-btn">Registrarse</button>
        </a>

    <div class="footer">© 2026 - Cristian and Sagar</div>
    </form>
</div>

</body>
</html>