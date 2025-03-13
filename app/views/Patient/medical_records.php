<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Medical Records</title>
</head>

<body>
    <h1>Medical Records</h1>
    <?php if (!empty($medicalRecords)): ?>
        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Doctor ID</th>
                    <th>Diagnosis</th>
                    <th>Prescription</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($medicalRecords as $record): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($record['id']); ?></td>
                        <td><?php echo htmlspecialchars($record['doctor_id']); ?></td>
                        <td><?php echo htmlspecialchars($record['diagnosis']); ?></td>
                        <td><?php echo htmlspecialchars($record['prescription']); ?></td>
                        <td><?php echo htmlspecialchars($record['date']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No medical records found.</p>
    <?php endif; ?>
</body>

</html>