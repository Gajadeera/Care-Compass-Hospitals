<?php
require __DIR__ . '/../../config/database.php';

class User
{
    private $conn;
    private $table_name = "users";

    // User properties
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

    public function create()
    {
        try {
            // Validate role
            $allowedRoles = ['administrator', 'staff', 'patient'];
            if (!in_array($this->role, $allowedRoles)) {
                throw new Exception("Invalid role. Allowed roles are: administrator, staff, patient.");
            }

            // Validate gender
            $allowedGenders = ['male', 'female', 'other'];
            if (!in_array($this->gender, $allowedGenders)) {
                throw new Exception("Invalid gender. Allowed genders are: male, female, other.");
            }

            // Hash the password
            $this->password = password_hash($this->password, PASSWORD_BCRYPT);

            // Insert query
            $query = "INSERT INTO " . $this->table_name . " 
                      (password, email, role, first_name, last_name, gender, date_of_birth, phone_number, address) 
                      VALUES (:password, :email, :role, :first_name, :last_name, :gender, :date_of_birth, :phone_number, :address)";

            $stmt = $this->conn->prepare($query);

            // Bind parameters
            $stmt->bindParam(":password", $this->password);
            $stmt->bindParam(":email", $this->email);
            $stmt->bindParam(":role", $this->role);
            $stmt->bindParam(":first_name", $this->first_name);
            $stmt->bindParam(":last_name", $this->last_name);
            $stmt->bindParam(":gender", $this->gender);
            $stmt->bindParam(":date_of_birth", $this->date_of_birth);
            $stmt->bindParam(":phone_number", $this->phone_number);
            $stmt->bindParam(":address", $this->address);

            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }

    public function read()
    {
        // Select all fields
        $query = "SELECT id, email, role, first_name, last_name, gender, date_of_birth, phone_number, address, created_at 
                  FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function update()
    {
        try {
            // Hash the password if it's being updated
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

            // Bind parameters
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
            // Query to fetch user by email
            $query = "SELECT id, password, email, role, first_name, last_name, gender, date_of_birth, phone_number, address, created_at 
                      FROM " . $this->table_name . " 
                      WHERE email = :email LIMIT 1";
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":email", $this->email);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                // Verify the password
                if (password_verify($this->password, $row['password'])) {
                    // Return only necessary user data
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

            return false; // Login failed
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }
}
