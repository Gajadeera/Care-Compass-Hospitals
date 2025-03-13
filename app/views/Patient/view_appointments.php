<?php require __DIR__ . '/../Partials/header.php'; ?>

<div class="container mt-5">
    <h2 class="mb-4">Your Appointments</h2>
    <?php if (!empty($appointments)) : ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Doctor</th>
                    <th>Specialization</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($appointments as $appointment) : ?>
                    <tr>
                        <td><?= htmlspecialchars($appointment['first_name'] . ' ' . $appointment['last_name']) ?></td>
                        <td><?= htmlspecialchars($appointment['specialization']) ?></td>
                        <td><?= htmlspecialchars($appointment['appointment_date']) ?></td>
                        <td><?= htmlspecialchars($appointment['appointment_time']) ?></td>
                        <td><?= htmlspecialchars($appointment['status']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else : ?>
        <p>No appointments found.</p>
    <?php endif; ?>
    <div class="text-center my-4">
        <li class="list-inline-item"><a href="/Hospital/public/" class="btn btn-outline-primary"><i class="fab fa-facebook-f"></i> Go Back</a></li>
    </div>
</div>

<?php require __DIR__ . '/../Partials/footer.php'; ?>