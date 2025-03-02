<?php
require __DIR__ . '/../Partials/header.php';
?>

<form action="/Hospital/public/users/login" method="POST">
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
</form>

<?php
require __DIR__ . '/../Partials/footer.php';
?>