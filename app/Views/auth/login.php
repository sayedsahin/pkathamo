<?php ob_start(); ?>

<h1><?= e($title) ?></h1>
<?php 
pr($_SESSION); 
?>
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
    <label>
        Password Confirmation
        <input type="password" name="password_confirmation" required>
    </label>
    <!-- remember -->
    <label>
        <input type="checkbox" name="remember">
        Remember Me

    <button type="submit">Login</button>
</form>

<?php $content = ob_get_clean(); ?>

<?php ob_start(); ?>
<script></script>
<?php $scripts = ob_get_clean(); ?>

<?php require view_path('layout.main'); ?>
