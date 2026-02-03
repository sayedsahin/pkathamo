<?php ob_start(); ?>

<h1><?= e($title) ?></h1>

<form method="post" action="/register">
    <?= csrf_field() ?>

    <input name="name" placeholder="Name" required>
    <input name="email" type="email" required>
    <input name="password" type="password" required>

    <button type="submit">Register</button>
</form>

<?php $content = ob_get_clean(); ?>
<?php require view_path('layout.main'); ?>
