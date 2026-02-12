<?php
session_start();
require 'ticketing.php';

$msg = "";

if (isset($_POST['login'])) {
    $id_number = trim($_POST['id_number']);
    $password  = $_POST['password'];

    $stmt = $conn->prepare("
        SELECT student_user_id, full_name, password, role
        FROM user
        WHERE id_number = ?
    ");
    $stmt->execute([$id_number]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id']   = $user['student_user_id'];
        $_SESSION['full_name'] = $user['full_name'];
        $_SESSION['role']      = strtolower(trim($user['role'])); // normalize

        // Redirect based on role
        if ($_SESSION['role'] === 'admin') {
                header("Location: admindashboard.php");
        } else {
                header("Location: landingpage.php");
        exit();
        }

    } else {
        $msg = "❌ Invalid ID Number or Password!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - SCC Ticketing</title>
    <link rel="stylesheet" href="ticketing.css">
</head>
<body>

<header>
    <h1>🎓 SCC Registrar Ticketing System</h1>
</header>

<h2>Login</h2>

<?php if ($msg): ?>
    <p class="error"><?php echo htmlspecialchars($msg); ?></p>
<?php endif; ?>

<form method="POST">
    <input type="text" name="id_number" placeholder="SCC ID Number" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit" name="login">Login</button>
</form>

<p>No account? <a href="register.php">Register here</a></p>
<p><a href="homepage.php">Back to Home</a></p>

<footer>
    <p>&copy; <?php echo date("Y"); ?> SCC Ticketing System</p>
</footer>

</body>
</html>
