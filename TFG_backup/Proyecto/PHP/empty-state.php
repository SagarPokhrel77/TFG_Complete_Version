<?php

/**
 * Bloque de estado vacío reutilizable.
 */
function renderEmptyState(
    string $iconName,
    string $title,
    string $message,
    ?string $ctaUrl = null,
    ?string $ctaLabel = null
): void {
    ?>
    <div class="empty-state" role="status">
        <div class="empty-state-icon"><?= icon($iconName, 48, 'icon icon-lg') ?></div>
        <h2><?= htmlspecialchars($title) ?></h2>
        <p><?= htmlspecialchars($message) ?></p>
        <?php if ($ctaUrl && $ctaLabel): ?>
            <a href="<?= htmlspecialchars($ctaUrl) ?>" class="empty-state-cta profile-btn"><?= htmlspecialchars($ctaLabel) ?></a>
        <?php endif; ?>
    </div>
    <?php
}
