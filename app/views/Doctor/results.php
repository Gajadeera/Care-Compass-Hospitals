<?php
require __DIR__ . '/../Partials/header.php';
?>

<div class="container mt-4">
    <h1 class="mb-4 text-center">Search Results</h1>

    <?php if (count($doctors) > 0): ?>
        <div class="row">
            <?php foreach ($doctors as $doctor): ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h3 class="card-title">Dr. <?= htmlspecialchars($doctor['first_name']) ?> <?= htmlspecialchars($doctor['last_name']) ?></h3>
                            <p class="card-text"><strong>Specialization:</strong> <?= htmlspecialchars($doctor['specialization']) ?></p>
                            <p class="card-text"><strong>Qualifications:</strong> <?= htmlspecialchars($doctor['qualifications']) ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="text-center">No doctors found matching your search.</p>
    <?php endif; ?>

    <div class="text-center my-4">
        <li class="list-inline-item"><a href="/Hospital/public/" class="btn btn-outline-primary"><i class="fab fa-facebook-f"></i> Go Back</a></li>
    </div>
</div>

<?php
require __DIR__ . '/../Partials/footer.php';
?>