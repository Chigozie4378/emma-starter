<script>
    window.APP_CONFIG = {
        csrfToken: <?= json_encode($csrfToken ?? '') ?>,
        appName: <?= json_encode(config('app.name')) ?>,
        baseUrl: <?= json_encode(config('app.url')) ?>
    };
</script>

<?php if (!empty($pageScripts ?? null)): ?>
    <?php foreach ($pageScripts as $scriptFile): ?>
        <script src="<?= htmlspecialchars($scriptFile) ?>"></script>
    <?php endforeach; ?>
<?php endif; ?>