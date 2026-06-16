<?php
/** @var array<string, mixed> $_SESSION */
?>
<?php if (!empty($_SESSION['flash'])): ?>
    <div class="doctor-flash">
        <?= doctor_icon('check', 16) ?>
        <span><?= doctor_e($_SESSION['flash']['message'] ?? '') ?></span>
    </div>
    <?php unset($_SESSION['flash']); ?>
<?php endif; ?>
