<?php
session_start();

// Restrict to admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "catadopt");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Fetch pending adoption requests
$sql = "SELECT a.id, a.adopted_at, a.status, u.fname, u.email, c.name AS cat_name 
        FROM adoptions a
        JOIN users u ON a.user_id = u.id
        JOIN cat c ON a.cat_id = c.catID
        WHERE a.status = 'pending'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin - Adoption Requests</title>
  <link rel="stylesheet" href="admin_style.css" />
  <link rel="icon" href="img/favicon.ico" type="image/x-icon">
  <style>
    table {
        width: 90%;
        margin: 2rem auto;
        border-collapse: collapse;
    }
    th, td {
        padding: 0.75rem;
        border: 1px solid #ccc;
        text-align: center;
    }
    th {
        background-color: #f2f2f2;
    }
    
    .approve { background-color:rgb(84, 188, 109); }
    .reject { background-color: #dc3545; }
  </style>
</head>
<body>

<h1 style="text-align:center;">Pending Adoption Requests</h1>

<table>
    <thead>
        <tr>
            <th>Request ID</th>
            <th>Cat Name</th>
            <th>Adopter</th>
            <th>Email</th>
            <th>Requested On</th>
            <th>View Adoption Details</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['cat_name']) ?></td>
                    <td><?= htmlspecialchars($row['fname']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= date('d M Y', strtotime($row['adopted_at'])) ?></td>
                    <td><a button class="btn" href="adoption_details.php?request_id=<?= $row['id'] ?>">Click to view</a></td>

                    <td>
                        <form method="POST" action="process_adoption_request.php" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <button class="btn approve" name="action" value="approve">Approve</button>
                        </form>
                        <form method="POST" action="process_adoption_request.php" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <button class="btn reject" name="action" value="reject">Reject</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="6">No pending requests.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<a href="admin_dashboard.php" class="back-btn">Back to Dashboard</a>

</body>
</html>
