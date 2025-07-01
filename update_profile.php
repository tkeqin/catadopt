<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$conn = new mysqli("localhost", "root", "", "catadopt");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$user_id = $_SESSION['user_id'];

// Only process if form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get data from POST
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $dob = $_POST['dob'];
    $occupation = $_POST['occupation'];
    $house_type = $_POST['house_type'];
    $own_or_rent = $_POST['own_or_rent'];
    $landlord_approval = $_POST['landlord_approval'];
    $household_members = $_POST['household_members'];
    $children_in_house = $_POST['children_in_house'];
    $other_pets = $_POST['other_pets'];
    $adopt_reason = $_POST['adopt_reason'];
    $adopted_before = $_POST['adopted_before'];
    $cat_sleep = $_POST['cat_sleep'];
    $indoor_outdoor = $_POST['indoor_outdoor'];
    $care_away = $_POST['care_away'];

    // Update query
    $stmt = $conn->prepare("UPDATE users SET
        fname=?, lname=?, phone=?, address=?, date_of_birth=?, occupation=?,
        house_type=?, own_or_rent=?, landlord_approval=?, household_members=?, 
        children_in_house=?, other_pets=?, adopt_reason=?, adopted_before=?, 
        cat_sleep=?, indoor_outdoor=?, care_away=?
        WHERE id=?");

    $stmt->bind_param("ssssssssissssssssi",
        $fname, $lname, $phone, $address, $dob, $occupation,
        $house_type, $own_or_rent, $landlord_approval, $household_members,
        $children_in_house, $other_pets, $adopt_reason, $adopted_before,
        $cat_sleep, $indoor_outdoor, $care_away, $user_id);

    if ($stmt->execute()) {
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}

// Optional: Fetch user info if you want to prefill form
$result = $conn->query("SELECT * FROM users WHERE id = $user_id");
$user = $result->fetch_assoc();

$conn->close();
?>
