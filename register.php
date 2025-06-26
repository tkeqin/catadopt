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
        header("Location: login.html");
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
</head>
<body>
    <nav>
        <div class="container">
            <a href="dashboard.php" class="brand">Simple</a>
            <ul class="nav-links">
                <li><a href="cats_list_guest.html">Cat List</a></li>
                <li><a href="login.html" class="btn-adopt">Login</a></li>
            </ul>
            <div class="menu-toggle">☰</div>
        </div>
    </nav>

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
</body>
</html>
