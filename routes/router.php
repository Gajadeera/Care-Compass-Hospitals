<?php
// Autoload classes (if not using Composer autoloading)
spl_autoload_register(function ($class) {
    require_once __DIR__ . '/' . str_replace('\\', '/', $class) . '.php';
});

// Include configuration and controllers
require __DIR__ . '/../config/database.php';
// require "./app/controllers/PatientController.php";
require __DIR__ . '/../app/controllers/UserController.php';
require __DIR__ . '/../app/controllers/HomeController.php';
// require "./app/controllers/DoctorController.php";
// require "./app/controllers/AppointmentController.php";

// Initialize controllers
// $patientController = new PatientController($pdo);
$userController = new UserController($pdo);
$homeController = new HomeController();
// $doctorController = new DoctorController($pdo);
// $appointmentController = new AppointmentController($pdo);

// Get the URL from the query parameter
$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : '';

// Route the request
switch ($url) {
    case "":
        $homeController->index();
        break;
    case "register":
        $homeController->register();
        break;
    case "contact":
        $homeController->contact();
        break;
    case "about":
        $homeController->about();
        break;
    case "services":
        $homeController->service();
        break;

    case "patients":
        $patientController->index();
        break;
    case "patients/create":
        $patientController->create();
        break;

    case "users":
        $userController->index();
        break;
    case "users/create":
        $userController->create();
        break;
    case "users/update":
        $userController->update();
        break;
    case "users/delete":
        $userController->delete();
        break;
    case "users/login":
        $userController->login();
        break;

    // case "doctors":
    //     $doctorController->index();
    //     break;
    // case "doctors/create":
    //     $doctorController->create();
    //     break;
    // case "doctors/search":
    //     $doctorController->search();
    //     break;

    // case "appointments":
    //     $appointmentController->index();
    //     break;
    // case "appointments/createAppointment":
    //     $appointmentController->createAppointment();
    //     break;

    // case "administrator":
    //     $userController->login();
    //     break;

    default:
        // Handle 404 - Page not found
        http_response_code(404);
        echo "404 Not Found. Go Back.";
        break;
}
