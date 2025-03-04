<?php
require __DIR__ . '/../models/User.php';
ini_set('session.cookie_lifetime', 0); // Session cookie expires when the browser is closed
session_start();
class UserController
{
    private $user;

    public function __construct($pdo)
    {
        $this->user = new User($pdo);
    }

    public function index()
    {
        // Fetch all users and display them
        $users = $this->user->read();
        print_r($users->fetchAll(PDO::FETCH_ASSOC));
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Assign form data to user properties
            $this->user->password = $_POST['password']; // Password will be hashed in the model
            $this->user->email = $_POST['email'];
            $this->user->role = $_POST['role'];
            $this->user->first_name = $_POST['first_name'];
            $this->user->last_name = $_POST['last_name'];
            $this->user->gender = $_POST['gender'];
            $this->user->date_of_birth = $_POST['date_of_birth'];
            $this->user->phone_number = $_POST['phone_number'];
            $this->user->address = $_POST['address'];

            // Attempt to create the user
            $userId = $this->user->create();

            if ($userId) {
                // Save role-specific data
                switch ($this->user->role) {
                    case 'patient':
                        $this->user->createPatient($userId, [
                            'insurance_number' => $_POST['insurance_number'],
                            'blood_type' => $_POST['blood_type'],
                            'allergies' => $_POST['allergies'],
                            'emergency_contact' => $_POST['emergency_contact']
                        ]);
                        break;

                    case 'doctor':
                        $this->user->createDoctor($userId, [
                            'specialization' => $_POST['specialization'],
                            'qualifications' => $_POST['qualifications']
                        ]);
                        break;

                    case 'staff':
                        $this->user->createStaff($userId, [
                            'position' => $_POST['position']
                        ]);
                        break;
                }

                // Redirect to login page after successful registration
                require __DIR__ . '/../views/User/login.php';
                exit();
            } else {
                echo "Failed to create user.";
            }
        } else {
            require __DIR__ . '/../views/User/create.php';
        }
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Handle form submission for updating user data
            $this->user->id = $_POST['id'];
            $this->user->password = $_POST['password']; // Password will be hashed in the model
            $this->user->email = $_POST['email'];
            $this->user->role = $_POST['role'];
            $this->user->first_name = $_POST['first_name'];
            $this->user->last_name = $_POST['last_name'];
            $this->user->gender = $_POST['gender'];
            $this->user->date_of_birth = $_POST['date_of_birth'];
            $this->user->phone_number = $_POST['phone_number'];
            $this->user->address = $_POST['address'];

            // Attempt to update the user
            if ($this->user->update()) {
                // Update role-specific data
                switch ($this->user->role) {
                    case 'patient':
                        $this->user->updatePatient($this->user->id, [
                            'insurance_number' => $_POST['insurance_number'],
                            'blood_type' => $_POST['blood_type'],
                            'allergies' => $_POST['allergies'],
                            'emergency_contact' => $_POST['emergency_contact']
                        ]);
                        break;

                    case 'doctor':
                        $this->user->updateDoctor($this->user->id, [
                            'specialization' => $_POST['specialization'],
                            'qualifications' => $_POST['qualifications']
                        ]);
                        break;

                    case 'staff':
                        $this->user->updateStaff($this->user->id, [
                            'position' => $_POST['position']
                        ]);
                        break;
                }

                echo "User updated successfully!";
            } else {
                echo "Failed to update user.";
            }
        } else {
            // Fetch user data for the update form
            $userId = $_GET['id'] ?? null;
            if (!$userId) {
                die("User ID is required.");
            }

            // Fetch user data
            $user = $this->user->readById($userId);
            if (!$user) {
                die("User not found.");
            }

            // Fetch role-specific data
            $roleSpecificData = [];
            if ($user['role'] === 'patient') {
                $roleSpecificData = $this->user->getPatientData($userId);
            } elseif ($user['role'] === 'doctor') {
                $roleSpecificData = $this->user->getDoctorData($userId);
            } elseif ($user['role'] === 'staff') {
                $roleSpecificData = $this->user->getStaffData($userId);
            }

            // Render the update form with user data
            require __DIR__ . '/../views/User/update.php';
        }
    }

    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Assign user ID to delete
            $this->user->id = $_POST['id'];

            // Attempt to delete the user
            if ($this->user->delete()) {
                echo "User deleted successfully!";
            } else {
                echo "Failed to delete user.";
            }
        } else {
            // Render the delete confirmation form
            echo "Render delete confirmation form here.";
        }
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Assign email and password from the form
            $this->user->email = $_POST['email'];
            $this->user->password = $_POST['password']; // Password will be verified in the model

            // Attempt to log in the user
            $loggedInUser = $this->user->login();

            if ($loggedInUser) {
                // Save user data in the session
                $_SESSION['id'] = $loggedInUser['id'];
                $_SESSION['email'] = $loggedInUser['email'];
                $_SESSION['role'] = $loggedInUser['role'];
                $_SESSION['first_name'] = $loggedInUser['first_name'];
                $_SESSION['last_name'] = $loggedInUser['last_name'];
                $_SESSION['gender'] = $loggedInUser['gender'];
                $_SESSION['date_of_birth'] = $loggedInUser['date_of_birth'];


                header("Location: /Hospital/app/views/User/profile.php");
                exit(); // Ensure no further code is executed after redirection
            } else {
                echo "Invalid email or password.";
            }
        } else {
            // Render the login form
            require __DIR__ . '/../views/User/login.php';
        }
    }

    public function logout()
    {
        session_unset(); // Unset all session variables
        session_destroy(); // Destroy the session
        require __DIR__ . '/../views/User/login.php';
        exit();
    }
}
