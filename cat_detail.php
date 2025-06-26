
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Cat Adoption - Register</title>
    <link rel="stylesheet" href="style.css" />
</head>
<body>
    <nav>
        <div class="container">
            <a href="dashboard.php" class="brand">Simple</a>
            <ul class="nav-links">
                <li><a href="cats_list_user.html">Cat List</a></li>
                <li><a href="login.html" class="btn-adopt">Login</a></li>
            </ul>
            <div class="menu-toggle">☰</div>
        </div>
    </nav>

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
                    echo "<div style='border:1px solid #ccc; padding:15px; margin-bottom:10px'>";
                    echo "<div style='text-align: center;'>";
                    echo "<img src='" . htmlspecialchars($row["image_url"]) . "' alt='Cat Image' style='max-width:200px;'><br>";
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
</body>
</html>






