<?php
$title = $title ?? config('app.name');
$bodyClass = $bodyClass ?? 'guest-page';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require view_path('partials.head'); ?>
</head>
<body class="<?= htmlspecialchars($bodyClass) ?>">
    <?php require view_path('partials.flash'); ?>
    <?php require $contentView; ?>
    <?php require view_path('partials.footer_scripts'); ?>
</body>
</html>