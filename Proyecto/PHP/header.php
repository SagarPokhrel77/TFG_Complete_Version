<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit();
}

// Ruta correcta a texto.py
$python_file = 'C:\\Users\\Sagar_Pokhrel\\Documents\\2 DAW\\Xammp\\htdocs\\Desarrollowebenentornoservidor\\Cine\\Includes\\texto.py';

// Ejecuta Python usando el launcher py de Windows
$command = "py -3 " . escapeshellarg($python_file) . " 2>&1";
$python_output = shell_exec($command);

// Decodifica JSON
$texto = "Bienvenido al Cine"; // valor por defecto
if ($python_output) {
    $texto_json = json_decode($python_output, true);
    if (json_last_error() === JSON_ERROR_NONE && isset($texto_json['texto'])) {
        $texto = $texto_json['texto'];
    } else {
        $texto = "Error en JSON de Python: " . htmlspecialchars($python_output);
    }
} else {
    $texto = "Error al ejecutar Python. Verifica py y ruta.";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cine</title>
<style>
header th:first-child img { width:180px;height:auto;border-radius:12px;transition:transform 0.3s ease, filter 0.3s ease; }
header th:first-child img:hover { transform: scale(1.08); filter: brightness(1.1); }

header { background: linear-gradient(90deg, #0f1217, #02050c); padding:5px 20px; border-radius:10px; box-shadow:0 4px 8px rgba(0,0,0,0.2); }
header table { width:100%; border-collapse:collapse; }
header th:last-child { text-align:center; color:#fff; font-weight:bold; }
header th:last-child img { width:70px;height:70px;border-radius:50%;margin-bottom:10px;border:2px solid #fff; }
header th:last-child a {
    display:inline-block;
    margin-top:10px;
    padding:10px 22px;
    border-radius:30px;
    background:linear-gradient(135deg,#ffd700,#ffc107);
    color:#141414; font-weight:bold; 
    text-decoration:none; 
}

/* Estilo para el texto moviendo */
.marquee-container {
    width:80%;
    max-width:1000px;
    height:70px;
    background:#1a1a1a;
    border-radius:12px;
    display:flex;
    align-items:center;
    overflow:hidden;
    margin:auto;
    border:2px solid #ffd700;
}

.marquee-text {
    display:inline-block;
    white-space:nowrap;
    color:#ffd700;
    font-weight:bold;
    font-size:1.1em;
    animation: scroll-text 40s linear infinite; /* más lento para leer */
}

@keyframes scroll-text {
    0% { transform: translateX(0); }
    100% { transform: translateX(-50%); } /* mueve solo la mitad del ancho, porque se repite */
}

</style>
</head>
<body>
<header>
<table class="box" cellspacing="0" width="100%">
<tr>
    <th style="width:30%;"><a href="./ModifiedMain.php"><img src="./Imagenes/logo1.png" alt="Logo"></a></th>
    <th style="width:50%; text-align:center; vertical-align:middle;">
        <div class="marquee-container">
            <p class="marquee-text"><?php echo htmlspecialchars($texto); ?></p>
        </div>
    </th>
    <th style="width:20%; text-align:center;">
        <img src="./Imagenes/user.png" alt="User"><br>
        <?php echo "Bienvenido: " . htmlspecialchars($_SESSION["usuario"]); ?><br>
        <a href="./login.php">LogOut</a>
    </th>
</tr>
</table>
</header>
</body>
</html>