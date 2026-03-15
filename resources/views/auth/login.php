<main class="auth-page-wrap">
    <div class="auth-card">
        <h1>Sign In</h1>
        <p class="auth-text">Login to continue.</p>

        <?php if (!empty($error ?? null)): ?>
            <div class="flash flash-error auth-inline-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="/login">
            <?= Csrf::field(); ?>

            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-input" autocomplete="username">

            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-input" autocomplete="current-password">

            <button type="submit" class="btn btn-primary auth-submit">Login</button>
        </form>
    </div>
</main>