<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
 
<div class="login-container">
    <h2>Login</h2>
    <?php
    if (isset($_GET['error'])) {
        echo "<p style='color:red; text-align:center;'>" . $_GET['error'] . "</p>";
    }
    ?>
    <form method="POST" action="php/login_auth.php">
        <input type="text" name="username" class="input-field" placeholder="Username" required autocomplete="off"><br>
        <input type="password" name="password" class="input-field" placeholder="Password" required autocomplete="off"><br>
        <button type="submit" class="btn">Login</button>
    </form>

    <p style="text-align:center; margin-top:10px;">
        <a href="register.php" style="color: blue;">Don't have an account? Register here</a>
    </p>
</div>
 
</body>
</html>