<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

// Connect to DB
$conn = new mysqli("localhost", "root", "", "catadopt");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Sanitize input
$user_id = $_SESSION['id'];
$adoption_id = intval($_POST['adoption_id']);
$feedback = trim($_POST['feedback']);

if (!empty($feedback)) {
    $stmt = $conn->prepare("INSERT INTO feedback (user_id, adoption_id, feedback_text, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("iis", $user_id, $adoption_id, $feedback);
    
    if ($stmt->execute()) {
        header("Location: dashboard.php?success=feedback"); // redirect back to your desired page
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    header("Location: dashboard.php?error=empty_feedback");
}

$conn->close();
?>
