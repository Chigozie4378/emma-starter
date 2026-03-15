<?php $flashError = Session::getFlash('error'); ?>
<?php if (!empty($flashError)): ?>
    <div class="flash-wrap">
        <div class="flash flash-error"><?= htmlspecialchars($flashError) ?></div>
    </div>
<?php endif; ?>