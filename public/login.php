<?php
session_start();
require '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = $pdo->prepare("SELECT * FROM Users WHERE username = :username AND password = :password");
    $query->execute(['username' => $username, 'password' => $password]);
    $user = $query->fetch();

    if ($user) {
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] === 'admin') {
            header('Location: admin.php');
        } else {
            header('Location: user.php');
        }
    } else {
        $error = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>

<div class="login-container">
    <h2>Login to Your Account</h2>
    
    <?php if (isset($error)): ?>
        <div class="error-message"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST" action="login.php">
        <div class="input-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required placeholder="Enter your username">
        </div>
        <div class="input-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required placeholder="Enter your password">
        </div>
        <button type="submit" class="btn">Login</button>
    </form>

    <footer>
        <p>Don't have an account? <a href="register.php">Sign Up</a></p>
    </footer>
</div>

</body>
</html>
