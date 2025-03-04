<?php
require __DIR__ . '/../Partials/header.php';

// Fetch the user ID from the query string
$userId = $_GET['id'] ?? null;

if (!$userId) {
    die("User ID is required.");
}

// Fetch user data from the database
require __DIR__ . '/../models/User.php';
$userModel = new User($pdo);
$user = $userModel->readById($userId);

if (!$user) {
    die("User not found.");
}

// Fetch role-specific data
$roleSpecificData = [];
if ($user['role'] === 'patient') {
    $roleSpecificData = $userModel->getPatientData($userId);
} elseif ($user['role'] === 'doctor') {
    $roleSpecificData = $userModel->getDoctorData($userId);
} elseif ($user['role'] === 'staff') {
    $roleSpecificData = $userModel->getStaffData($userId);
}

// Define all roles (you can fetch these from the database if they are dynamic)
$allRoles = [
    'patient' => 'Patient',
    'staff' => 'Staff',
    'doctor' => 'Doctor',
    'administrator' => 'Administrator',
    'superAdministrator' => 'Super Admin'
];

// Filter roles based on the logged-in user's role
$allowedRoles = [];
$defaultRole = $user['role']; // Default role is the user's current role

if ($_SESSION['role'] === 'superAdmin') {
    // Super Admin can assign all roles
    $allowedRoles = $allRoles;
} elseif ($_SESSION['role'] === 'administrator') {
    // Administrator can assign all roles except superAdministrator and other administrators
    $allowedRoles = array_filter($allRoles, function ($role) {
        return !in_array($role, ['superAdministrator', 'administrator']);
    }, ARRAY_FILTER_USE_KEY);
} else {
    // For other roles, only allow the current role
    $allowedRoles = [$user['role'] => $allRoles[$user['role']]];
}
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <h2 class="mb-4 text-center">Update User</h2>
            <form action="/Hospital/public/users/update" method="POST">
                <!-- Hidden input for user ID -->
                <input type="hidden" name="id" value="<?= htmlspecialchars($userId) ?>">

                <!-- First Name and Last Name (Inline) -->
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

                <!-- Email and Password -->
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="password">Password (Leave blank to keep current password)</label>
                    <input type="password" class="form-control" id="password" name="password">
                </div>

                <!-- Gender and Date of Birth (Inline) -->
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

                <!-- Phone Number and Address -->
                <div class="form-group">
                    <label for="phone_number">Phone Number</label>
                    <input type="text" class="form-control" id="phone_number" name="phone_number" value="<?= htmlspecialchars($user['phone_number']) ?>">
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <textarea class="form-control" id="address" name="address" rows="3"><?= htmlspecialchars($user['address']) ?></textarea>
                </div>

                <!-- Role Selection (Conditional) -->
                <?php if (!empty($allowedRoles)) : ?>
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select class="form-control" id="role" name="role" <?= $_SESSION['role'] !== 'superAdmin' && $_SESSION['role'] !== 'administrator' ? 'disabled' : '' ?>>
                            <?php foreach ($allowedRoles as $value => $label): ?>
                                <option value="<?= htmlspecialchars($value) ?>" <?= $user['role'] === $value ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($label) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php else : ?>
                    <!-- Hide role dropdown and set default role -->
                    <input type="hidden" name="role" value="<?= htmlspecialchars($defaultRole) ?>">
                <?php endif; ?>

                <!-- Patient-Specific Fields -->
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

                <!-- Doctor-Specific Fields -->
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

                <!-- Staff-Specific Fields -->
                <div id="staffFields" class="role-specific-fields" style="display: none;">
                    <div class="form-group">
                        <label for="position">Position</label>
                        <input type="text" class="form-control" id="position" name="position" value="<?= htmlspecialchars($roleSpecificData['position'] ?? '') ?>">
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="form-group text-center">
                    <button type="submit" class="btn btn-primary">Update User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('role');
        const patientFields = document.getElementById('patientFields');
        const doctorFields = document.getElementById('doctorFields');
        const staffFields = document.getElementById('staffFields');

        // Function to hide all role-specific fields
        function hideAllFields() {
            patientFields.style.display = 'none';
            doctorFields.style.display = 'none';
            staffFields.style.display = 'none';
        }

        // Function to show fields based on selected role
        function showFieldsForRole(role) {
            hideAllFields();
            if (role === 'patient') {
                patientFields.style.display = 'block';
            } else if (role === 'doctor') {
                doctorFields.style.display = 'block';
            } else if (role === 'staff') {
                staffFields.style.display = 'block';
            }
        }

        // Initial call to show fields based on default selected role
        if (roleSelect) {
            showFieldsForRole(roleSelect.value);
            roleSelect.addEventListener('change', function() {
                showFieldsForRole(this.value);
            });
        } else {
            // If role is hidden (default role for fresh users), show patient or doctor fields
            showFieldsForRole('<?= $defaultRole ?>');
        }
    });
</script>

<?php
require __DIR__ . '/../Partials/footer.php';
?>