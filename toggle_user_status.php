<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id']) || !isset($_GET['action'])) {
    header("Location: manage_users.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "catadopt");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$id = intval($_GET['id']);
$action = $_GET['action'];

$status = ($action === 'suspend') ? 'suspended' : 'active';

$stmt = $conn->prepare("UPDATE users SET status = ? WHERE id = ?");
$stmt->bind_param("si", $status, $id);

if ($stmt->execute()) {
    $stmt->close();
    $conn->close();
    header("Location: manage_users.php");
    exit();
} else {
    $stmt->close();
    $conn->close();
    header("Location: manage_users.php?error=UpdateFailed");
    exit();
}
?>
