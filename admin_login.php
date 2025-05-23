<?php
session_start();
include "config.php"; // Database connection

// Prevent browser back after login
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM admin WHERE username = :username";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":username", $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $user['username'];
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $error = "Invalid credentials!";
        }
    } else {
        $error = "Invalid credentials!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .login-container {
            width: 30%;
            margin: 100px auto;
            background: white;
            padding: 20px;
            box-shadow: 0px 0px 10px 0px #000000;
            text-align: center;
            border-radius: 10px;
        }
        input {
            width: 80%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .button {
            width: 85%;
            padding: 10px;
            background: #28a745;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        .button:hover {
            background: #218838;
        }
        .add-admin-link {
            margin-top: 10px;
            display: block;
            color: #007bff;
            text-decoration: none;
            font-size: 14px;
        }
        .add-admin-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Admin Login</h2>
    <form method="POST">
        <input type="text" name="username" placeholder="Enter Admin Username" required>
        <input type="password" name="password" placeholder="Enter Password" required>
        <button type="submit" class="button">Login</button>
    </form>
    <?php if (isset($error)) { echo "<p style='color: red;'>$error</p>"; } ?>

    <!-- Link to add new admin -->
    <a href="add_admin.php" class="add-admin-link">➕ Add New Admin</a>
</div>

</body>
</html>
