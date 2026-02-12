<?php
session_start();
require 'ticketing.php';

/* ===============================
   AUTH CHECK
================================ */
if (!isset($_SESSION['user_id'])) {
    header("Location: homepage.php");
    exit();
}

$msg = "";

/* ===============================
   FETCH USER PROFILE
================================ */
$stmt = $conn->prepare("
    SELECT id_number, full_name, email, year_level, phone_number
    FROM user
    WHERE student_user_id = ?
");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

/* ===============================
   SUBMIT NEW REQUEST
================================ */
if (isset($_POST['request_ticket'])) {
    $stmt = $conn->prepare("
        INSERT INTO request (student_user_id, request_type, department, details)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->execute([
        $_SESSION['user_id'],
        $_POST['request_type'],
        trim($_POST['department']),
        trim($_POST['details'])
    ]);
    $msg = "✅ Request submitted successfully!";
}

/* ===============================
   UPDATE REQUEST
================================ */
if (isset($_POST['update_request'])) {
    $stmt = $conn->prepare("
        UPDATE request 
        SET request_type = ?, department = ?
        WHERE ticket_id = ? AND student_user_id = ?
    ");
    $stmt->execute([
        $_POST['request_type'],
        trim($_POST['department']),
        $_POST['ticket_id'],
        $_SESSION['user_id']
    ]);
    $msg = "✅ Request updated successfully!";
}

/* ===============================
   DELETE REQUEST
================================ */
if (isset($_POST['delete_request'])) {
    $stmt = $conn->prepare("
        DELETE FROM request 
        WHERE ticket_id = ? AND student_user_id = ?
    ");
    $stmt->execute([
        $_POST['ticket_id'],
        $_SESSION['user_id']
    ]);
    $msg = "🗑️ Request deleted successfully!";
}

/* ===============================
   FETCH REQUESTS
================================ */
$stmt = $conn->prepare("
    SELECT * FROM request
    WHERE student_user_id = ?
    ORDER BY request_date DESC
");
$stmt->execute([$_SESSION['user_id']]);
$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* ===============================
   LOGOUT
================================ */
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: homepage.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Landing Page - SCC Ticketing</title>
    <link rel="stylesheet" href="ticketing.css">
    <script>
        function showSection(id) {
            document.querySelectorAll('.section').forEach(s => s.style.display = 'none');
            document.querySelectorAll('.sidebar a').forEach(a => a.classList.remove('active'));
            document.getElementById(id).style.display = 'block';
            document.getElementById('link-' + id).classList.add('active');
        }
        function confirmDelete() {
            return confirm("Are you sure you want to delete this request?");
        }
        window.onload = () => showSection('welcome');
    </script>
</head>
<body>

<header>
    <h1>🎓 SCC Registrar Ticketing System</h1>
</header>

<div class="dashboard">

    <!-- SIDEBAR -->
    <div class="sidebar">
        <h2>Dashboard</h2>
        <a href="#" id="link-welcome" onclick="showSection('welcome')">Home</a>
        <a href="#" id="link-request" onclick="showSection('request')">Request</a>
        <a href="#" id="link-myrequests" onclick="showSection('myrequests')">My Requests</a>
        <a href="#" id="link-profile" onclick="showSection('profile')">Profile</a>
        <a href="?logout=true">Logout</a>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content">

        <!-- WELCOME -->
        <div id="welcome" class="section">
            <h2>Welcome, <?php echo htmlspecialchars($_SESSION['full_name']); ?> 👋</h2>
            <p>Role: <?php echo htmlspecialchars($_SESSION['role']); ?></p>
            <?php if ($msg): ?>
                <p class="msg"><?php echo htmlspecialchars($msg); ?></p>
            <?php endif; ?>
            <p>Select an option from the sidebar.</p>
        </div>

        <!-- REQUEST -->
        <div id="request" class="section">
            <h2>Submit a New Request</h2>
            <form method="POST">
                <select name="request_type" required>
                    <option value="">-- Select Request Type --</option>
                    <option value="SOA">SOA</option>
                    <option value="PERMIT">PERMIT</option>
                    <option value="Study Load">Study Load</option>
                    <option value="Grades">Grades</option>
                </select>
                <input type="text" name="department" placeholder="Department" required>
                <textarea name="details" placeholder="Additional details"></textarea>
                <button type="submit" name="request_ticket">Submit</button>
            </form>
        </div>

        <!-- MY REQUESTS -->
        <div id="myrequests" class="section">
            <h2>My Requests</h2>

            <?php if ($tickets): ?>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Type</th>
                    <th>Department</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>

                <?php foreach ($tickets as $t): ?>
                <tr>
                    <form method="POST" onsubmit="return confirmDelete();">
                        <td><?php echo $t['ticket_id']; ?></td>
                        <td>
                            <select name="request_type">
                                <option value="SOA" <?php if ($t['request_type']=="SOA") echo "selected"; ?>>SOA</option>
                                <option value="PERMIT" <?php if ($t['request_type']=="PERMIT") echo "selected"; ?>>PERMIT</option>
                                <option value="Study Load" <?php if ($t['request_type']=="Study Load") echo "selected"; ?>>Study Load</option>
                                <option value="Grades" <?php if ($t['request_type']=="Grades") echo "selected"; ?>>Grades</option>
                            </select>
                        </td>
                        <td><input type="text" name="department" value="<?php echo htmlspecialchars($t['department']); ?>"></td>
                        <td><?php echo $t['status']; ?></td>
                        <td><?php echo $t['request_date']; ?></td>
                        <td>
                            <input type="hidden" name="ticket_id" value="<?php echo $t['ticket_id']; ?>">
                            <button name="update_request">Update</button>
                            <button name="delete_request">Delete</button>
                        </td>
                    </form>
                </tr>
                <?php endforeach; ?>
            </table>
            <?php else: ?>
                <p>No requests found.</p>
            <?php endif; ?>
        </div>

        <!-- PROFILE -->
        <div id="profile" class="section">
            <h2>My Profile</h2>

            <div class="profile-container">
                <div class="profile-pic">
                    <img src="profile-placeholder.png" alt="Profile Picture">
                </div>

                <div class="profile-details">
                    <p><strong>SCC ID Number:</strong> <?php echo htmlspecialchars($user['id_number']); ?></p>
                    <p><strong>Full Name:</strong> <?php echo htmlspecialchars($user['full_name']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                    <p><strong>Year Level:</strong> <?php echo htmlspecialchars($user['year_level']); ?></p>
                    <p><strong>Phone Number:</strong> <?php echo htmlspecialchars($user['phone_number']); ?></p>
                </div>
            </div>
        </div>

    </div>
</div>

<footer>
    <p>&copy; <?php echo date("Y"); ?> SCC Ticketing System</p>
</footer>

</body>
</html>
