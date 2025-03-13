<?php
require __DIR__ . '/../Partials/header.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6 text-center">
            <h2 class="mb-4">User Profile</h2>
            <?php if (isset($_SESSION['role'])) : ?>
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h4 class="card-title">Welcome, <?= htmlspecialchars($_SESSION['first_name'] . ' ' . $_SESSION['last_name']) ?>!</h4>
                        <p class="card-text"><strong>Email:</strong> <?= htmlspecialchars($_SESSION['email']) ?></p>
                        <p class="card-text"><strong>Role:</strong> <?= htmlspecialchars($_SESSION['role']) ?></p>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-body">
                        <?php if ($_SESSION['role'] === 'staff') : ?>
                            <h3 class="card-title mb-3">Staff Actions</h3>
                            <div class="list-group">
                                <a href="/Hospital/app/views/staff/view_appointments.php" class="list-group-item list-group-item-action">View Appointments</a>
                                <a href="/Hospital/app/views/staff/manage_patients.php" class="list-group-item list-group-item-action">Manage Patients</a>
                                <a href="/Hospital/app/views/staff/update_schedule.php" class="list-group-item list-group-item-action">Update Schedule</a>
                                <a href="/Hospital/public/users/update?id=<?= $_SESSION['id'] ?> " class="list-group-item list-group-item-action">Update Profile</a>
                            </div>

                        <?php elseif ($_SESSION['role'] === 'administrator') : ?>
                            <h3 class="card-title mb-3">Administrator Actions</h3>
                            <div class="list-group">
                                <a href="/Hospital/public/users" class="list-group-item list-group-item-action">Manage All Users</a>
                                <a href="/Hospital/app/views/administrator/view_reports.php" class="list-group-item list-group-item-action">View Reports</a>
                                <a href="/Hospital/app/views/administrator/manage_roles.php" class="list-group-item list-group-item-action">Manage Roles</a>
                                <a href="/Hospital/public/users/update?id=<?= $_SESSION['id'] ?> " class="list-group-item list-group-item-action">Update Profile</a>

                            </div>

                        <?php elseif ($_SESSION['role'] === 'patient') : ?>
                            <h3 class="card-title mb-3">Patient Actions</h3>
                            <div class="list-group">
                                <a href="/Hospital/public/patients/makeAppointment" class="list-group-item list-group-item-action">Book Appointment</a>
                                <a href="/Hospital/public/patients/viewAppointments" class="list-group-item list-group-item-action">View Appointments</a>
                                <a href="/Hospital/app/views/patients/view_medical_records.php" class="list-group-item list-group-item-action">View Medical Records</a>
                                <a href="/Hospital/public/users/update?id=<?= $_SESSION['id'] ?> " class="list-group-item list-group-item-action">Update Profile</a>
                            </div>

                        <?php elseif ($_SESSION['role'] === 'doctor') : ?>
                            <h3 class="card-title mb-3">Doctor Actions</h3>
                            <div class="list-group">
                                <a href="/Hospital/public/doctors/viewAppointments" class="list-group-item list-group-item-action">View Appointments</a>
                                <a href="/Hospital/app/views/doctor/update_availability.php" class="list-group-item list-group-item-action">Update Availability</a>
                                <a href="/Hospital/app/views/doctor/view_patient_records.php" class="list-group-item list-group-item-action">View Patient Records</a>
                                <a href="/Hospital/public/users/update?id=<?= $_SESSION['id'] ?> " class="list-group-item list-group-item-action">Update Profile</a>
                            </div>

                        <?php elseif ($_SESSION['role'] === 'superAdmin') : ?>
                            <h3 class="card-title mb-3">Super Admin Actions</h3>
                            <div class="list-group">
                                <a href="/Hospital/public/users/create" class="list-group-item list-group-item-action">Add User</a>
                                <a href="/Hospital/public/users/update?id=<?= $_SESSION['id'] ?> " class="list-group-item list-group-item-action">Edit User Profile</a>
                                <a href="/Hospital/public/users" class="list-group-item list-group-item-action">Manage All Users</a>
                                <a href="/Hospital/app/views/superAdmin/view_system_logs.php" class="list-group-item list-group-item-action">View System Logs</a>
                                <a href="/Hospital/app/views/superAdmin/manage_hospital_settings.php" class="list-group-item list-group-item-action">Manage Hospital Settings</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            <?php else : ?>
                <div class="alert alert-warning" role="alert">
                    You are not logged in. <a href="/Hospital/app/views/users/login.php" class="alert-link">Login here</a>.
                </div>
            <?php endif; ?>
            <li class="list-inline-item"><a href="/Hospital/public/" class="btn btn-outline-primary"><i class="fab fa-facebook-f"></i> Go Back</a></li>
        </div>

    </div>

</div>

<?php
require __DIR__ . '/../Partials/footer.php';
?>