<?php
require_once 'Database.php';
require_once 'User.php';
require_once 'Auth.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role']; 

    if ($role === 'admin') {
        $adminEmail = 'admin@gmail.com';
        $adminPassword = 'admin';

        if ($email === $adminEmail && $password === $adminPassword) {
            $_SESSION['role'] = 'admin';
            header('Location: admin_dashboard.php');
            exit;
        } else {
            echo "Invalid admin credentials.";
        }
    } elseif ($role === 'borrower') {
        $auth = new Auth();
        $user = $auth->authenticateUser($email, $password);

        if ($user && $user['role'] === 'borrower') {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = 'borrower';
            header('Location: client_dashboard.php');
            exit;
        } else {
            echo "Invalid client credentials.";
        }
    } else {
        echo "Invalid role selected.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="login-container">
        <form method="POST" class="login-form">
            <h1>Login</h1>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            
            <label for="role">Role:</label>
            <select name="role" id="role" required>
                <option value="borrower">Client</option>
                <option value="admin">Admin</option>
            </select>
            
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
