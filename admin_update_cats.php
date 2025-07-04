<?php
session_start();

// Only allow admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "catadopt");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get catID
if (!isset($_GET['catID'])) {
    echo "No cat selected.";
    exit();
}

$catID = (int)$_GET['catID'];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'];
    $breed = $_POST['breed'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $location = $_POST['location'];
    $health_status = $_POST['health_status'];
    $status = $_POST['status'];
    $description = $_POST['description'];
    $healthnmedical = $_POST['healthnmedical'];
    $image_url = $_POST['image_path'];
    $last_updated = date('Y-m-d H:i:s');

    $stmt = $conn->prepare("UPDATE cat SET name=?, breed=?, age=?, gender=?, location=?, health_status=?, status=?, description=?, healthnmedical=?, image_path=?, last_updated=? WHERE catID=?");
    $stmt->bind_param("ssissssssssi", $name, $breed, $age, $gender, $location, $health_status, $status, $description, $healthnmedical, $image_url, $last_updated, $catID);

    if ($stmt->execute()) {
    // Reject pending adoptions if marked as Adopted
        if ($status === 'Adopted') {
            $rejectStmt = $conn->prepare("UPDATE adoptions SET status = 'rejected' WHERE cat_id = ? AND status = 'pending'");
            $rejectStmt ->bind_param("i", $catID);
            $rejectStmt->execute();
            $rejectStmt->close();
        }

        $success = "Cat details updated successfully.";
    } else {
        $error = "Failed to update cat: " . $stmt->error;
    }   


    $stmt->close();
}

// Fetch cat info
$result = $conn->query("SELECT * FROM cat WHERE catID = $catID");
$cat = $result->fetch_assoc();

if (!$cat) {
    echo "Cat not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Edit Cat Details</title>
    <link rel="stylesheet" href="admin_style.css" />
    <link rel="icon" href="img/favicon.ico" type="image/x-icon">
 
</head>
<body>

<div class="form-container">
    <h2>Edit Cat Details</h2>

    <?php if (isset($success)): ?>
        <div class="msg success"><?= htmlspecialchars($success) ?></div>
    <?php elseif (isset($error)): ?>
        <div class="msg error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="form-img">
        <img src="<?= htmlspecialchars($cat['image_path'])?>" alt="Cat Image">
    </div>

    <form method="POST">
        <label>Name</label>
        <input type="text" name="name" value="<?= htmlspecialchars($cat['name']) ?>" required>

        <label>Breed</label>
        <input type="text" name="breed" value="<?= htmlspecialchars($cat['breed']) ?>" required>

        <label>Gender</label>
        <select name="gender" required>
            <option value="Male" <?= $cat['gender'] === 'Male' ? 'selected' : '' ?>>Male</option>
            <option value="Female" <?= $cat['gender'] === 'Female' ? 'selected' : '' ?>>Female</option>
        </select>

        <label>Age</label>
        <input type="number" name="age" value="<?= htmlspecialchars($cat['age']) ?>" required>

        <label>Location</label>
        <input type="text" name="location" value="<?= htmlspecialchars($cat['location']) ?>" required>

        <label>Health Status</label>
        <input type="text" name="health_status" value="<?= htmlspecialchars($cat['health_status']) ?>" required>

        <label>Status</label>
        <select name="status" required>
            <option value="Available" <?= $cat['status'] === 'Available' ? 'selected' : '' ?>>Available</option>
            <option value="Adopted" <?= $cat['status'] === 'Adopted' ? 'selected' : '' ?>>Adopted</option>
        </select>

        <label>Description</label>
        <textarea name="description"><?= htmlspecialchars($cat['description']) ?></textarea>

        <label>Health & Medical Notes</label>
        <textarea name="healthnmedical"><?= htmlspecialchars($cat['healthnmedical']) ?></textarea>

        <label>Image URL</label>
        <input type="text" name="image_path" value="<?= htmlspecialchars($cat['image_path']) ?>" required>

        <div class="button-group">
        <button type="submit" class="btn">Update</button>
        <a href="admin_view_cats.php" class="btn back-btn">Back to Cat List</a>
        </div>
        
    </form>
    
</div>

</body>
</html>
