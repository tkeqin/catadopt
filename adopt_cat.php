<?php
//submit adoption
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cat_id = $_POST['cat_id'];
    $cat_name = $_POST['cat_name']; // include this from form or fetch from DB

    $conn = new mysqli("localhost", "root", "", "catadopt");
    if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

    // Optional: Check if already adopted by this user

    $stmt = $conn->prepare("INSERT INTO adoptions (user_id, cat_id, cat_name, adopted_at) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("iis", $user_id, $cat_id, $cat_name);

    if ($stmt->execute()) {
        header("Location: dashboard.php?adopted=1");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
