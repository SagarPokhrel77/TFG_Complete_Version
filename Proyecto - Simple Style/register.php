<?php
include("php/DataBase.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user = trim($_POST["user"]);
    $password = trim($_POST["password"]);

    // Verificar si el usuario ya existe
    $check = "SELECT * FROM usuarios WHERE user = ?";
    $stmt = $conn->prepare($check);
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

        $error = "El usuario ya existe";

    } else {

        // Rol por defecto = 3 (usuario normal)
        $rol = 3;

        $sql = "INSERT INTO usuarios (user, pass, rol) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $user, $password, $rol);

        if ($stmt->execute()) {

            header("Location: login.php");
            exit();

        } else {

            $error = "Error al registrar usuario";

        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Registro</title>
<link rel="stylesheet" href="CSS/login.css">
</head>

<body>

<div class="navbar">⚽ Elite Players</div>

<div class="login-container">

    <h2>Crear Cuenta</h2>

    <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>

    <form method="POST">

        <input type="text" name="user" placeholder="Usuario" required>

        <input type="password" name="password" placeholder="Contraseña" required>

        <button type="submit">Registrarse</button>

    </form>

    <br>

    <a href="login.php">
        <button type="button">Volver al Login</button>
    </a>

</div>

</body>
</html>