<?php
require __DIR__ . '/../Partials/header.php';
?>
<div class="home-container container-fluid d-flex flex-column justify-content-center align-items-center vh-100">

    <h1 class="mb-4 text-center">Welcome to the Care Compass Hospital</h1>
    <h3>Emergency call us 0112 345 678</h3>
    <div class="inline-forms-container d-flex align-items-center first_container">
        <form action="/Hospital/public/doctors/search" method="GET" class="d-flex">
            <div class="form-group me-2">
                <input type="text" id="search" name="search" class="form-control" placeholder="Enter name or specialization" required>
            </div>
            <button type="submit" class="btn btn-primary btn-lg">Find a Doctor</button>
        </form>
        <div>
            <a href="/Hospital/public/patients/makeAppointment" class="btn btn-primary btn-lg me-3">Make Appointment</a>
        </div>
    </div>

</div>

<?php
require __DIR__ . '/../Partials/footer.php';
?>