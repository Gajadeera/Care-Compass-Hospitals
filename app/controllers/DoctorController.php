<?php
require __DIR__ . '/../models/Doctor.php';

class DoctorController
{
    private $doctor;

    public function __construct($pdo)
    {
        $this->doctor = new Doctor($pdo);
    }


    public function updateProfile()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $this->doctor->user_id = $_SESSION['id'];
            $this->doctor->specialization = $_POST['specialization'];
            $this->doctor->qualifications = $_POST['qualifications'];

            if ($this->doctor->update()) {
                echo "Doctor profile updated successfully!";
            } else {
                echo "Failed to update doctor profile.";
            }
        } else {

            $doctorData = $this->doctor->getByUserId($_SESSION['id']);
            require __DIR__ . '/../views/Doctor/update_profile.php';
        }
    }


    public function viewAppointments()
    {
        if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'doctor') {
            header('Location: /users/login');
            exit();
        }

        $appointments = $this->doctor->getAppointments($_SESSION['id']);
        require __DIR__ . '/../views/Doctor/view_appointments.php';
    }



    public function viewPatients()
    {
        $patients = $this->doctor->getPatients($_SESSION['id']);
        require __DIR__ . '/../views/Doctor/view_patients.php';
    }


    public function searchDoctors()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['search'])) {
            $searchTerm = $_GET['search'];
            $doctors = $this->doctor->searchDoctors($searchTerm);
            require __DIR__ . '/../views/Doctor/results.php';
        } else {

            require __DIR__ . '/../views/Doctor/searchForm.php';
        }
    }
}
