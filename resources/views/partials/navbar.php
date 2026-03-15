<nav class="navbar">
    <div class="nav-left">
        <div class="brand"><?= htmlspecialchars(config('app.name')) ?></div>
        <div class="subtext">Reusable MVC Starter</div>
    </div>

    <div class="nav-right">
        <?php if (Auth::check()): ?>
            <div class="subtext"><?= htmlspecialchars(Auth::name()) ?></div>

            <form method="POST" action="/logout" class="logout-form">
                <?= Csrf::field(); ?>
                <button type="submit" class="btn btn-secondary btn-small">Logout</button>
            </form>
        <?php endif; ?>
    </div>
</nav>