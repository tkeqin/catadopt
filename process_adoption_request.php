<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id'], $_POST['action'])) {
    $id = intval($_POST['id']);
    $action = $_POST['action'] === 'approve' ? 'approved' : 'rejected';

    $conn = new mysqli("localhost", "root", "", "catadopt");
    if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

    $stmt = $conn->prepare("UPDATE adoptions SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $action, $id);

    if ($stmt->execute()) {
        header("Location: admin_adoption_requests.php");
    } else {
        echo "Failed to update status.";
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: admin_adoption_requests.php");
}
