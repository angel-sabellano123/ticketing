<?php
session_start();
require 'ticketing.php'; // Make sure this points to your database connection

$msg = "";

if (isset($_POST['register'])) {
    $id_number  = trim($_POST['id_number']);
    $full_name  = trim($_POST['full_name']);
    $email      = trim($_POST['email']);
    $password   = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $year_level = trim($_POST['year_level']);
    $phone      = trim($_POST['phone']);

    // Check if ID number already exists
    $check = $conn->prepare("SELECT 1 FROM user WHERE id_number = ?");
    $check->execute([$id_number]);

    if ($check->rowCount() > 0) {
        // Redirect to login page with message if already registered
        header("Location: login.php?already_registered=1");
        exit();
    } else {
        // Insert new user
        $stmt = $conn->prepare(
            "INSERT INTO user 
            (id_number, full_name, password, role, year_level, phone_number, email)
            VALUES (?, ?, ?, 'Student', ?, ?, ?)"
        );
        $stmt->execute([$id_number, $full_name, $password, $year_level, $phone, $email]);
        $msg = "✅ Registration successful! Please <a href='login.php'>login</a>.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - SCC Ticketing</title>
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
    <h2>Register</h2>

    <!-- Display message if registration successful -->
    <?php if ($msg) echo "<p class='msg'>$msg</p>"; ?>

    <form method="POST">
        <input type="text" name="id_number" placeholder="SCC ID Number" required>
        <input type="text" name="full_name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="text" name="year_level" placeholder="Year Level" required>
        <input type="text" name="phone" placeholder="Phone Number">
        <button type="submit" name="register">Register</button>
    </form>

    <p>Already have an account? <a href="login.php">Login here</a></p>
    <p><a href="homepage.php">Back to Home</a></p>
</body>
</html>
