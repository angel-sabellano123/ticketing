<?php
session_start();
require 'ticketing.php';

// Check if admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: homepage.php");
    exit();
}

$msg = "";

// Approve user request
if (isset($_POST['approve_request'])) {
    $stmt = $conn->prepare("UPDATE request SET status='Approved', pickup_date=? WHERE ticket_id=?");
    $stmt->execute([$_POST['pickup_date'], $_POST['ticket_id']]);
    $msg = "✅ Request approved successfully!";
}

// Delete user request
if (isset($_POST['delete_request'])) {
    $stmt = $conn->prepare("DELETE FROM request WHERE ticket_id=?");
    $stmt->execute([$_POST['ticket_id']]);
    $msg = "🗑️ Request deleted successfully!";
}

// Fetch all users
$stmt = $conn->prepare("SELECT * FROM user ORDER BY full_name ASC");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all requests
$stmt = $conn->prepare("
    SELECT r.*, u.full_name, u.id_number 
    FROM request r 
    JOIN user u ON r.student_user_id = u.student_user_id 
    ORDER BY r.request_date DESC
");
$stmt->execute();
$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: homepage.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - SCC Ticketing</title>
    <link rel="stylesheet" href="admin.css">
    <script>
        function showSection(id) {
            const sections = document.querySelectorAll('.section');
            sections.forEach(sec => sec.style.display = 'none');

            const links = document.querySelectorAll('.sidebar a');
            links.forEach(link => link.classList.remove('active'));

            document.getElementById(id).style.display = 'block';
            document.getElementById('link-' + id).classList.add('active');
        }

        window.onload = function() {
            showSection('home');
        };
    </script>
</head>
<body>

<header>
    <h1>🎓 SCC Admin Dashboard</h1>
</header>

<div class="dashboard">

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Admin</h2>
        <a href="#" id="link-home" onclick="showSection('home')">Home</a>
        <a href="#" id="link-users" onclick="showSection('users')">Users</a>
        <a href="#" id="link-requests" onclick="showSection('requests')">User Requests</a>
        <a href="#" id="link-reports" onclick="showSection('reports')">Reports</a>
        <a href="admin_dashboard.php?logout=true">Logout</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">

        <!-- Home Section -->
        <div id="home" class="section">
            <h2>Welcome, <?php echo htmlspecialchars($_SESSION['full_name']); ?> 👋</h2>
            <?php if ($msg): ?>
                <p class="msg"><?php echo $msg; ?></p>
            <?php endif; ?>
            <p>Use the sidebar to manage users, requests, and reports.</p>
        </div>

        <!-- Users Section -->
        <div id="users" class="section">
            <h2>Registered Users</h2>
            <?php if ($users): ?>
            <table>
                <tr>
                    <th>SCC ID</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Year Level</th>
                    <th>Phone</th>
                    <th>Role</th>
                </tr>
                <?php foreach ($users as $u): ?>
                <tr>
                    <td><?php echo htmlspecialchars($u['id_number']); ?></td>
                    <td><?php echo htmlspecialchars($u['full_name']); ?></td>
                    <td><?php echo htmlspecialchars($u['email']); ?></td>
                    <td><?php echo htmlspecialchars($u['year_level']); ?></td>
                    <td><?php echo htmlspecialchars($u['phone']); ?></td>
                    <td><?php echo htmlspecialchars($u['role']); ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
            <?php else: ?>
                <p>No users registered yet.</p>
            <?php endif; ?>
        </div>

        <!-- Requests Section -->
        <div id="requests" class="section">
            <h2>User Requests</h2>
            <?php if ($requests): ?>
            <table>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Request Type</th>
                    <th>Department</th>
                    <th>Status</th>
                    <th>Request Date</th>
                    <th>Pickup Date</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($requests as $r): ?>
                <tr>
                    <form method="POST">
                        <td><?php echo $r['ticket_id']; ?></td>
                        <td><?php echo htmlspecialchars($r['full_name']) . " (" . htmlspecialchars($r['id_number']) . ")"; ?></td>
                        <td><?php echo htmlspecialchars($r['request_type']); ?></td>
                        <td><?php echo htmlspecialchars($r['department']); ?></td>
                        <td><?php echo htmlspecialchars($r['status']); ?></td>
                        <td><?php echo htmlspecialchars($r['request_date']); ?></td>
                        <td>
                            <input type="date" name="pickup_date" value="<?php echo $r['pickup_date']; ?>" required>
                        </td>
                        <td>
                            <input type="hidden" name="ticket_id" value="<?php echo $r['ticket_id']; ?>">
                            <button name="approve_request">Approve</button>
                            <button name="delete_request">Delete</button>
                        </td>
                    </form>
                </tr>
                <?php endforeach; ?>
            </table>
            <?php else: ?>
                <p>No user requests yet.</p>
            <?php endif; ?>
        </div>

        <!-- Reports Section -->
        <div id="reports" class="section">
            <h2>All Transactions / Requests</h2>
            <?php if ($requests): ?>
            <table>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Request Type</th>
                    <th>Department</th>
                    <th>Status</th>
                    <th>Request Date</th>
                    <th>Pickup Date</th>
                </tr>
                <?php foreach ($requests as $r): ?>
                <tr>
                    <td><?php echo $r['ticket_id']; ?></td>
                    <td><?php echo htmlspecialchars($r['full_name']) . " (" . htmlspecialchars($r['id_number']) . ")"; ?></td>
                    <td><?php echo htmlspecialchars($r['request_type']); ?></td>
                    <td><?php echo htmlspecialchars($r['department']); ?></td>
                    <td><?php echo htmlspecialchars($r['status']); ?></td>
                    <td><?php echo htmlspecialchars($r['request_date']); ?></td>
                    <td><?php echo htmlspecialchars($r['pickup_date']); ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
            <?php else: ?>
                <p>No transactions yet.</p>
            <?php endif; ?>
        </div>

    </div>
</div>

<footer>
    <p>&copy; <?php echo date("Y"); ?> SCC Ticketing System</p>
</footer>

</body>
</html>
