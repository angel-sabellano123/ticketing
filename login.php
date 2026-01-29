<?php
session_start();
require 'ticketing.php';

$msg = "";

if (isset($_POST['login'])) {
    $id_number = trim($_POST['id_number']);
    $password  = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM user WHERE id_number = ?");
    $stmt->execute([$id_number]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id']   = $user['student_user_id'];
        $_SESSION['full_name'] = $user['full_name'];
        $_SESSION['role']      = $user['role'];

        header("Location: landingpage.php");
        exit;
    } else {
        $msg = "❌ Invalid ID Number or password!";
    }
}

if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: homepage.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - SCC Ticketing</title>
    <style>
        body {
            font-family: Arial;
            background: #f4f4f4;
            text-align: center;
            padding: 50px;
        }
        h2 {
            margin-bottom: 30px;
        }
        form {
            background: #fff;
            padding: 20px;
            width: 350px;
            margin: 20px auto;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        input, button {
            width: 90%;
            padding: 10px;
            margin: 5px 0;
        }
        button {
            background: #333;
            color: #fff;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
        }
        button:hover {
            background: #555;
        }
        .msg {
            color: red;
            font-weight: bold;
        }
        a {
            text-decoration: none;
            color: #333;
        }
    </style>
</head>
<body>
    <h2>Login</h2>
    <?php if ($msg) echo "<p class='msg'>$msg</p>"; ?>
    <form method="POST">
        <input type="text" name="id_number" placeholder="SCC ID Number" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login">Login</button>
    </form>
    <p>No account? <a href="register.php">Register here</a></p>
    <p><a href="homepage.php">Back to Home</a></p>
</body>
</html>
