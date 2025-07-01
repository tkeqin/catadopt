<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $_SESSION['users'] = [
        'email' => $email
    ];

    $conn = new mysqli("localhost", "root", "", "catadopt");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "INSERT INTO users (email, password)
            VALUES ('$email', '$hashed_password')";

    if ($conn->query($sql) === TRUE) {
        header("Location: login.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    
    $check = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($check);
    if ($result->num_rows > 0) {
        echo "<script>alert('❌ Email already registered'); window.history.back();</script>";
        header("Location: register.php?error=Email already registered");
        exit();
    }

    $conn->close();
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
    <nav >
        <div class="container">
            <a href="dashboard.php" class="brand">Simple</a>
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
    <section >
    <main>
        <div class="account-container">
            <section class="register-form">
                <h1 style="text-align: center;">Register</h1>
                <form method="post" action="register.php" id="UserLoginForm">
                    
                    <label for="UserEmail">Email Address</label>
                    <input type="email" name="email" id="UserEmail" required>
                    
                    <label for="UserPassword">Password</label>
                    <input type="password" name="password" id="UserPassword" required>

                    <button type="submit" class="btn">Submit</button>
                </form>
                <p class="cancel">
                    <a href="login.php">Cancel</a>
                </p>
            </section>
        </div>
    </main>
        <script src="menu.js"></script>
        </section>
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
