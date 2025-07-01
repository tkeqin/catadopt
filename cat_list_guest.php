<?php
$conn = mysqli_connect("localhost", "root", "", "catadopt"); 

$search = $_GET['search'] ?? '';
$breed = $_GET['breed'] ?? '';
$gender = $_GET['gender'] ?? '';
$location = $_GET['location'] ?? '';

$sql = "SELECT * FROM cat WHERE 1=1";

if (!empty($search)) {
  $searchSafe = mysqli_real_escape_string($conn, $search);
  $sql .= " AND name LIKE '%$searchSafe%'";
}
if (!empty($breed)) {
  $breedSafe = mysqli_real_escape_string($conn, $breed);
  $sql .= " AND breed LIKE '%$breedSafe%'";
}
if (!empty($gender)) {
  $genderSafe = mysqli_real_escape_string($conn, $gender);
  $sql .= " AND gender = '$genderSafe'";
}
if (!empty($location)) {
  $locationSafe = mysqli_real_escape_string($conn, $location);
  $sql .= " AND location LIKE '%$locationSafe%'";
}

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Cat List</title>
  <link rel="stylesheet" href="style.css" />
  <link rel="icon" href="img/favicon.ico" type="image/x-icon">
</head>
<body>
  <nav>
    <div class="container">
      <a href="index.html" class="brand">
        <img src="img/fureverhomeLogo.png" alt="Simple Logo" >
      </a>
      <ul class="nav-links">
        <!-- <li><a href="cats_list_guest.php">Cat List</a></li> -->
        <li><a href="login.php" class="btn-adopt">Login</a></li>
      </ul>
      <div class="menu-toggle" id="menu-toggle">☰</div>
    </div>
  </nav>

    <div class="side-menu" id="side-menu">
  <ul>
    <li><a href="login.php" class="btn-adopt">Login</a></li>
    <li><a href="register.php" class="btn-adopt">Sign Up</a></li> 
    <li><a href="cat_list_guest.php" class="btn-adopt">Cat List</a></li> 
    <li><a href="index.html" class="btn-adopt">Home</a></li> 

  </ul>
  </div>
<section>
  <main>
    <h1 style="text-align:center; margin-top:24px; color:#251d17;">Available Cats for Adoption</h1>
    <form method="GET" class="search-form">
      <input type="text" name="search" placeholder="Search by name" value="<?= htmlspecialchars($search) ?>" />
  
      <input type="text" name="breed" placeholder="Breed" value="<?= htmlspecialchars($breed) ?>" />
  
      <select name="gender">
        <option value="">All Genders</option>
        <option value="Male" <?= $gender == 'Male' ? 'selected' : '' ?>>Male</option>
        <option value="Female" <?= $gender == 'Female' ? 'selected' : '' ?>>Female</option>
      </select>
  
      <input type="text" name="location" placeholder="Location" value="<?= htmlspecialchars($location) ?>" />
  
      <div class="button-group">
        <button type="submit" class="btn-search">🔍 Search</button>
        <a href="cat_list_guest.php" class="btn-reset">⟳ Reset</a>
      </div>
    </form>

    <div class="cat-list">
      <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <div class="cat-card">
          <img src="<?php echo $row['image_path']; ?>" alt="<?php echo $row['name']; ?>" />
          <div class="info">
            <h2><?php echo $row['name']; ?></h2>
            <p><strong>Breed:</strong> <?php echo $row['breed']; ?></p>
            <p><strong>Age:</strong> <?php echo $row['age']; ?> years</p>
          </div>
          <div class="actions">
            <a href="cat_detail.php?catID=<?php echo $row['catID']; ?>" class="btn-detail">View Details</a>
          </div>
        </div>
      <?php } ?>
    </div>
  </main>
  </section>
      <script src="menu.js"></script>
      
</body>

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

</html>
