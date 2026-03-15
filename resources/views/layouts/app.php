<?php
$title = $title ?? config('app.name');
$bodyClass = $bodyClass ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require view_path('partials.head'); ?>
</head>
<body class="<?= htmlspecialchars($bodyClass) ?>">
    <div class="app-shell">
        <?php require view_path('partials.navbar'); ?>
        <?php require view_path('partials.flash'); ?>
        <?php require $contentView; ?>
    </div>

    <?php require view_path('partials.footer_scripts'); ?>
</body>
</html>