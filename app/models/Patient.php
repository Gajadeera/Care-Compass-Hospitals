<?php
require __DIR__ . '/../../config/database.php';

class Patient
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function bookAppointment($appointmentData)
    {
        try {
            $query = "SELECT id FROM patients WHERE id = :patient_id LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":patient_id", $appointmentData['patient_id']);
            $stmt->execute();

            if (!$stmt->fetch(PDO::FETCH_ASSOC)) {
                throw new Exception("Invalid patient ID. Patient does not exist.");
            }
            $query = "INSERT INTO appointments 
                      (patient_id, doctor_id, appointment_date, appointment_time, status) 
                      VALUES (:patient_id, :doctor_id, :appointment_date, :appointment_time, :status)";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":patient_id", $appointmentData['patient_id']);
            $stmt->bindParam(":doctor_id", $appointmentData['doctor_id']);
            $stmt->bindParam(":appointment_date", $appointmentData['appointment_date']);
            $stmt->bindParam(":appointment_time", $appointmentData['appointment_time']);
            $stmt->bindParam(":status", $appointmentData['status']);

            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }

    public function getAppointments($patient_id)
    {
        try {
            $query = "SELECT a.id, a.doctor_id, a.appointment_date, a.appointment_time, a.status, 
                             d.specialization, u.first_name, u.last_name
                      FROM appointments a
                      INNER JOIN doctors d ON a.doctor_id = d.id
                      INNER JOIN users u ON d.user_id = u.id
                      WHERE a.patient_id = :patient_id
                      ORDER BY a.appointment_date DESC, a.appointment_time DESC";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":patient_id", $patient_id);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }

    public function getMedicalRecords($patient_id)
    {
        try {
            $query = "SELECT * FROM medical_records WHERE patient_id = :patient_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":patient_id", $patient_id);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }

    public function getLabResults($patient_id)
    {
        try {
            $query = "SELECT * FROM lab_tests WHERE patient_id = :patient_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":patient_id", $patient_id);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }

    public function submitQuery($queryData)
    {
        try {
            $query = "INSERT INTO queries 
                      (patient_id, query, status) 
                      VALUES (:patient_id, :query, :status)";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":patient_id", $queryData['patient_id']);
            $stmt->bindParam(":query", $queryData['query']);
            $stmt->bindParam(":status", $queryData['status']);

            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }

    public function processPayment($paymentData)
    {
        try {
            $this->conn->beginTransaction();

            $query = "INSERT INTO payments 
                      (patient_id, amount, payment_status, payment_date) 
                      VALUES (:patient_id, :amount, :payment_status, :payment_date)";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":patient_id", $paymentData['patient_id']);
            $stmt->bindParam(":amount", $paymentData['amount']);
            $stmt->bindParam(":payment_status", $paymentData['payment_status']);
            $stmt->bindParam(":payment_date", $paymentData['payment_date']);

            $stmt->execute();
            $query = "UPDATE appointments 
                      SET status = 'completed' 
                      WHERE id = :appointment_id AND patient_id = :patient_id";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":appointment_id", $paymentData['appointment_id']);
            $stmt->bindParam(":patient_id", $paymentData['patient_id']);

            $stmt->execute();

            $this->conn->commit();

            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            throw new Exception("Database error: " . $e->getMessage());
        }
    }
}
