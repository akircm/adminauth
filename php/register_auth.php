<?php
include('../database/db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $department = $_POST['department'];
    $course = $_POST['course'];
    $role = $_POST['role'];


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

    $stmt = $conn->prepare("INSERT INTO users (name, username, email, password, department, course, role) 
                            VALUES (:name, :username, :email, :password, :department, :course, :role)");
    $stmt->execute([
        'name' => $name,
        'username' => $username,
        'email' => $email,
        'password' => $password,
        'department' => $department,
        'course' => $course,
        'role' => $role,
    ]);

    header('Location: ../index.php?success=Registration successful! Please login.');
    exit;
} else {
    header('Location: ../register.php');
    exit;
}
?>
