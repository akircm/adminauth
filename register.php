<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Form</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="login-container">
    <h2>Registration Form</h2>
    <?php
    if (isset($_GET['error'])) {
        echo "<p style='color:red; text-align:center;'>" . $_GET['error'] . "</p>";
    }
    ?>
    <form method="POST" action="php/register_auth.php">
        
        <input type="text" name="username" class="input-field" placeholder="Username" required autocomplete="off"><br>
        <input type="email" name="email" class="input-field" placeholder="Email" required autocomplete="off"><br>
        <input type="password" name="password" class="input-field" placeholder="Password" required autocomplete="off"><br>
        <input type="password" name="confirm_password" class="input-field" placeholder="Confirm Password" required autocomplete="off"><br>
        <input type="text" name="address" class="input-field" placeholder="Address" required autocomplete="off"><br>
        <input type="text" name="number" class="input-field" placeholder="Phone Number" required autocomplete="off"><br>
        <input type="hidden" name="role" value="user">
        <button type="submit" class="btn">Register</button>
    </form>

    <p style="text-align:center; margin-top:10px;">
        <a href="index.php" style="color: blue;">Back to Login</a>
    </p>
</div>

</body>
</html>
