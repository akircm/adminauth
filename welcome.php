<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: index.php');
    exit;
}

if (isset($_SESSION['user'])){
    $user = $_SESSION['user'];
} else {

    header ('Location: index.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
   <style>
    body { font-family: Arial, sans-serif; background-color: #f2f2f2; text-align: center; padding: 50px; }
        .btn { padding: 10px 20px; background-color: #f44336; color: white; border: none; border-radius: 5px; cursor: pointer; }
        .btn:hover { background-color: #e53935; }
        .error { color: red; }
   </style>
</head>
<body>
        <h1>USER PAGE</h1>

    <div class="welcome-container">
        <h2>Hello and Welcome, <span><?php echo ($user['name']); ?></span>!</h2>
        <p><strong>Here’s your account information:</strong></p>
        <p><span>Username:</span> <?php echo ($user['username']); ?></p>
        <p><span>Password:</span> <?php echo ($user['password']); ?></p>
        <p><span>Department:</span> <?php echo ($user['department']); ?></p>
        <p><span>Course:</span> <?php echo ($user['course']); ?></p>
        <p><span>Email:</span> <?php echo ($user['email']); ?></p>
        <a href="php/logout.php"><button class="btn">Logout</button></a>
    </div>
 
</body>
</html>
 