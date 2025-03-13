<?php

spl_autoload_register(function ($class) {
    require_once __DIR__ . '/' . str_replace('\\', '/', $class) . '.php';
});

require __DIR__ . '/../config/database.php';
require __DIR__ . '/../app/controllers/PatientController.php';
require __DIR__ . '/../app/controllers/UserController.php';
require __DIR__ . '/../app/controllers/HomeController.php';
require __DIR__ . '/../app/controllers/DoctorController.php';

$patientController = new PatientController($pdo);
$userController = new UserController($pdo);
$homeController = new HomeController();
$doctorController = new DoctorController($pdo);

$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : '';

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

    case "patients/makeAppointment":
        $patientController->bookAppointment();
        break;

    case "patients/viewAppointments":
        $patientController->viewAppointments();
        break;

    case "patients/processPayment":
        $patientController->processPayment();
        break;

    case "patients/viewMedicalRecords":
        $patientController->viewMedicalRecords();
        break;

    case "patients/viewLabResults":
        $patientController->viewLabResults();
        break;

    case "patients/submitQuery":
        $patientController->submitQuery();
        break;


    case "users":
        $userController->index();
        break;
    case "users/profile":
        $userController->profile();
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
    case "users/logout":
        $userController->logout();
        break;

    case "doctors/viewAppointments":
        $doctorController->viewAppointments();
        break;
    case "doctors/search":
        $doctorController->searchDoctors();
        break;
    default:
        http_response_code(404);
        echo "404 Not Found";
        break;
}
