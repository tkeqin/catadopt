<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "catadopt");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$user_id = $_SESSION['id'];

// Handle form submission

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    // Use existing avatar as default
    $image_path = $user['avatar']; 

    // Check if new file is uploaded
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
        $target_dir = "uploads/"; // Ensure this folder exists and is writable
        $image_name = basename($_FILES["avatar"]["name"]);
        $target_file = $target_dir . time() . "_" . $image_name;

        if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_file)) {
            $image_path = $target_file;
        } else {
            // Optional error handling
        }
    }

    // Update user info including avatar path
    $stmt = $conn->prepare("UPDATE users SET fname=?, email=?, phone=?, address=?, avatar=? WHERE id=?");
    $stmt->bind_param("sssssi", $name, $email, $phone, $address, $image_path, $user_id);
    $stmt->execute();
    $stmt->close();

    header("Location: profile.php?success=1");
    exit();
}


// Fetch user data
$result = $conn->query("SELECT * FROM users WHERE id = $user_id");
$user = $result->fetch_assoc();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>User Profile</title>
  <link rel="stylesheet" href="style.css" />
  <link rel="icon" href="img/favicon.ico" type="image/x-icon">
</head>
<body>
<nav>
  <div class="container">
    <a href="dashboard.php" class="brand">
      <img src="img/fureverhomeLogo.png" alt="Simple Logo" >
    </a>
    <ul class="nav-links">
      <li><a href="cat_list_user.php">Cat List</a></li>
    </ul>
    <div class="menu-toggle" id="menu-toggle">☰</div>
  </div>
</nav>

  <div class="side-menu" id="side-menu">
  <ul>
    
    <li><a href="cat_list_user.php" class="btn-adopt">Cat List</a></li> 
    <li><a href="dashboard.html" class="btn-adopt">Home</a></li> 
    <li><a href="login.php" class="btn-adopt">Log Out</a></li> 

  </ul>
  </div>

<section>
<div class="profile-page">
  <h1>User Profile</h1>

  <?php if (isset($_GET['success'])): ?>
    <p style="color: orange; text-align: center;">Profile updated successfully!</p>
  <?php endif; ?>

  <form class="profile-form" method="post" enctype="multipart/form-data">
    <div class="avatar-section">
      <img src="<?= $user['avatar'] ? htmlspecialchars($user['avatar']) : 'https://via.placeholder.com/100' ?>" alt="User Avatar" id="avatarPreview" />

      <input type="file" name="avatar" id="avatarInput" accept="image/*" disabled />
    </div>

    <label>
      Name:
      <input type="text" name="name" value="<?= htmlspecialchars($user['fname']) ?>" disabled />
    </label>

    <label>
      Email:
      <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" disabled />
    </label>

    <label>
      Phone:
      <input type="tel" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" disabled />
    </label>

    <label>
      Address:
      <input type="text" name="address" value="<?= htmlspecialchars($user['address']) ?>" disabled />
    </label>

    <div class="form-buttons">
      <button type="button" id="editBtn">Edit</button>
      <button type="submit" id="saveBtn" disabled>Save</button>
    </div>
  </form>
</div>
  </section>
<script>
  const editBtn = document.getElementById('editBtn');
  const saveBtn = document.getElementById('saveBtn');
  const inputs = document.querySelectorAll('.profile-form input');
  const avatarInput = document.getElementById('avatarInput');

  editBtn.addEventListener('click', () => {
    inputs.forEach(input => input.disabled = false);
    avatarInput.disabled = false;
    saveBtn.disabled = false;
  });

  avatarInput.addEventListener('change', (e) => {
    const file = e.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function(event) {
        document.getElementById('avatarPreview').src = event.target.result;
      };
      reader.readAsDataURL(file);
    }
  });
</script>

<footer class="site-footer">
  <div class="footer-container">
    <div class="footer-left">
      <p>&copy; 2025 FurEver Home. All rights reserved.</p>
    </div>
    <div class="footer-right">
      <a href="#">Privacy Policy</a>
      <a href="#">Terms of Service</a>
     
    </div>
  </div>
</footer>

<script src="menu.js"></script>
</body>
</html>
