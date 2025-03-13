<?php
require __DIR__ . '/../models/User.php';


class UserController
{
    private $user;

    public function __construct($pdo)
    {
        $this->user = new User($pdo);
    }

    public function index()
    {

        $users = $this->user->read()->fetchAll(PDO::FETCH_ASSOC);
        require __DIR__ . '/../views/User/index.php';
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $this->user->password = $_POST['password'];
            $this->user->email = $_POST['email'];
            $this->user->role = $_POST['role'];
            $this->user->first_name = $_POST['first_name'];
            $this->user->last_name = $_POST['last_name'];
            $this->user->gender = $_POST['gender'];
            $this->user->date_of_birth = $_POST['date_of_birth'];
            $this->user->phone_number = $_POST['phone_number'];
            $this->user->address = $_POST['address'];

            $userId = $this->user->create();

            if ($userId) {
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
            $this->user->id = $_POST['id'];
            $this->user->password = $_POST['password'];
            $this->user->email = $_POST['email'];
            $this->user->role = $_POST['role'];
            $this->user->first_name = $_POST['first_name'];
            $this->user->last_name = $_POST['last_name'];
            $this->user->gender = $_POST['gender'];
            $this->user->date_of_birth = $_POST['date_of_birth'];
            $this->user->phone_number = $_POST['phone_number'];
            $this->user->address = $_POST['address'];

            if ($this->user->update()) {
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
            $userId = $_GET['id'] ?? null;
            if (!$userId) {
                die("User ID is required.");
            }


            $user = $this->user->readById($userId);
            if (!$user) {
                die("User not found.");
            }

            $roleSpecificData = [];
            if ($user['role'] === 'patient') {
                $roleSpecificData = $this->user->getPatientData($userId);
            } elseif ($user['role'] === 'doctor') {
                $roleSpecificData = $this->user->getDoctorData($userId);
            } elseif ($user['role'] === 'staff') {
                $roleSpecificData = $this->user->getStaffData($userId);
            }

            $userModel = $this->user;

            require __DIR__ . '/../views/User/update.php';
        }
    }

    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->user->id = $_POST['id'];

            if ($this->user->delete()) {
                echo "User deleted successfully!";
            } else {
                echo "Failed to delete user.";
            }
        } else {
            echo "Render delete confirmation form here.";
        }
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->user->email = $_POST['email'];
            $this->user->password = $_POST['password'];


            $loggedInUser = $this->user->login();

            if ($loggedInUser) {

                $_SESSION['id'] = $loggedInUser['id'];
                $_SESSION['email'] = $loggedInUser['email'];
                $_SESSION['role'] = $loggedInUser['role'];
                $_SESSION['first_name'] = $loggedInUser['first_name'];
                $_SESSION['last_name'] = $loggedInUser['last_name'];
                $_SESSION['gender'] = $loggedInUser['gender'];
                $_SESSION['date_of_birth'] = $loggedInUser['date_of_birth'];


                if ($loggedInUser['role'] === 'patient') {
                    $patient_id = $this->user->getPatientId($loggedInUser['id']);
                    if ($patient_id) {
                        $_SESSION['patient_id'] = $patient_id;
                    } else {
                        echo "Patient record not found.";
                        exit();
                    }
                }
                require __DIR__ . '/../views/User/profile.php';
                exit();
            } else {
                echo "Invalid email or password.";
            }
        } else {
            require __DIR__ . '/../views/User/login.php';
        }
    }
    public function logout()
    {
        session_unset();
        session_destroy();
        require __DIR__ . '/../views/User/login.php';
        exit();
    }
    public function profile()
    {
        if (!isset($_SESSION['id'])) {
            header('Location: /users/login');
            exit();
        }

        $userId = $_SESSION['id'];
        $user = $this->user->readById($userId);

        if (!$user) {
            die("User not found.");
        }

        $roleSpecificData = [];
        if ($user['role'] === 'patient') {
            $roleSpecificData = $this->user->getPatientData($userId);
        } elseif ($user['role'] === 'doctor') {
            $roleSpecificData = $this->user->getDoctorData($userId);
        } elseif ($user['role'] === 'staff') {
            $roleSpecificData = $this->user->getStaffData($userId);
        }

        require __DIR__ . '/../views/User/profile.php';
    }
}
