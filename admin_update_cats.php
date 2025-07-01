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
    $image_url = $_POST['image_url'];
    $last_updated = date('Y-m-d H:i:s');

    $stmt = $conn->prepare("UPDATE cat SET name=?, breed=?, age=?, gender=?, location=?, health_status=?, status=?, description=?, healthnmedical=?, image_url=?, last_updated=? WHERE catID=?");
    $stmt->bind_param("ssissssssssi", $name, $breed, $age, $gender, $location, $health_status, $status, $description, $healthnmedical, $image_url, $last_updated, $catID);

    if ($stmt->execute()) {
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
    <link rel="stylesheet" href="style.css" />
    <link rel="icon" href="img/favicon.ico" type="image/x-icon">
    <style>
        .form-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            background-color: #fdfdfd;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        label {
            font-weight: bold;
            display: block;
            margin-top: 1rem;
        }
        input[type="text"],
        input[type="number"],
        textarea,
        select {
            width: 100%;
            padding: 0.5rem;
            margin-top: 0.25rem;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        textarea {
            resize: vertical;
        }
        .form-img {
            text-align: center;
            margin: 1rem 0;
        }
        .form-img img {
            max-width: 200px;
            border-radius: 6px;
        }
        .btn-submit {
            margin-top: 1.5rem;
            padding: 0.75rem 2rem;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        .btn-submit:hover {
            background-color: #0056b3;
        }
        .msg {
            margin-top: 1rem;
            padding: 0.75rem;
            text-align: center;
            border-radius: 5px;
        }
        .msg.success {
            background-color: #d4edda;
            color: #155724;
        }
        .msg.error {
            background-color: #f8d7da;
            color: #721c24;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 1rem;
            color: #007BFF;
        }
    </style>
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
        <img src="<?= htmlspecialchars($cat['image_url']) ?>" alt="Cat Image">
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

        <button type="submit" class="btn-submit">Update</button>
    </form>

    <a href="admin_view_cats.php" class="back-link">← Back to Cat List</a>
</div>

</body>
</html>
