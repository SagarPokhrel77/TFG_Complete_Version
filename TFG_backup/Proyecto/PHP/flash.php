<?php

function setFlash(string $type, string $message): void
{
    $_SESSION["flash"] = [
        "type" => $type,
        "message" => $message
    ];
}

function getFlash(): ?array
{
    if (!isset($_SESSION["flash"])) {
        return null;
    }

    $flash = $_SESSION["flash"];
    unset($_SESSION["flash"]);
    return $flash;
}

function renderFlash(): void
{
    $flash = getFlash();

    if (!$flash) {
        return;
    }

    $type = htmlspecialchars($flash["type"]);
    $message = htmlspecialchars($flash["message"]);

    echo '<div class="flash flash-' . $type . '" role="alert">' . $message . '</div>';
}
