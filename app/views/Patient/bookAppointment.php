<?php
require __DIR__ . '/../Partials/header.php';
require __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../models/Doctor.php';
$doctorModel = new Doctor($pdo);
$doctors = $doctorModel->getAllDoctors();
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <h2 class="mb-4 text-center">Make an Appointment</h2>
            <form action="/Hospital/public/patients/makeAppointment" method="POST">
                <div class="form-group mb-3">
                    <label for="doctor_id">Select Doctor</label>
                    <select class="form-control" id="doctor_id" name="doctor_id" required>
                        <option value="">Choose a doctor</option>
                        <?php if (!empty($doctors)) : ?>
                            <?php foreach ($doctors as $doctor) : ?>
                                <option value="<?= htmlspecialchars($doctor['id']) ?>">
                                    <?= htmlspecialchars($doctor['first_name'] . ' ' . $doctor['last_name']) ?> - <?= htmlspecialchars($doctor['specialization']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <option value="">No doctors available</option>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label for="appointment_date">Appointment Date</label>
                    <input type="date" class="form-control" id="appointment_date" name="appointment_date" required>
                </div>

                <div class="form-group mb-3">
                    <label for="appointment_time">Appointment Time</label>
                    <input type="time" class="form-control" id="appointment_time" name="appointment_time" required>
                </div>

                <div class="form-group text-center">
                    <button type="submit" class="btn btn-primary">Book Appointment</button>
                </div>
            </form>
        </div>
    </div>
    <div class="text-center my-4">
        <li class="list-inline-item"><a href="/Hospital/public/" class="btn btn-outline-primary"><i class="fab fa-facebook-f"></i> Go Back</a></li>
    </div>
</div>

<?php
require __DIR__ . '/../Partials/footer.php';
?>