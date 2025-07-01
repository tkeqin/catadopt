<?php
session_start();

// Check form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Check if email and password exist
    if (isset($_POST['email']) && isset($_POST['password'])) {

        // Function to clean inputs
        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        // Sanitize input
        $email = test_input($_POST['email']);
        $password = test_input($_POST['password']);

        // Check for empty input
        if (empty($email)) {
            header("Location: login.php?error=Email is required");
            exit();
        } else if (empty($password)) {
            header("Location: login.php?error=Password is required");
            exit();
        }

        // Connect to database
        $conn = new mysqli("localhost", "root", "", "catadopt");

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Escape email for safety
        $email = $conn->real_escape_string($email);

        // Fetch user by email
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = $conn->query($sql);

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();

            // Validate password
            if (password_verify($password, $row['password'])) {
                // Assign session variables
                $_SESSION['id'] = $row['id'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['role'] = $row['role'];

                if ($row['role'] === 'admin') {
                    $conn->close();
                    header("Location: admin_dashboard.php");
                    exit();
                } else {
                    $conn->close();
                    header("Location: dashboard.php");
                    exit();
                }
            
            } else {
                header("Location: login.php?error=Incorrect password");
                exit();
            }
        } else {
            header("Location: login.php?error=Email not found");
            exit();
        }  
    } else {
        header("Location: login.php?error=Please fill in the form");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Cat Adoption - Register</title>
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
                <li><a href="cat_list_guest.php">Cat List</a></li>
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
        <div class="account-container">
            <section class="login-form">
                <h1 style="text-align: center;">Login</h1>

                <?php if (isset($_GET['error'])): ?>
                    <p style="color: red; text-align: center;"><?php echo $_GET['error']; ?></p>
                <?php endif; ?>

                <form method="post" action="login.php">
                    <label for="UserEmail">Email Address</label>
                    <input type="email" name="email" id="UserEmail" required>

                    <label for="UserPassword">Password</label>
                    <input type="password" name="password" id="UserPassword" required>

                    <button type="submit" class="btn">Login</button>
                </form>

                <p class="create-account">
                    <a href="register.php">Create account</a>
                </p>
            </section>
        </div>
    </main>
                </section>
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
