<?php
// Database configuration
$host = "localhost";
$dbname = "CC_Hospital_DB";
$username = "root";
$password = "myroot@1";

// Connect to the database
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Check if a superAdmin already exists
$checkQuery = "SELECT COUNT(*) FROM users WHERE role = 'superAdmin'";
$stmt = $pdo->query($checkQuery);
$superAdminCount = $stmt->fetchColumn();

if ($superAdminCount > 0) {
    die("A superAdmin user already exists. No action taken.");
}

// Insert the superAdmin user
$insertQuery = "INSERT INTO users (
                    password, 
                    email, 
                    role, 
                    first_name, 
                    last_name, 
                    gender, 
                    date_of_birth, 
                    phone_number, 
                    address
                ) VALUES (
                    :password, 
                    :email, 
                    :role, 
                    :first_name, 
                    :last_name, 
                    :gender, 
                    :date_of_birth, 
                    :phone_number, 
                    :address
                )";

$hashedPassword = password_hash('123', PASSWORD_BCRYPT); // Hash the password

try {
    $stmt = $pdo->prepare($insertQuery);
    $stmt->execute([
        ':password' => $hashedPassword,
        ':email' => 'dayan@gmail.com',
        ':role' => 'superAdmin',
        ':first_name' => 'Dayan',
        ':last_name' => 'Gajadeera',
        ':gender' => 'male', // Add gender
        ':date_of_birth' => '1990-04-03', // Add date of birth
        ':phone_number' => '0770097073',
        ':address' => '123 Super Admin St'
    ]);

    echo "Super Admin user created successfully!";
} catch (PDOException $e) {
    die("Error inserting superAdmin user: " . $e->getMessage());
}
