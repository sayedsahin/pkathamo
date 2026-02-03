<?php ob_start(); ?>

<h1><?= e($title) ?></h1>

<form method="post" action="/login">
    <?= csrf_field() ?>

    <label>
        Email
        <input type="email" name="email" required>
    </label>

    <label>
        Password
        <input type="password" name="password" required>
    </label>

    <button type="submit">Login</button>
</form>

<?php $content = ob_get_clean(); ?>

<?php ob_start(); ?>
<script></script>
<?php $scripts = ob_get_clean(); ?>

<?php require view_path('layout.main'); ?>
