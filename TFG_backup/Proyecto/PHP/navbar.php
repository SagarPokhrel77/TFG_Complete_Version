<?php

/**

 * Barra de navegación común.

 * Variables opcionales antes del include:

 *   $activePage = 'inicio' | 'favoritos' | 'partidos' | 'transferencias' | 'gestionar_partidos'

 */

$activePage = $activePage ?? "";

$favoritesCount = 0;



if (!function_exists('isAdmin')) {

    require_once __DIR__ . '/auth.php';

}

if (!function_exists('icon')) {

    require_once __DIR__ . '/icons.php';

}



$isAdmin = isAdmin();



if (isset($_SESSION["user_id"]) && isset($conn)) {

    $uid = (int) $_SESSION["user_id"];

    $favQ = $conn->prepare("SELECT COUNT(DISTINCT jugador_id) AS total FROM favoritos WHERE user_id = ?");

    $favQ->bind_param("i", $uid);

    $favQ->execute();

    $favRes = $favQ->get_result()->fetch_assoc();

    $favoritesCount = (int) ($favRes["total"] ?? 0);

}



function navActive(string $page, string $current): string

{

    return $page === $current ? " active" : "";

}

?>

<div class="navbar">

    <a href="user.php" class="logo logo-link">
        <img src="uploads/elite-player-logo.png" alt="Elite Player" class="site-logo-img" width="48" height="48">
        <span class="logo-text">Elite Players</span>
    </a>



    <nav class="nav-links">

        <a href="favorito.php" class="fav-link<?= navActive("favoritos", $activePage) ?>">

            <?= icon('heart', 18) ?> Favoritos

            <span class="fav-count"><?= $favoritesCount ?></span>

        </a>

        <a href="partidos.php" class="fav-link<?= navActive("partidos", $activePage) ?>">

            <?= icon('trophy', 18) ?> Partidos

        </a>

        <a href="transferencias.php" class="fav-link<?= navActive("transferencias", $activePage) ?>">

            <?= icon('briefcase', 18) ?> Transferencias

        </a>

        <?php if ($isAdmin): ?>

            <a href="gestionar_partidos.php" class="fav-link<?= navActive("gestionar_partidos", $activePage) ?>">

                <?= icon('settings', 18) ?> Gestionar partidos

            </a>

        <?php endif; ?>

    </nav>



    <div class="nav-user">

        <?php if (isset($_SESSION["user"])): ?>

            <span class="nav-username"><?= icon('user', 16) ?> <?= htmlspecialchars($_SESSION["user"]) ?></span>

        <?php endif; ?>

        <a href="logout.php" class="fav-link nav-logout"><?= icon('logout', 18) ?> Cerrar sesión</a>

    </div>

</div>

