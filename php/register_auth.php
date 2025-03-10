<?php
include('../database/db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Validate and fetch input values
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $address = $_POST['address'] ?? '';
    $number = $_POST['number'] ?? '';
    $role = $_POST['role'] ?? 'user'; // Default role if not provided


    if ($password !== $confirm_password) {
        $error = "Passwords do not match!";
        header('Location: ../register.php?error=' . ($error));
        exit;
    }


    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
    $stmt->execute(['username' => $username, 'email' => $email]);
    if ($stmt->rowCount() > 0) {
        $error = "Username or email already exists!";
        header('Location: ../register.php?error=' . ($error));
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO users (username, email, password, address, number, role) 
                            VALUES (:username, :email, :password, :address, :number, :role)");
    $stmt->execute([

        'username' => $username,
        'email' => $email,
        'password' => $password,
        'address' => $address,
        'number' => $number,
        'role' => $role,
    ]);

    // Redirect to login page with success message
    header('Location: ../index.php?success=' . urlencode("Registration successful! Please login."));
    exit;
} else {
    header('Location: ../register.php');
    exit;
}
?>
