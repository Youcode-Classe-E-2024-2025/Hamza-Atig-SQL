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
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #121212;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            width: 100%;
            max-width: 450px;
            background-color: #1c1c1c;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            text-align: center;
            transition: transform 0.3s ease;
        }

        .login-container:hover {
            transform: scale(1.05);
        }

        h2 {
            font-size: 28px;
            font-weight: 500;
            color: #fff;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .input-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .input-group label {
            font-size: 14px;
            color: #bbb;
            margin-bottom: 6px;
            display: block;
        }

        .input-group input {
            width: 94%;
            padding: 12px;
            font-size: 16px;
            border: 1px solid #444;
            border-radius: 8px;
            background-color: #2a2a2a;
            color: #fff;
            outline: none;
            transition: all 0.3s ease;
        }

        .input-group input:hover {
            border-color: #ff4b5c;
        }

        .input-group input:focus {
            border-color: #ff4b5c;
            box-shadow: 0 0 10px rgba(255, 75, 92, 0.7);
        }

        .btn {
            width: 100%;
            padding: 14px;
            background-color: #ff4b5c;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn:hover {
            background-color: #e0404a;
            transform: scale(1.05);
        }

        .error-message {
            color: #ff4747;
            font-size: 14px;
            margin-bottom: 20px;
        }

        footer {
            margin-top: 30px;
            font-size: 14px;
            color: #aaa;
        }

        footer a {
            color: #ff4b5c;
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }
    </style>
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
