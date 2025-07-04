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

    // Update adoption status
    $stmt = $conn->prepare("UPDATE adoptions SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $action, $id);

   if ($stmt->execute()) {
    if ($action === 'approved') {
        // Fetch cat_id
        $catQuery = $conn->prepare("SELECT cat_id FROM adoptions WHERE id = ?");
        $catQuery->bind_param("i", $id);
        $catQuery->execute();
        $catQuery->bind_result($cat_id);

        if ($catQuery->fetch()) {
            $catQuery->close();

            // Mark cat as adopted
            $updateCat = $conn->prepare("UPDATE cat SET status = 'Adopted' WHERE catID = ?");
            $updateCat->bind_param("i", $cat_id);
            $updateCat->execute();
            $updateCat->close();

            // Reject other requests
            $rejectOthers = $conn->prepare("UPDATE adoptions SET status = 'rejected' WHERE cat_id = ? AND id != ? AND status = 'pending'");
            $rejectOthers->bind_param("ii", $cat_id, $id);
            $rejectOthers->execute();
            $rejectOthers->close();
        } else {
            $catQuery->close();
        }
    }

    header("Location: admin_adoption_requests.php");
    exit();
    } else {
        echo "Failed to update status.";
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: admin_adoption_requests.php");
    exit();
}
