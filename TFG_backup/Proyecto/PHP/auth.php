<?php

function isLoggedIn(): bool
{
    return isset($_SESSION["user_id"]);
}

function isAdmin(): bool
{
    return isset($_SESSION["rol"]) && ($_SESSION["rol"] == 1 || $_SESSION["rol"] == 2);
}

/**
 * Redirige a login si no hay sesión activa.
 */
function requireLogin(): void
{
    if (!isLoggedIn()) {
        if (!function_exists('setFlash')) {
            require_once __DIR__ . '/flash.php';
        }
        setFlash("error", "Debes iniciar sesión para acceder.");
        header("Location: Login.php");
        exit();
    }
}

/**
 * Solo administradores (rol 1 o 2).
 */
function requireAdmin(): void
{
    requireLogin();

    if (!isAdmin()) {
        if (!function_exists('setFlash')) {
            require_once __DIR__ . '/flash.php';
        }
        setFlash("error", "No tienes permisos para acceder a esta página.");
        header("Location: user.php");
        exit();
    }
}
