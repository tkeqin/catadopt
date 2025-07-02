<?php
session_start();

// Restrict access to admin only
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "catadopt");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch cat list
$result = $conn->query("SELECT * FROM cat");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Admin - View Cats</title>
    <link rel="stylesheet" href="admin_style.css" />
    <link rel="icon" href="img/favicon.ico" type="image/x-icon">
    <style>
        .cat-table {
            width: 90%;
            margin: 2rem auto;
            border-collapse: collapse;
        }
        .cat-table th, .cat-table td {
            border: 1px solid #ddd;
            padding: 0.75rem;
            text-align: center;
        }
        .cat-table th {
            background-color: #f2f2f2;
        }
        
        h1 {
            text-align: center;
            margin-top: 2rem;
        }
        
    </style>
</head>
<body>

<h1>All Cats in the System</h1>

<?php if (isset($_GET['removed']) && $_GET['removed'] == 1): ?>
    <p style="color: red; text-align: center;">Cat removed successfully.</p>
<?php endif; ?>

<table class="cat-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Breed</th>
            <th>Age</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($cat = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $cat['catID'] ?></td>
                    <td><?= htmlspecialchars($cat['name']) ?></td>
                    <td><?= htmlspecialchars($cat['breed']) ?></td>
                    <td><?= htmlspecialchars($cat['age']) ?> years</td>
                    <td><?= htmlspecialchars($cat['status']) ?></td>
                    <td class="actions">
            <!--<a href="admin_view_cat_detail.php?catID=<?= $cat['catID'] ?>">View</a>-->
                        <a href="admin_update_cats.php?catID=<?= $cat['catID'] ?>">Edit</a>
                        <a href="admin_remove_cat.php?catID=<?= $cat['catID'] ?>" onclick="return confirm('Are you sure you want to delete this cat? This action cannot be undone.');">Remove</a>

                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="6">No cats found.</td></tr>
        <?php endif; ?>
    </tbody>
</table>
<div class="button-group">
    <a href="add_cat.php" class="btn btn-add-cat">Add Cat</a>
    <a href="admin_dashboard.php" class="btn back-btn">Back to Admin Dashboard</a>
</div>

</body>
</html>
