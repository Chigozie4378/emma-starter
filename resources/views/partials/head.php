<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($title ?? config('app.name')) ?></title>
<link rel="stylesheet" href="<?= htmlspecialchars(asset('assets/css/app.css')) ?>">

<?php if (!empty($pageStyles ?? null)): ?>
    <?php foreach ($pageStyles as $styleFile): ?>
        <link rel="stylesheet" href="<?= htmlspecialchars($styleFile) ?>">
    <?php endforeach; ?>
<?php endif; ?>