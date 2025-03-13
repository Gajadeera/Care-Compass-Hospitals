<?php
require __DIR__ . '/../../config/database.php';

class User
{
    private $conn;
    private $table_name = "users";
    public $id;
    public $password;
    public $email;
    public $role;
    public $first_name;
    public $last_name;
    public $gender;
    public $date_of_birth;
    public $phone_number;
    public $address;
    public $created_at;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getConnection()
    {
        return $this->conn;
    }

    public function create()
    {
        try {
            $allowedRoles = ['administrator', 'staff', 'patient', 'doctor', 'superAdministrator'];
            if (!in_array($this->role, $allowedRoles)) {
                throw new Exception("Invalid role. Allowed roles are: administrator, staff, patient, doctor, superAdministrator.");
            }

            $allowedGenders = ['male', 'female', 'other'];
            if (!in_array($this->gender, $allowedGenders)) {
                throw new Exception("Invalid gender. Allowed genders are: male, female, other.");
            }

            $this->password = password_hash($this->password, PASSWORD_BCRYPT);

            $query = "INSERT INTO " . $this->table_name . " 
                      (password, email, role, first_name, last_name, gender, date_of_birth, phone_number, address) 
                      VALUES (:password, :email, :role, :first_name, :last_name, :gender, :date_of_birth, :phone_number, :address)";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":password", $this->password);
            $stmt->bindParam(":email", $this->email);
            $stmt->bindParam(":role", $this->role);
            $stmt->bindParam(":first_name", $this->first_name);
            $stmt->bindParam(":last_name", $this->last_name);
            $stmt->bindParam(":gender", $this->gender);
            $stmt->bindParam(":date_of_birth", $this->date_of_birth);
            $stmt->bindParam(":phone_number", $this->phone_number);
            $stmt->bindParam(":address", $this->address);

            if ($stmt->execute()) {
                return $this->conn->lastInsertId();
            } else {
                return false;
            }
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }

    public function createPatient($userId, $data)
    {
        try {
            $query = "INSERT INTO patients 
                      (user_id, insurance_number, blood_type, allergies, emergency_contact) 
                      VALUES (:user_id, :insurance_number, :blood_type, :allergies, :emergency_contact)";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":user_id", $userId);
            $stmt->bindParam(":insurance_number", $data['insurance_number']);
            $stmt->bindParam(":blood_type", $data['blood_type']);
            $stmt->bindParam(":allergies", $data['allergies']);
            $stmt->bindParam(":emergency_contact", $data['emergency_contact']);

            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }

    public function createDoctor($userId, $data)
    {
        try {
            $query = "INSERT INTO doctors 
                      (user_id, specialization, qualifications) 
                      VALUES (:user_id, :specialization, :qualifications)";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":user_id", $userId);
            $stmt->bindParam(":specialization", $data['specialization']);
            $stmt->bindParam(":qualifications", $data['qualifications']);

            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }

    public function createStaff($userId, $data)
    {
        try {
            $query = "INSERT INTO staff 
                      (user_id, position) 
                      VALUES (:user_id, :position)";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":user_id", $userId);
            $stmt->bindParam(":position", $data['position']);

            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }


    public function read()
    {
        $query = "SELECT id, email, role, first_name, last_name, gender, date_of_birth, phone_number, address, created_at 
                  FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function update()
    {
        try {
            if (!empty($this->password)) {
                $this->password = password_hash($this->password, PASSWORD_BCRYPT);
            }

            $query = "UPDATE " . $this->table_name . " SET
                      password = :password,
                      email = :email,
                      role = :role,
                      first_name = :first_name,
                      last_name = :last_name,
                      gender = :gender,
                      date_of_birth = :date_of_birth,
                      phone_number = :phone_number,
                      address = :address
                      WHERE id = :id";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":password", $this->password);
            $stmt->bindParam(":email", $this->email);
            $stmt->bindParam(":role", $this->role);
            $stmt->bindParam(":first_name", $this->first_name);
            $stmt->bindParam(":last_name", $this->last_name);
            $stmt->bindParam(":gender", $this->gender);
            $stmt->bindParam(":date_of_birth", $this->date_of_birth);
            $stmt->bindParam(":phone_number", $this->phone_number);
            $stmt->bindParam(":address", $this->address);
            $stmt->bindParam(":id", $this->id);

            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }

    public function readById($userId)
    {
        $query = "SELECT * FROM users WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $userId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getPatientData($userId)
    {
        $query = "SELECT * FROM patients WHERE user_id = :user_id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $userId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getDoctorData($userId)
    {
        $query = "SELECT * FROM doctors WHERE user_id = :user_id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $userId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getStaffData($userId)
    {
        $query = "SELECT * FROM staff WHERE user_id = :user_id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $userId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updatePatient($userId, $data)
    {
        try {
            $query = "UPDATE patients SET
                  insurance_number = :insurance_number,
                  blood_type = :blood_type,
                  allergies = :allergies,
                  emergency_contact = :emergency_contact
                  WHERE user_id = :user_id";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":insurance_number", $data['insurance_number']);
            $stmt->bindParam(":blood_type", $data['blood_type']);
            $stmt->bindParam(":allergies", $data['allergies']);
            $stmt->bindParam(":emergency_contact", $data['emergency_contact']);
            $stmt->bindParam(":user_id", $userId);

            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }

    public function updateDoctor($userId, $data)
    {
        try {
            $query = "UPDATE doctors SET
                  specialization = :specialization,
                  qualifications = :qualifications
                  WHERE user_id = :user_id";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":specialization", $data['specialization']);
            $stmt->bindParam(":qualifications", $data['qualifications']);
            $stmt->bindParam(":user_id", $userId);

            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }

    public function updateStaff($userId, $data)
    {
        try {
            $query = "UPDATE staff SET
                  position = :position
                  WHERE user_id = :user_id";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":position", $data['position']);
            $stmt->bindParam(":user_id", $userId);

            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }

    public function delete()
    {
        try {
            $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $this->id);

            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }

    public function login()
    {
        try {
            $query = "SELECT id, password, email, role, first_name, last_name, gender, date_of_birth, phone_number, address, created_at 
                      FROM " . $this->table_name . " 
                      WHERE email = :email LIMIT 1";
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":email", $this->email);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if (password_verify($this->password, $row['password'])) {
                    return [
                        'id' => $row['id'],
                        'email' => $row['email'],
                        'role' => $row['role'],
                        'first_name' => $row['first_name'],
                        'last_name' => $row['last_name'],
                        'gender' => $row['gender'],
                        'date_of_birth' => $row['date_of_birth']
                    ];
                }
            }

            return false;
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }

    public function getPatientId($user_id)
    {
        try {
            $query = "SELECT id FROM patients WHERE user_id = :user_id LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":user_id", $user_id);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                return $row['id'];
            }

            return false;
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }

    public function getProfileData($userId)
    {
        $userData = $this->readById($userId);

        if (!$userData) {
            return null;
        }

        $roleSpecificData = [];
        if ($userData['role'] === 'patient') {
            $roleSpecificData = $this->getPatientData($userId);
        } elseif ($userData['role'] === 'doctor') {
            $roleSpecificData = $this->getDoctorData($userId);
        } elseif ($userData['role'] === 'staff') {
            $roleSpecificData = $this->getStaffData($userId);
        }

        return [
            'user' => $userData,
            'roleSpecificData' => $roleSpecificData
        ];
    }
}
