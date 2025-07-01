<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "catadopt");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$success = "";
$error = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'];
    $breed = $_POST['breed'];
    $age = $_POST['age'];

    // Handle image upload
    $image_path = "";
    if (isset($_FILES['cat_image']) && $_FILES['cat_image']['error'] == 0) {
        $target_dir = "images/";
        $image_name = basename($_FILES["cat_image"]["name"]);
        $target_file = $target_dir . time() . "_" . $image_name;

        if (move_uploaded_file($_FILES["cat_image"]["tmp_name"], $target_file)) {
            $image_path = $target_file;
        } else {
            $error = "Image upload failed.";
        }
    }

    if (empty($error)) {
        $stmt = $conn->prepare("INSERT INTO cat (name, breed, age, image_path) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssis", $name, $breed, $age, $image_path);

        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
            header("Location: admin_view_cats.php?success=1");
            exit();
        } else {
            $error = "Database error: " . $stmt->error;
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Add Cat</title>
  <link rel="stylesheet" href="style.css">
  <link rel="icon" href="img/favicon.ico" type="image/x-icon">
</head>
<body>
<nav>
  <div class="container">
    <a href="admin_dashboard.php" class="brand">FurEver Admin</a>
    <ul class="nav-links">
      <li><a href="dashboard.php">Dashboard</a></li>
      <li><a href="cats_list_usr.html">Cat List</a></li>
    </ul>
    <div class="menu-toggle">☰</div>
  </div>
</nav>

<main>
  <div class="account-container">
    <section class="register-form">
      <h1 style="text-align:center;">Add a New Cat</h1>

      <?php if ($success): ?>
        <p style="color:green;text-align:center;"><?= $success ?></p>
      <?php elseif ($error): ?>
        <p style="color:red;text-align:center;"><?= $error ?></p>
      <?php endif; ?>

      <form method="POST" action="add_cat.php" enctype="multipart/form-data">
        <label>Cat Name</label>
        <input type="text" name="name" required>

        <label>Breed</label>
        <input type="text" name="breed" required>

        <label>Age (years)</label>
        <input type="number" name="age" step="0.1" required>

        <label>Upload Image (optional)</label>
        <input type="file" name="cat_image" accept="image/*">

        <button type="submit" class="btn">Add Cat</button>
      </form>
    </section>
  </div>
</main>
</body>
</html>