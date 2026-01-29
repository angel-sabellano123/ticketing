<?php
session_start();
require 'ticketing.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: homepage.php");
    exit();
}

$msg = "";

// Handle new request submission
if (isset($_POST['request_ticket'])) {
    $student_user_id = $_SESSION['user_id'];
    $request_type    = $_POST['request_type'];
    $department      = trim($_POST['department']);
    $details         = trim($_POST['details']);

    $stmt = $conn->prepare(
        "INSERT INTO request (student_user_id, request_type, department, details)
         VALUES (?, ?, ?, ?)"
    );
    $stmt->execute([$student_user_id, $request_type, $department, $details]);

    $msg = "✅ Request submitted successfully!";
}

// Handle request updates
if (isset($_POST['update_request'])) {
    $ticket_id    = $_POST['ticket_id'];
    $request_type = $_POST['request_type'];
    $department   = trim($_POST['department']);

    $stmt = $conn->prepare(
        "UPDATE request 
         SET request_type = ?, department = ? 
         WHERE ticket_id = ? AND student_user_id = ?"
    );
    $stmt->execute([$request_type, $department, $ticket_id, $_SESSION['user_id']]);

    $msg = "✅ Request updated successfully!";
}

// Handle request deletion
if (isset($_POST['delete_request'])) {
    $ticket_id = $_POST['ticket_id'];

    $stmt = $conn->prepare(
        "DELETE FROM request WHERE ticket_id = ? AND student_user_id = ?"
    );
    $stmt->execute([$ticket_id, $_SESSION['user_id']]);

    $msg = "🗑️ Request deleted successfully!";
}

// Fetch user requests
$stmt = $conn->prepare("SELECT * FROM request WHERE student_user_id = ? ORDER BY request_date DESC");
$stmt->execute([$_SESSION['user_id']]);
$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Logout
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
    <title>Landing Page - SCC Ticketing</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; padding: 30px; text-align: center; }
        h1 { margin-bottom: 10px; }
        h2, h3 { margin-top: 30px; }
        form { background: #fff; padding: 20px; width: 400px; margin: 20px auto; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        input, select, textarea, button { width: 90%; padding: 10px; margin: 5px 0; }
        button { background: #333; color: #fff; border: none; cursor: pointer; border-radius: 5px; font-size: 16px; }
        button:hover { background: #555; }
        .msg { color: green; font-weight: bold; }
        table { margin: 20px auto; border-collapse: collapse; width: 90%; background: #fff; }
        th, td { border: 1px solid #ccc; padding: 10px; }
        a.logout { display: inline-block; margin-top: 10px; text-decoration: none; color: #333; font-weight: bold; }
        a.logout:hover { color: #555; }
        .delete-btn { background: red; }
        .delete-btn:hover { background: darkred; }
    </style>
    <script>
        function confirmDelete() {
            return confirm("⚠️ Are you sure you want to delete this request?");
        }
    </script>
</head>
<body>
    <h1>🎓 SCC Registrar Ticketing System</h1>
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['full_name']); ?> 👋</h2>
    <p>Role: <?php echo $_SESSION['role']; ?></p>
    <a href="?logout=true" class="logout">Logout</a>

    <?php if ($msg): ?>
        <p class="msg"><?php echo $msg; ?></p>
    <?php endif; ?>

    <h3>📄 Submit a New Request</h3>
    <form method="POST">
        <select name="request_type" required>
            <option value="">-- Select Request Type --</option>
            <option value="SOA">SOA</option>
            <option value="PERMIT">PERMIT</option>
            <option value="Study Load">Study Load</option>
            <option value="Grades">Grades</option>
        </select>
        <input type="text" name="department" placeholder="Department" required>
        <textarea name="details" placeholder="Additional details (optional)"></textarea>
        <button type="submit" name="request_ticket">Submit</button>
    </form>

    <?php if (!empty($tickets)): ?>
        <h3>Your Previous Requests</h3>
        <table>
            <tr>
                <th>Ticket ID</th>
                <th>Type</th>
                <th>Department</th>
                <th>Status</th>
                <th>Request Date</th>
                <th>Action</th>
            </tr>
            <?php foreach ($tickets as $t): ?>
                <tr>
                    <form method="POST" onsubmit="return confirmDelete();">
                        <td><?php echo $t['ticket_id']; ?></td>
                        <td>
                            <select name="request_type" required>
                                <option value="SOA" <?php if($t['request_type']=='SOA') echo 'selected'; ?>>SOA</option>
                                <option value="PERMIT" <?php if($t['request_type']=='PERMIT') echo 'selected'; ?>>PERMIT</option>
                                <option value="Study Load" <?php if($t['request_type']=='Study Load') echo 'selected'; ?>>Study Load</option>
                                <option value="Grades" <?php if($t['request_type']=='Grades') echo 'selected'; ?>>Grades</option>
                            </select>
                        </td>
                        <td><input type="text" name="department" value="<?php echo htmlspecialchars($t['department']); ?>" required></td>
                        <td><?php echo htmlspecialchars($t['status']); ?></td>
                        <td><?php echo $t['request_date']; ?></td>
                        <td>
                            <input type="hidden" name="ticket_id" value="<?php echo $t['ticket_id']; ?>">
                            <button type="submit" name="update_request">Update</button>
                            <button type="submit" name="delete_request" class="delete-btn">Delete</button>
                        </td>
                    </form>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</body>
</html>
