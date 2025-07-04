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
$result = $conn->query("SELECT * FROM cat ORDER BY deleted ASC, catID DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Admin View Cats | FurEver</title>
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

    .actions {
    padding: 5px;
    }

    .action-wrapper {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    justify-content: center;
    }

    .action-btn {
    padding: 6px 12px;
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    border-radius: 6px;
    color: white;
    white-space: nowrap;
    transition: background-color 0.3s ease;
    }

    .actions a.remove {
    background: #dc3545;
    }

    .actions a.remove:hover {
    background:rgb(139, 41, 51);
    }


    /* Force full width on small screens */
    @media (max-width: 500px) {
    .action-wrapper {
        flex-direction: column;
        align-items: stretch;
    }

    .action-btn {
        width: 100%;
        text-align: center;
    }
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
                        <div class="action-wrapper">
                            <?php if ($cat['deleted'] == 0): ?>
                                <a class="action-btn" href="admin_update_cats.php?catID=<?= $cat['catID'] ?>">Edit</a>
                                <a class="action-btn remove" href="admin_remove_cat.php?catID=<?= $cat['catID'] ?>"
                                onclick="return confirm('Are you sure you want to delete this cat? This action cannot be undone.');">Remove</a>
                            <?php else: ?>
                                <em style="color: gray;">Removed</em>
                            <?php endif; ?>
                        </div>
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
