<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "catadopt");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch current user data to prefill form
$user_id = $_SESSION['id'];
$user = [];
$result = $conn->query("SELECT * FROM users WHERE id = $user_id");
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    

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
        cat_sleep=?, indoor_outdoor=?, care_away=?
        WHERE id=?");

    $stmt->bind_param("ssssssssisssssssssi",
        $fname, $lname, $email, $phone, $address, $dob, $occupation,
        $house_type, $own_or_rent, $landlord_approval, $household_members,
        $children_in_house, $other_pets, $adopt_reason, $adopted_before,
        $cat_sleep, $indoor_outdoor, $care_away, $user_id);

    $catName = $_POST['cat_name'] ?? '';

    if ($stmt->execute()) {
        // Insert adoption record if catID exists
        if (isset($_GET['cat_id'])) {
            $catID = (int) $_GET['cat_id'];

            
                $stmt2 = $conn->prepare("INSERT INTO adoptions (user_id, cat_id,  adopted_at) VALUES (?, ?, NOW())");
                $stmt2->bind_param("ii", $user_id, $catID);
                $stmt2->execute();
                $stmt2->close();
            }
        

        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Adoption Form | FurEver</title>
    <link rel="stylesheet" href="style.css" />
    <link rel="icon" href="img/favicon.ico" type="image/x-icon">
</head>
<body>
    <nav>
        <div class="container">
            <a href="dashboard.php" class="brand">
                <img src="img/fureverhomeLogo.png" alt="Simple Logo" >
            </a>
            <ul class="nav-links">
                <li><a href="javascript:history.back()">Back</a></li>
                <li><a href="logout.php" >Log out</a></li>
            </ul>
            <div class="menu-toggle">☰</div>
        </div>
    </nav>
    <main>
        <div class="adoptform-container">
            <section>
                <h1 style="text-align: center;">Adopter's Information</h1>
                <form method="post" action="adopt_form.php?cat_id=<?= $_GET['cat_id'] ?? '' ?>">

                <?php if (isset($_GET['catID'])): ?>
                    <?php
                        $catID = (int) $_GET['catID'];
                        $catResult = $conn->query("SELECT name FROM cat WHERE catID = $catID");
                        $cat = $catResult && $catResult->num_rows > 0 ? $catResult->fetch_assoc() : null;
                        $catName = $cat['name'] ?? '';
                    ?>
                    <input type="hidden" name="cat_name" value="<?= htmlspecialchars($catName) ?>">
                <?php endif; ?>


                    <div class="form-row">
                        <div class="form-group">
                            <label for="fname">First Name</label>
                            <input type="text" name="fname" id="fname" required value="<?= htmlspecialchars($user['fname'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label for="lname">Last Name</label>
                            <input type="text" name="lname" id="lname" required value="<?= htmlspecialchars($user['lname'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" required value="<?= htmlspecialchars($user['email'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="text" name="phone" id="phone" required value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="dob">Date of Birth</label>
                            <input type="date" name="dob" id="dob" required value="<?= htmlspecialchars($user['date_of_birth'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label for="occupation">Occupation</label>
                            <input type="text" name="occupation" id="occupation" required value="<?= htmlspecialchars($user['occupation'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text" name="address" id="address" required value="<?= htmlspecialchars($user['address'] ?? '') ?>">
                    </div>

                    <h2>Living Situation</h2>

                    <div class="form-group">
                        <label for="house_type">House Type</label>
                        <input type="text" name="house_type" id="house_type" required value="<?= htmlspecialchars($user['house_type'] ?? '') ?>">
                    </div>

                    <div class="form-group">
                        <label for="own_or_rent">Do you own or rent?</label>
                        <input type="text" name="own_or_rent" id="own_or_rent" required value="<?= htmlspecialchars($user['own_or_rent'] ?? '') ?>">
                    </div>

                    <div class="form-group">
                        <label>Landlord Approval</label><br>
                        <div class="radio-group-centered">
                        <label class="radio-option">
                        <input type="radio" name="landlord_approval" value="Yes" <?= ($user['landlord_approval'] ?? '') === 'Yes' ? 'checked' : '' ?>> Yes
                        </label>
                        <label class="radio-option">
                        <input type="radio" name="landlord_approval" value="No" <?= ($user['landlord_approval'] ?? '') === 'No' ? 'checked' : '' ?>> No
                        </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="household_members">How many people live in your household?</label>
                        <input type="number" name="household_members" id="household_members" required value="<?= htmlspecialchars($user['household_members'] ?? '') ?>">
                    </div>

                    <div class="form-group">
                        <label for="children_in_house">Are there children in the household?</label>
                        <input type="text" name="children_in_house" id="children_in_house" required value="<?= htmlspecialchars($user['children_in_house'] ?? '') ?>">
                    </div>

                    <div class="form-group">
                        <label for="other_pets">Do you have other pets?</label>
                        <input type="text" name="other_pets" id="other_pets" required value="<?= htmlspecialchars($user['other_pets'] ?? '') ?>">
                    </div>

                    <h2>Cat Care Commitment</h2>

                    <div class="form-group">
                        <label for="adopt_reason">Why do you want to adopt a cat?</label>
                        <input type="text" name="adopt_reason" id="adopt_reason" required value="<?= htmlspecialchars($user['adopt_reason'] ?? '') ?>">
                    </div>

                    <div class="form-group">
                        <label>Have you adopted before?</label><br>
                        <div class="radio-group-centered">
                        <label class="radio-option"><input type="radio" name="adopted_before" value="Yes" <?= ($user['adopted_before'] ?? '') === 'Yes' ? 'checked' : '' ?>> Yes</label>
                        <label class="radio-option"><input type="radio" name="adopted_before" value="No" <?= ($user['adopted_before'] ?? '') === 'No' ? 'checked' : '' ?>> No</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="cat_sleep">Where will the cat sleep?</label>
                        <input type="text" name="cat_sleep" id="cat_sleep" required value="<?= htmlspecialchars($user['cat_sleep'] ?? '') ?>">
                    </div>

                    <div class="form-group">
                        <label>Will the cat be indoors, outdoors, or both?</label><br>
                        <div class="radio-group-centered">
                        <label class="radio-option"><input type="radio" name="indoor_outdoor" value="Indoor" <?= ($user['indoor_outdoor'] ?? '') === 'Indoor' ? 'checked' : '' ?>> Indoor</label>
                        <label class="radio-option"><input type="radio" name="indoor_outdoor" value="Outdoor" <?= ($user['indoor_outdoor'] ?? '') === 'Outdoor' ? 'checked' : '' ?>> Outdoor</label>
                        <label class="radio-option"><input type="radio" name="indoor_outdoor" value="Both" <?= ($user['indoor_outdoor'] ?? '') === 'Both' ? 'checked' : '' ?>> Both</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="care_away">How will you care for the cat when you're away?</label>
                        <input type="text" name="care_away" id="care_away" required value="<?= htmlspecialchars($user['care_away'] ?? '') ?>">
                    </div>

                    <button type="submit" class="btn">Submit</button>
                </form>
            </section>
        </div>
    </main>
</body>

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

</html>
