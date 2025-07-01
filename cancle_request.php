<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login.html");
    exit();
}

if (!isset($_GET['id'])) {
    echo "Invalid request ID.";
    exit();
}

$adoption_id = (int) $_GET['id'];
$user_id = $_SESSION['id'];

$conn = new mysqli("localhost", "root", "", "catadopt");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Only allow cancellation of pending requests that belong to this user
$sql = "DELETE FROM adoptions WHERE id = ? AND user_id = ? AND status = 'Pending'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $adoption_id, $user_id);

if ($stmt->execute() && $stmt->affected_rows > 0) {
    header("Location: dashboard.php?message=Adoption+request+cancelled");
    exit();
} else {
    echo "Unable to cancel. It might already be approved or not found.";
}

$stmt->close();
$conn->close();
?>
