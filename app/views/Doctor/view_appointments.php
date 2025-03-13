<?php require __DIR__ . '/../Partials/header.php'; ?>
<h1>Your Appointments</h1>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Patient Name</th>
            <th>Appointment Date</th>
            <th>Appointment Time</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($appointments as $appointment): ?>
            <tr>
                <td><?php echo htmlspecialchars($appointment['id']); ?></td>
                <td><?php echo htmlspecialchars($appointment['first_name'] . ' ' . $appointment['last_name']); ?></td>
                <td><?php echo htmlspecialchars($appointment['appointment_date']); ?></td>
                <td><?php echo htmlspecialchars($appointment['appointment_time']); ?></td>
                <td><?php echo htmlspecialchars($appointment['status']); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require __DIR__ . '/../Partials/footer.php'; ?>