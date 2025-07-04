
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Cat Detail | FurEver</title>
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
                <li><a href="cat_list_user.php">Back</a></li>
                <li><a href="logout.php" onclick="return confirm('Are you sure you want to log out?')">Log out</a></li>
            </ul>
            <div class="menu-toggle" id="menu-toggle">☰</div>
        </div>
    </nav>

    <div class="side-menu" id="side-menu">
    <ul>
      <li><a href="cat_list_user.php" class="btn-adopt">Cat List</a></li> 
            <li><a href="dashboard.php" class="btn-adopt">Home</a></li> 
            <li><a href="logout.php" class="btn-adopt">Log out</a></li> 

    </ul>
  </div>

<section>
    <main>
        <div class="catdetail-container">
        <?php
            // Connect to database
            $host = "localhost";
            $user = "root";
            $password = "";
            $database = "catadopt";

            $conn = mysqli_connect($host, $user, $password, $database);

            // Check connection
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }

            // Check if catID is provided
            if (isset($_GET['catID'])) {
                $catID = intval($_GET['catID']); // sanitize the input (important)

                // Create and run SQL query
                $sql = "SELECT * FROM cat WHERE catID = $catID";
                $result = mysqli_query($conn, $sql);

                // Display result
                if (mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
                    
                    echo "<div style='text-align: center;'>";
                    echo "<img src='" . htmlspecialchars($row["image_path"]) . "' alt='Cat Image' class='cat-image'><br>";
                    echo "</div>";
                    echo "<h2>" . htmlspecialchars($row["name"]) . "</h2>";
                    echo "<strong>Breed:</strong> " . htmlspecialchars($row["breed"]) . "<br>";
                    echo "<strong>Gender:</strong> " . htmlspecialchars($row["gender"]) . "<br>";
                    echo "<strong>Age:</strong> " . htmlspecialchars($row["age"]) . " year(s)<br>";
                    echo "<strong>Location:</strong> " . htmlspecialchars($row["location"]) . "<br>";
                    echo "<strong>Health Status:</strong> " . htmlspecialchars($row["health_status"]) . "<br>";
                    echo "<strong>Status:</strong> " . htmlspecialchars($row["status"]) . "<br>";
                    echo "<p><strong>Description:</strong><br>" . nl2br(htmlspecialchars($row["description"])) . "</p>";
                    echo "<p><strong>Health & Medical Notes:</strong><br>" . nl2br(htmlspecialchars($row["healthnmedical"])) . "</p>";
                    echo "<small>Added on: " . $row["date_added"] . " | Last updated: " . $row["last_updated"] . "</small>";
                    echo "</div>";
                } else {
                    echo "Cat not found.";
                }

                mysqli_free_result($result);
            } else {
                echo "No cat selected.";
            }

            mysqli_close($conn);
        ?>
        </div>
    </main>
    <section>
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






