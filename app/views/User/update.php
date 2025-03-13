<?php
require __DIR__ . '/../Partials/header.php';

$userId = $_GET['id'] ?? null;

if (!$userId) {
    die("User ID is required.");
}

$user = $userModel->readById($userId);

if (!$user) {
    die("User not found.");
}

$roleSpecificData = [];
if ($user['role'] === 'patient') {
    $roleSpecificData = $userModel->getPatientData($userId);
} elseif ($user['role'] === 'doctor') {
    $roleSpecificData = $userModel->getDoctorData($userId);
} elseif ($user['role'] === 'staff') {
    $roleSpecificData = $userModel->getStaffData($userId);
}

$allRoles = [
    'patient' => 'Patient',
    'staff' => 'Staff',
    'doctor' => 'Doctor',
    'administrator' => 'Administrator',
    'SuperAdmin' => 'Super Admin'
];

$allowedRoles = [];
$defaultRole = $user['role'];

if ($_SESSION['role'] === 'superAdmin') {
    $allowedRoles = $allRoles;
} elseif ($_SESSION['role'] === 'administrator') {
    $allowedRoles = array_filter($allRoles, function ($role) {
        return !in_array($role, ['superAdministrator', 'administrator']);
    }, ARRAY_FILTER_USE_KEY);
} else {
    $allowedRoles = [$user['role'] => $allRoles[$user['role']]];
}
?>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <h2 class="mb-4 text-center">Update User</h2>
                <form action="/Hospital/public/users/update" method="POST">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($userId) ?>">

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="first_name">First Name</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" value="<?= htmlspecialchars($user['first_name']) ?>" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="last_name">Last Name</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" value="<?= htmlspecialchars($user['last_name']) ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password (Leave blank to keep current password)</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="gender">Gender</label>
                            <select class="form-control" id="gender" name="gender" required>
                                <option value="male" <?= $user['gender'] === 'male' ? 'selected' : '' ?>>Male</option>
                                <option value="female" <?= $user['gender'] === 'female' ? 'selected' : '' ?>>Female</option>
                                <option value="other" <?= $user['gender'] === 'other' ? 'selected' : '' ?>>Other</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="date_of_birth">Date of Birth</label>
                            <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="<?= htmlspecialchars($user['date_of_birth']) ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="phone_number">Phone Number</label>
                        <input type="text" class="form-control" id="phone_number" name="phone_number" value="<?= htmlspecialchars($user['phone_number']) ?>">
                    </div>
                    <div class="form-group">
                        <label for="address">Address</label>
                        <textarea class="form-control" id="address" name="address" rows="3"><?= htmlspecialchars($user['address']) ?></textarea>
                    </div>
                    <?php if (!empty($allowedRoles)) : ?>
                        <div class="form-group">
                            <label for="role">Role</label>
                            <select class="form-control" id="role" name="role">
                                <?php foreach ($allowedRoles as $value => $label): ?>
                                    <option value="<?= htmlspecialchars($value) ?>" <?= $user['role'] === $value ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($label) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php else : ?>
                        <input type="hidden" name="role" value="<?= htmlspecialchars($defaultRole) ?>">
                    <?php endif; ?>
                    <div id="patientFields" class="role-specific-fields" style="display: none;">
                        <div class="form-group">
                            <label for="insurance_number">Insurance Number</label>
                            <input type="text" class="form-control" id="insurance_number" name="insurance_number" value="<?= htmlspecialchars($roleSpecificData['insurance_number'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label for="blood_type">Blood Type</label>
                            <select class="form-control" id="blood_type" name="blood_type">
                                <option value="A+" <?= ($roleSpecificData['blood_type'] ?? '') === 'A+' ? 'selected' : '' ?>>A+</option>
                                <option value="A-" <?= ($roleSpecificData['blood_type'] ?? '') === 'A-' ? 'selected' : '' ?>>A-</option>
                                <option value="B+" <?= ($roleSpecificData['blood_type'] ?? '') === 'B+' ? 'selected' : '' ?>>B+</option>
                                <option value="B-" <?= ($roleSpecificData['blood_type'] ?? '') === 'B-' ? 'selected' : '' ?>>B-</option>
                                <option value="AB+" <?= ($roleSpecificData['blood_type'] ?? '') === 'AB+' ? 'selected' : '' ?>>AB+</option>
                                <option value="AB-" <?= ($roleSpecificData['blood_type'] ?? '') === 'AB-' ? 'selected' : '' ?>>AB-</option>
                                <option value="O+" <?= ($roleSpecificData['blood_type'] ?? '') === 'O+' ? 'selected' : '' ?>>O+</option>
                                <option value="O-" <?= ($roleSpecificData['blood_type'] ?? '') === 'O-' ? 'selected' : '' ?>>O-</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="allergies">Allergies</label>
                            <textarea class="form-control" id="allergies" name="allergies" rows="3"><?= htmlspecialchars($roleSpecificData['allergies'] ?? '') ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="emergency_contact">Emergency Contact</label>
                            <input type="text" class="form-control" id="emergency_contact" name="emergency_contact" value="<?= htmlspecialchars($roleSpecificData['emergency_contact'] ?? '') ?>">
                        </div>
                    </div>
                    <div id="doctorFields" class="role-specific-fields" style="display: none;">
                        <div class="form-group">
                            <label for="specialization">Specialization</label>
                            <input type="text" class="form-control" id="specialization" name="specialization" value="<?= htmlspecialchars($roleSpecificData['specialization'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label for="qualifications">Qualifications</label>
                            <textarea class="form-control" id="qualifications" name="qualifications" rows="3"><?= htmlspecialchars($roleSpecificData['qualifications'] ?? '') ?></textarea>
                        </div>
                    </div>
                    <div id="staffFields" class="role-specific-fields" style="display: none;">
                        <div class="form-group">
                            <label for="position">Position</label>
                            <input type="text" class="form-control" id="position" name="position" value="<?= htmlspecialchars($roleSpecificData['position'] ?? '') ?>">
                        </div>
                    </div>
                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-primary">Update User</button>
                        <li class="list-inline-item"><a href="/Hospital/public/" class="btn btn-outline-primary"><i class="fab fa-facebook-f"></i> Go Back</a></li>

                    </div>
                </form>
            </div>
        </div>

    </div>

    <?php
    require __DIR__ . '/../Partials/footer.php';
    ?>