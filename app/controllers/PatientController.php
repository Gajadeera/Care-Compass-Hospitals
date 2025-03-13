<?php
require __DIR__ . '/../models/Patient.php';

class PatientController
{
    private $patient;
    private $user;

    public function __construct($pdo)
    {
        $this->patient = new Patient($pdo);
        $this->user = new User($pdo);
    }


    public function bookAppointment()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (!isset($_SESSION['patient_id'])) {
                echo "You must be logged in as a patient to book an appointment.";
                return;
            }


            $appointmentData = [
                'patient_id' => $_SESSION['patient_id'],
                'doctor_id' => $_POST['doctor_id'],
                'appointment_date' => $_POST['appointment_date'],
                'appointment_time' => $_POST['appointment_time'],
                'status' => 'pending'
            ];

            try {
                if ($this->patient->bookAppointment($appointmentData)) {
                    require __DIR__ . '/../views/Patient/payment.php';
                } else {
                    echo "Failed to book appointment.";
                }
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
            }
        } else {
            require __DIR__ . '/../views/Patient/bookAppointment.php';
        }
    }

    public function viewAppointments()
    {
        if (!isset($_SESSION['patient_id'])) {
            echo "You must be logged in as a patient to view appointments.";
            return;
        }

        try {
            $appointments = $this->patient->getAppointments($_SESSION['patient_id']);
            require __DIR__ . '/../views/Patient/view_appointments.php';
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function viewMedicalRecords()
    {
        if (!isset($_SESSION['patient_id'])) {
            echo "You must be logged in as a patient to view medical records.";
            return;
        }

        $medicalRecords = $this->patient->getMedicalRecords($_SESSION['patient_id']);
        require __DIR__ . '/../views/Patient/medical_records.php';
    }

    public function viewLabResults()
    {
        if (!isset($_SESSION['patient_id'])) {
            echo "You must be logged in as a patient to view lab results.";
            return;
        }

        $labResults = $this->patient->getLabResults($_SESSION['patient_id']);
        require __DIR__ . '/../views/Patient/lab_results.php';
    }

    public function submitQuery()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_SESSION['patient_id'])) {
                echo "You must be logged in as a patient to submit a query.";
                return;
            }

            $queryData = [
                'patient_id' => $_SESSION['patient_id'],
                'query' => $_POST['query'],
                'status' => 'open'
            ];

            try {
                if ($this->patient->submitQuery($queryData)) {
                    echo "Query submitted successfully!";
                } else {
                    echo "Failed to submit query.";
                }
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
            }
        } else {

            require __DIR__ . '/../views/Patient/submit_query.php';
        }
    }


    public function processPayment()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (!isset($_SESSION['patient_id'])) {
                echo "You must be logged in as a patient to process a payment.";
                return;
            }


            $paymentData = [
                'patient_id' => $_SESSION['patient_id'],
                'appointment_id' => $_POST['appointment_id'],
                'amount' => $_POST['amount'],
                'payment_status' => 'completed',
                'payment_date' => date('Y-m-d H:i:s')
            ];


            try {
                if ($this->patient->processPayment($paymentData)) {
                    require __DIR__ . '/../views/Patient/view_appointments.php';
                } else {
                    echo "Failed to process payment.";
                }
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
            }
        } else {
            require __DIR__ . '/../views/Patient/payment.php';
        }
    }
}
