<?php
session_start();
require 'ticketing.php';

$msg = "";
$success = "";

if (isset($_POST['register'])) {

    $id_number  = trim($_POST['id_number']);
    $full_name  = trim($_POST['full_name']);
    $email      = trim($_POST['email']);
    $password   = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $phone      = trim($_POST['phone']);

    // AUTO ROLE BASED ON ID LENGTH
    if (strlen($id_number) <= 4) {
        $role = 'admin';
        $year_level = 'N/A'; // default value for admin
    } else {
        $role = 'student';
        $year_level = trim($_POST['year_level']); // only for student
        if (!$year_level) {
            $msg = "❌ Year level is required for students!";
        }
    }

    // CHECK IF ID EXISTS
    if (!$msg) {
        $check = $conn->prepare("SELECT student_user_id FROM user WHERE id_number = ?");
        $check->execute([$id_number]);
        if ($check->rowCount() > 0) {
            $msg = "❌ ID Number already registered!";
        }
    }

    // INSERT USER
    if (!$msg) {
        $stmt = $conn->prepare("
            INSERT INTO user 
            (id_number, full_name, password, role, year_level, phone_number, email)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $id_number,
            $full_name,
            $password,
            $role,
            $year_level,
            $phone,
            $email
        ]);

        $success = "✅ Registration successful! Redirecting to login...";
        header("refresh:2; url=login.php"); // redirect after 2 seconds
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - SCC Ticketing</title>
    <link rel="stylesheet" href="ticketing.css">
    <script>
        function toggleYearLevel() {
            const idInput = document.querySelector('input[name="id_number"]');
            const yearDiv = document.getElementById('yearDiv');
            if (idInput.value.length > 4) {
                yearDiv.style.display = 'block';
                yearDiv.querySelector('input').required = true;
            } else {
                yearDiv.style.display = 'none';
                yearDiv.querySelector('input').required = false;
            }
        }
    </script>
</head>
<body>

<header>
    <h1>🎓 SCC Registrar Ticketing System</h1>
</header>

<h2>Register</h2>

<?php if ($msg): ?>
    <p class="error"><?php echo htmlspecialchars($msg); ?></p>
<?php endif; ?>

<?php if ($success): ?>
    <p class="success"><?php echo htmlspecialchars($success); ?></p>
<?php endif; ?>

<?php if (!$success): ?>
<form method="POST">
    <input type="text" name="id_number" placeholder="SCC ID Number" required oninput="toggleYearLevel()">
    <input type="text" name="full_name" placeholder="Full Name" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <input type="text" name="phone" placeholder="Phone Number" required>

    <div id="yearDiv" style="display:none;">
        <input type="text" name="year_level" placeholder="Year Level">
    </div>

    <button type="submit" name="register">Register</button>
</form>
<?php endif; ?>

<p>Already have an account? <a href="login.php">Login here</a></p>
<p><a href="homepage.php">Back to Home</a></p>

<footer>
    <p>&copy; <?php echo date("Y"); ?> SCC Ticketing System</p>
</footer>

</body>
</html>
