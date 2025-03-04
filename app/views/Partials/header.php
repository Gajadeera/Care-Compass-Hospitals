<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/Hospital/public/style.css"> <!-- External CSS -->
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="/Hospital/public">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/Hospital/public/about">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/Hospital/public/services">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/Hospital/public/contact">Contact Us</a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <?php
                    if (isset($_SESSION['role'])) : ?>
                        <!-- If user is logged in, show Logout -->
                        <li class="nav-item">
                            <a class="nav-link" href="/Hospital/public/users/logout">Logout</a>
                        </li>
                    <?php else : ?>
                        <!-- If user is not logged in, show Login and Register -->
                        <li class="nav-item">
                            <a class="nav-link" href="/Hospital/public/users/login">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/Hospital/public/users/create">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container mt-4 flex-grow-1">
        <!-- Page-specific content will be included here -->