<?php
session_start();

// Only allow admin to access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Check if catID is provided
if (!isset($_GET['catID'])) {
    header("Location: admin_view_cats.php?error=MissingCatID");
    exit();
}

$catID = intval($_GET['catID']);

// Connect to the database
$conn = new mysqli("localhost", "root", "", "catadopt");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Delete the cat
$stmt = $conn->prepare("UPDATE cat SET deleted = 1 WHERE catID = ?");
$stmt->bind_param("i", $catID);

if ($stmt->execute()) {
    $stmt->close();
    $conn->close();
    header("Location: admin_view_cats.php?removed=1");
    exit();
} else {
    $stmt->close();
    $conn->close();
    header("Location: admin_view_cats.php?error=DeleteFailed");
    exit();
}
?>
