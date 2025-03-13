<?php require __DIR__ . '/../Partials/header.php'; ?>

<div class="container mt-5">
    <h2 class="mb-4">Process Payment</h2>
    <form action="/Hospital/public/patients/processPayment" method="POST">
        <div class="form-group mb-3">
            <label for="appointment_id">Appointment ID</label>
            <input type="text" class="form-control" id="appointment_id" name="appointment_id" required>
        </div>
        <div class="form-group mb-3">
            <label for="amount">Amount</label>
            <input type="number" class="form-control" id="amount" name="amount" step="0.01" required>
        </div>
        <div class="form-group text-center">
            <button type="submit" class="btn btn-primary">Process Payment</button>
        </div>
    </form>
    <div class="text-center my-4">
        <li class="list-inline-item"><a href="/Hospital/public/" class="btn btn-outline-primary"><i class="fab fa-facebook-f"></i> Go Back</a></li>
    </div>
</div>

<?php require __DIR__ . '/../Partials/footer.php'; ?>