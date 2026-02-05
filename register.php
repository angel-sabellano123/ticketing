<?php
session_start();
require 'ticketing.php';

$msg = "";

if (isset($_POST['register'])) {
    $id_number  = trim($_POST['id_number']);
    $full_name  = trim($_POST['full_name']);
    $email      = trim($_POST['email']);
    $password   = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $year_level = trim($_POST['year_level']);
    $phone      = trim($_POST['phone']);
    $role_selected = $_POST['role'];

    // Optional: secret key for admin registration
    $admin_key = isset($_POST['admin_key']) ? trim($_POST['admin_key']) : "";

    // Check if already registered
    $check = $conn->prepare("SELECT * FROM user WHERE id_number = ?");
    $check->execute([$id_number]);

    if ($check->rowCount() > 0) {
        header("Location: login.php?already_registered=1");
        exit();
    }

    // Role assignment
    if ($role_selected == 'Admin') {
        if ($admin_key !== '123') {
            $msg = "❌ Invalid Admin Key!";
        } else {
            $role = 'Admin';
        }
    } elseif ($role_selected == 'Staff') {
        $role = 'Staff';
    } else {
        $role = 'Student';
    }

    if (!$msg) {
        $stmt = $conn->prepare(
            "INSERT INTO user 
            (id_number, full_name, password, role, year_level, phone_number, email)
            VALUES (?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([$id_number, $full_name, $password, $role, $year_level, $phone, $email]);

        // Fetch newly created user
        $stmt = $conn->prepare("SELECT * FROM user WHERE id_number = ?");
        $stmt->execute([$id_number]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Set session
        $_SESSION['user_id']   = $user['student_user_id'];
        $_SESSION['full_name'] = $user['full_name'];
        $_SESSION['role']      = $user['role'];

        // Redirect based on role
        if ($user['role'] == 'Admin') {
            header("Location: admindashboard.php");
        } elseif ($user['role'] == 'Staff') {
            header("Location: landingpage.php"); // Staff can use landing page
        } else {
            header("Location: landingpage.php");
        }
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - SCC Ticketing</title>
    <link rel="stylesheet" href="ticketing.css">
</head>
<body>

<header>
    <h1>🎓 SCC Registrar Ticketing System</h1>
</header>

<h2>Register</h2>

<?php if ($msg): ?>
    <p class="error"><?php echo $msg; ?></p>
<?php endif; ?>

<form method="POST">
    <input type="text" name="id_number" placeholder="SCC ID Number" required>
    <input type="text" name="full_name" placeholder="Full Name" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <input type="text" name="year_level" placeholder="Year Level" required>
    <input type="text" name="phone" placeholder="Phone Number">

    <!-- Role Dropdown -->
    <select name="role" required>
        <option value="Student">Student</option>
        <option value="Staff">Staff</option>
        <option value="Admin">Admin</option>
    </select>

    <!-- Admin Secret Key (visible only if Admin selected via JS) -->
    <input type="text" name="admin_key" placeholder="Admin Key (for Admin only)">

    <button type="submit" name="register">Register</button>
</form>

<p>Already have an account? <a href="login.php">Login here</a></p>
<p><a href="homepage.php">Back to Home</a></p>

<footer>
    <p>&copy; <?php echo date("Y"); ?> SCC Ticketing System</p>
</footer>

<script>
    // Show/hide admin key field
    const roleSelect = document.querySelector('select[name="role"]');
    const adminKeyInput = document.querySelector('input[name="admin_key"]');

    function toggleAdminKey() {
        if (roleSelect.value === 'Admin') {
            adminKeyInput.style.display = 'block';
            adminKeyInput.required = true;
        } else {
            adminKeyInput.style.display = 'none';
            adminKeyInput.required = false;
        }
    }

    roleSelect.addEventListener('change', toggleAdminKey);
    toggleAdminKey(); // initial
</script>

</body>
</html>
