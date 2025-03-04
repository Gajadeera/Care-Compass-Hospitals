<?php
require __DIR__ . '/../Partials/header.php';
?>

<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="text-center">
        <h1 class="mb-4">Welcome to the Care Compass Hospital</h1>
        <div class="mb-3">
            <a href="/Hospital/public/users/create" class="btn btn-primary btn-lg">Register as Patient</a>
        </div>
        <div>
            <a href="/Hospital/public/patients/create" class="btn btn-success btn-lg">Register as Doctor</a>
        </div>
    </div>
</div>

<?php
require __DIR__ . '/../Partials/footer.php';
?>