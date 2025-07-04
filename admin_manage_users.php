<?php
session_start();

// Restrict access to admin only
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "catadopt");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Fetch all users except admins
$result = $conn->query("SELECT * FROM users WHERE role != 'admin'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Admin Manage Users | FurEver</title>
    <link rel="stylesheet" href="style.css" />
    <link rel="icon" href="img/favicon.ico" type="image/x-icon">
    <style>
        table.user-table {
            width: 90%;
            margin: 2rem auto;
            border-collapse: collapse;
        }

        .user-table th, .user-table td {
            border: 1px solid #ddd;
            padding: 0.75rem;
            text-align: center;
        }

        .user-table th {
            background-color: #f2f2f2;
        }

        .actions a {
            margin: 0 5px;
            padding: 6px 12px;
            background: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }

        .actions a.suspend {
            background: #dc3545;
        }

        .actions a:hover {
            opacity: 0.8;
        }

        .back-btn {
            display: block;
            width: fit-content;
            margin: 1rem auto;
            padding: 8px 16px;
            background: #6c757d;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }

        .back-btn:hover {
            background: #5a6268;
        }

        h1 {
            text-align: center;
            margin-top: 2rem;
        }
    </style>
</head>
<body>

<h1>Manage Adopter Users</h1>

<table class="user-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($user = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $user['id'] ?></td>
                    <td><?= htmlspecialchars($user['fname'] . ' ' . $user['lname']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= $user['status'] == 'suspended' ? 'Suspended' : 'Active' ?></td>
                    <td class="actions">
                       
                        <?php if ($user['status'] == 'suspended'): ?>
                            <a class="suspend" href="toggle_user_status.php?id=<?= $user['id'] ?>&action=activate">Unsuspend</a>
                        <?php else: ?>
                            <a class="suspend" href="toggle_user_status.php?id=<?= $user['id'] ?>&action=suspend">Suspend</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="5">No users found.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<a href="admin_dashboard.php" class="back-btn">Back to Admin Dashboard</a>

</body>
</html>
