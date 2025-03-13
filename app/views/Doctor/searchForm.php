<?php
require __DIR__ . '/../Partials/header.php';
?>

<div class="home-container">
    <div class="search-doctor-form">
        <h2>Find a Doctor</h2>
        <form action="/Hospital/public/doctors/search" method="GET">
            <div class="form-group">
                <label for="search">Search by Name or Specialization:</label>
                <input type="text" id="search" name="search" placeholder="Enter name or specialization" required>
            </div>
            <button type="submit" class="btn-search">Search</button>
        </form>
        <a href="/Hospital/public/" class="btn-back">Back to Home</a>
    </div>
    <div class="text-center my-4">
        <li class="list-inline-item"><a href="/Hospital/public/" class="btn btn-outline-primary"><i class="fab fa-facebook-f"></i> Go Back</a></li>
    </div>
</div>

<?php
require __DIR__ . '/../Partials/footer.php';
?>