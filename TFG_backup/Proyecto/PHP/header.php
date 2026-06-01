<?php

/**
 * Cabecera HTML común.
 * Variables: $pageTitle (string), $extraStylesheets (array de rutas CSS adicionales)
 */
$pageTitle = $pageTitle ?? 'Elite Players';
$extraStylesheets = $extraStylesheets ?? [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($pageTitle) ?></title>
<link rel="stylesheet" href="CSS/user.css">
<?php foreach ($extraStylesheets as $css): ?>
<link rel="stylesheet" href="<?= htmlspecialchars($css) ?>">
<?php endforeach; ?>
</head>
<body>
