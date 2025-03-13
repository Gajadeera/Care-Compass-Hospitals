<?php
require __DIR__ . '/../Partials/header.php';

$allRoles = [
    'patient' => 'Patient',
    'staff' => 'Staff',
    'doctor' => 'Doctor',
    'administrator' => 'Administrator',
    'SuperAdmin' => 'Super Admin'
];
$allowedRoles = [];
$defaultRole = 'patient';

if (!isset($_SESSION['role'])) {
    $allowedRoles = [
        'patient' => 'Patient',
        'doctor' => 'Doctor'
    ];
} else {
    if ($_SESSION['role'] === 'superAdmin') {
        $allowedRoles = $allRoles;
    } elseif ($_SESSION['role'] === 'administrator') {
        $allowedRoles = array_filter($allRoles, function ($role) {
            return !in_array($role, ['superAdministrator', 'administrator']);
        }, ARRAY_FILTER_USE_KEY);
    } elseif ($_SESSION['role'] === 'staff') {
        $allowedRoles = ['staff' => 'Staff'];
    } elseif ($_SESSION['role'] === 'doctor') {
        $allowedRoles = ['doctor' => 'Doctor'];
    } elseif ($_SESSION['role'] === 'patient') {
        $allowedRoles = ['patient' => 'Patient'];
    }
}
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <h2 class="mb-3 text-center">Add New User</h2>
            <form action="/Hospital/public/users/create" method="POST">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="first_name" class="form-label">First Name</label>
                        <input type="text" class="form-control form-control-sm" id="first_name" name="first_name" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="last_name" class="form-label">Last Name</label>
                        <input type="text" class="form-control form-control-sm" id="last_name" name="last_name" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control form-control-sm" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control form-control-sm" id="password" name="password" required>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="gender" class="form-label">Gender</label>
                        <select class="form-control form-control-sm" id="gender" name="gender" required>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="date_of_birth" class="form-label">Date of Birth</label>
                        <input type="date" class="form-control form-control-sm" id="date_of_birth" name="date_of_birth" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="phone_number" class="form-label">Phone Number</label>
                    <input type="text" class="form-control form-control-sm" id="phone_number" name="phone_number">
                </div>
                <div class="form-group">
                    <label for="address" class="form-label">Address</label>
                    <textarea class="form-control form-control-sm" id="address" name="address" rows="2"></textarea>
                </div>
                <?php if (!empty($allowedRoles)) : ?>
                    <div class="form-group">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-control form-control-sm" id="role" name="role">
                            <?php foreach ($allowedRoles as $value => $label): ?>
                                <option value="<?= htmlspecialchars($value) ?>">
                                    <?= htmlspecialchars($label) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php else : ?>
                    <input type="hidden" name="role" value="<?= htmlspecialchars($defaultRole) ?>">
                <?php endif; ?>
                <div id="patientFields" class="role-specific-fields" style="display: none;">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="insurance_number" class="form-label">Insurance Number</label>
                            <input type="text" class="form-control form-control-sm" id="insurance_number" name="insurance_number">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="blood_type" class="form-label">Blood Type</label>
                            <select class="form-control form-control-sm" id="blood_type" name="blood_type">
                                <option value="A+">A+</option>
                                <option value="A-">A-</option>
                                <option value="B+">B+</option>
                                <option value="B-">B-</option>
                                <option value="AB+">AB+</option>
                                <option value="AB-">AB-</option>
                                <option value="O+">O+</option>
                                <option value="O-">O-</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="allergies" class="form-label">Allergies</label>
                        <textarea class="form-control form-control-sm" id="allergies" name="allergies" rows="2"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="emergency_contact" class="form-label">Emergency Contact</label>
                        <input type="text" class="form-control form-control-sm" id="emergency_contact" name="emergency_contact">
                    </div>
                </div>
                <div id="doctorFields" class="role-specific-fields" style="display: none;">
                    <div class="form-group">
                        <label for="specialization" class="form-label">Specialization</label>
                        <input type="text" class="form-control form-control-sm" id="specialization" name="specialization">
                    </div>
                    <div class="form-group">
                        <label for="qualifications" class="form-label">Qualifications</label>
                        <textarea class="form-control form-control-sm" id="qualifications" name="qualifications" rows="2"></textarea>
                    </div>
                </div>

                <div id="staffFields" class="role-specific-fields" style="display: none;">
                    <div class="form-group">
                        <label for="position" class="form-label">Position</label>
                        <input type="text" class="form-control form-control-sm" id="position" name="position">
                    </div>
                </div>
                <div class="form-group text-center my-4">
                    <button type="submit" class="btn btn-primary btn-md">Create User</button>
                    <a href="/Hospital/public/" class="btn btn-secondary btn-md ml-2">Back to Home</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
require __DIR__ . '/../Partials/footer.php';
?>