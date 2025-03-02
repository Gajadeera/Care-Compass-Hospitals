<?php
session_start();
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
            if ($this->user->create()) {
                // Redirect to login page after successful registration
                require __DIR__ . '/../views/User/login.php';
                exit();
            } else {
                echo "Failed to create user.";
            }
        } else {
            // Render the registration form
            require __DIR__ . '/../views/User/create.php';
        }
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Assign form data to user properties
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
                echo "User updated successfully!";
            } else {
                echo "Failed to update user.";
            }
        } else {
            // Render the update user form
            echo "Render update user form here.";
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
}
