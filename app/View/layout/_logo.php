<?php
// logo SANTRI, atur lewat $logoSize / $logoPlain / $logoColor
$s = $logoSize ?? 38;
$plain = $logoPlain ?? false;
$bar = $plain ? ($logoColor ?? '#1668FF') : '#fff';
?>
<svg width="<?= $s ?>" height="<?= $s ?>" viewBox="0 0 40 40" fill="none" style="display:block;flex-shrink:0">
    <?php if (!$plain): ?>
        <rect width="40" height="40" rx="11" fill="#1668FF"/>
    <?php endif; ?>
    <rect x="16.25" y="5" width="7.5" height="9.5" rx="3.75" fill="<?= $bar ?>"/>
    <rect x="6.5" y="16.25" width="27" height="7.5" rx="3.75" fill="<?= $bar ?>"/>
    <rect x="16.25" y="25.5" width="7.5" height="9.5" rx="3.75" fill="<?= $bar ?>"/>
</svg>
<?php $logoPlain = false; $logoColor = null; // reset ?>
