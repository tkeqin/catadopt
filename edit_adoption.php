<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login.html");
    exit();
}

$conn = new mysqli("localhost", "root", "", "catadopt");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['id'];

// Ensure an adoption ID is provided
if (!isset($_GET['id'])) {
    die("Invalid request.");
}

$adoption_id = intval($_GET['id']);

// Check if the adoption belongs to the user and is still pending
$sql = "SELECT * FROM adoptions WHERE id = $adoption_id AND user_id = $user_id AND status = 'Pending'";
$result = $conn->query($sql);
if ($result->num_rows !== 1) {
    die("Invalid or unauthorized request.");
}

$adoption = $result->fetch_assoc();

// Fetch user profile to pre-fill form
$user_result = $conn->query("SELECT * FROM users WHERE id = $user_id");
$user = $user_result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture updated values
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
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

    $stmt = $conn->prepare("UPDATE users SET 
        fname=?, lname=?, email=?, phone=?, address=?, date_of_birth=?, occupation=?,
        house_type=?, own_or_rent=?, landlord_approval=?, household_members=?,
        children_in_house=?, other_pets=?, adopt_reason=?, adopted_before=?, 
        cat_sleep=?, indoor_outdoor=?, care_away=? WHERE id=?");

    $stmt->bind_param("ssssssssisssssssssi",
        $fname, $lname, $email, $phone, $address, $dob, $occupation,
        $house_type, $own_or_rent, $landlord_approval, $household_members,
        $children_in_house, $other_pets, $adopt_reason, $adopted_before,
        $cat_sleep, $indoor_outdoor, $care_away, $user_id);

    if ($stmt->execute()) {
        header("Location: dashboard.php?msg=updated");
        exit();
    } else {
        echo "Error updating record: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Edit Adoption Form</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="account-login">
<main>
    <div class="adoptform-container">
        <h1 style="text-align:center;">Edit Your Adoption Request</h1>
        <form method="post">
            <div class="form-row">
                <div class="form-group">
                    <label for="fname">First Name</label>
                    <input type="text" name="fname" required value="<?= htmlspecialchars($user['fname']) ?>">
                </div>
                <div class="form-group">
                    <label for="lname">Last Name</label>
                    <input type="text" name="lname" required value="<?= htmlspecialchars($user['lname']) ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" required value="<?= htmlspecialchars($user['email']) ?>">
                </div>
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="text" name="phone" required value="<?= htmlspecialchars($user['phone']) ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="dob">Date of Birth</label>
                    <input type="date" name="dob" required value="<?= htmlspecialchars($user['date_of_birth']) ?>">
                </div>
                <div class="form-group">
                    <label for="occupation">Occupation</label>
                    <input type="text" name="occupation" required value="<?= htmlspecialchars($user['occupation']) ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" name="address" required value="<?= htmlspecialchars($user['address']) ?>">
            </div>

            <h2>Living Situation</h2>
            <div class="form-group">
                <label for="house_type">House Type</label>
                <input type="text" name="house_type" required value="<?= htmlspecialchars($user['house_type']) ?>">
            </div>

            <div class="form-group">
                <label for="own_or_rent">Do you own or rent?</label>
                <input type="text" name="own_or_rent" required value="<?= htmlspecialchars($user['own_or_rent']) ?>">
            </div>

            <div class="form-group">
                <label>Landlord Approval</label>
                <div class="radio-group-centered">
                    <label class="radio-option"><input type="radio" name="landlord_approval" value="Yes" <?= $user['landlord_approval'] === 'Yes' ? 'checked' : '' ?>> Yes</label>
                    <label class="radio-option"><input type="radio" name="landlord_approval" value="No" <?= $user['landlord_approval'] === 'No' ? 'checked' : '' ?>> No</label>
                </div>
            </div>

            <div class="form-group">
                <label for="household_members">Household Members</label>
                <input type="number" name="household_members" required value="<?= htmlspecialchars($user['household_members']) ?>">
            </div>

            <div class="form-group">
                <label for="children_in_house">Children in House</label>
                <input type="text" name="children_in_house" required value="<?= htmlspecialchars($user['children_in_house']) ?>">
            </div>

            <div class="form-group">
                <label for="other_pets">Other Pets</label>
                <input type="text" name="other_pets" required value="<?= htmlspecialchars($user['other_pets']) ?>">
            </div>

            <h2>Cat Care</h2>

            <div class="form-group">
                <label for="adopt_reason">Why Adopt?</label>
                <input type="text" name="adopt_reason" required value="<?= htmlspecialchars($user['adopt_reason']) ?>">
            </div>

            <div class="form-group">
                <label>Adopted Before?</label>
                <div class="radio-group-centered">
                    <label class="radio-option"><input type="radio" name="adopted_before" value="Yes" <?= $user['adopted_before'] === 'Yes' ? 'checked' : '' ?>> Yes</label>
                    <label class="radio-option"><input type="radio" name="adopted_before" value="No" <?= $user['adopted_before'] === 'No' ? 'checked' : '' ?>> No</label>
                </div>
            </div>

            <div class="form-group">
                <label for="cat_sleep">Where will the cat sleep?</label>
                <input type="text" name="cat_sleep" required value="<?= htmlspecialchars($user['cat_sleep']) ?>">
            </div>

            <div class="form-group">
                <label>Indoor or Outdoor?</label>
                <div class="radio-group-centered">
                    <label class="radio-option"><input type="radio" name="indoor_outdoor" value="Indoor" <?= $user['indoor_outdoor'] === 'Indoor' ? 'checked' : '' ?>> Indoor</label>
                    <label class="radio-option"><input type="radio" name="indoor_outdoor" value="Outdoor" <?= $user['indoor_outdoor'] === 'Outdoor' ? 'checked' : '' ?>> Outdoor</label>
                    <label class="radio-option"><input type="radio" name="indoor_outdoor" value="Both" <?= $user['indoor_outdoor'] === 'Both' ? 'checked' : '' ?>> Both</label>
                </div>
            </div>

            <div class="form-group">
                <label for="care_away">Care while away?</label>
                <input type="text" name="care_away" required value="<?= htmlspecialchars($user['care_away']) ?>">
            </div>

            <button type="submit" class="btn">Update</button>
        </form>
    </div>
</main>
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
</body>
</html>
