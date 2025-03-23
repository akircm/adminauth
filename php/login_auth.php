<?php
session_start();
include('../database/db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare the query to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(); 

    // Check if the user exists and verify the password
    if ($user && $password === $user['password']) {
        $_SESSION['logged_in'] = true;
        $_SESSION['user'] = $user;

        $role = $user['role'];

        // Redirect based on role
        if ($role === 'admin') {
            header('Location: ../admin_page.php');
        } else {
            header('Location: ../welcome.php');
        }
        exit;
    } else {
        // Redirect back to login with an error message
        header('Location: ../index.php?error=Invalid username or password');
        exit;
    }
} else {
    header('Location: ../index.php'); 
    exit;
}
?>
