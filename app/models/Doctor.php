<?php
require __DIR__ . '/../../config/database.php';

class Doctor
{
    private $conn;
    private $table_name = "doctors";

    public $id;
    public $user_id;
    public $specialization;
    public $qualifications;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAllDoctors()
    {
        try {
            $query = "SELECT d.id, d.specialization, d.qualifications, 
                             u.first_name, u.last_name
                      FROM " . $this->table_name . " d
                      INNER JOIN users u ON d.user_id = u.id";

            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }


    public function searchDoctors($searchTerm)
    {
        try {
            $query = "SELECT d.id, d.specialization, d.qualifications, 
                         u.first_name, u.last_name
                  FROM " . $this->table_name . " d
                  INNER JOIN users u ON d.user_id = u.id
                  WHERE u.first_name LIKE :searchTerm 
                     OR u.last_name LIKE :searchTerm 
                     OR CONCAT(u.first_name, ' ', u.last_name) LIKE :searchTerm 
                     OR d.specialization LIKE :searchTerm";

            $stmt = $this->conn->prepare($query);

            $searchTerm = "%" . $searchTerm . "%";
            $stmt->bindParam(":searchTerm", $searchTerm);

            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }

    public function getByUserId($user_id)
    {
        try {
            $query = "SELECT * FROM " . $this->table_name . " WHERE user_id = :user_id LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":user_id", $user_id);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }

    public function update()
    {
        try {
            $query = "UPDATE " . $this->table_name . " SET
                      specialization = :specialization,
                      qualifications = :qualifications
                      WHERE user_id = :user_id";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":specialization", $this->specialization);
            $stmt->bindParam(":qualifications", $this->qualifications);
            $stmt->bindParam(":user_id", $this->user_id);

            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }

    public function delete()
    {
        try {
            $query = "DELETE FROM " . $this->table_name . " WHERE user_id = :user_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":user_id", $this->user_id);

            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }

    public function getAppointments($doctor_id)
    {
        try {
            $query = "SELECT 
                    appointments.id, 
                    appointments.appointment_date, 
                    appointments.appointment_time, 
                    appointments.status, 
                    users.first_name, 
                    users.last_name 
                  FROM appointments 
                  INNER JOIN patients ON appointments.patient_id = patients.id 
                  INNER JOIN users ON patients.user_id = users.id 
                  WHERE appointments.doctor_id = :doctor_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":doctor_id", $doctor_id);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }

    public function getPatients($doctor_id)
    {
        try {
            $query = "SELECT p.* FROM patients p
                      INNER JOIN appointments a ON p.user_id = a.patient_id
                      WHERE a.doctor_id = :doctor_id
                      GROUP BY p.user_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":doctor_id", $doctor_id);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }
}
