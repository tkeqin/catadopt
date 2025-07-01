<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Admin Dashboard | FurEver</title>
    <link rel="stylesheet" href="admin_style.css" />
    <link rel="icon" href="img/favicon.ico" type="image/x-icon">
</head>
<body>
    <nav class="admin-nav">
        <div class="container">
            <a href="admin_dashboard.php" class="brand">FurEver Admin</a>
            <ul class="nav-links">
                <li><a href="logout.php">Log out</a></li>
            </ul>
        </div>
    </nav>

    <main class="admin-main">
        <h1>Admin Dashboard</h1>
        <p class="welcome-text">Welcome, Admin. Manage the FurEver system below:</p>
        
        <div class="admin-cards">
            <a href="admin_view_cats.php" class="card">
                <h2>Manage Cats</h2>
                <p>View, update details or remove cats.</p>
            </a>
            <a href="manage_users.php" class="card">
                <h2>Manage Users</h2>
                <p>View profile or suspend adopter accounts.</p>
            </a>
            <a href="admin_adoption_requests.php" class="card">
                <h2>Adoption Requests</h2>
                <p>Review and approve adoption applications.</p>
            </a>
                <a href="admin_feedback.php" class="card">
                <h2>View Feedback</h2>
                <p>Read adopter feedback on their experiences.</p>
            </a>
        </div>
    </main>
</body>
</html>