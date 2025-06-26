<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the input values from the form
    $fname = $_POST['customer']['fname'];
    $lname = $_POST['customer']['lname'];
    $email = $_POST['customer']['email'];
    $phone = $_POST['customer']['phone'];
    $address = $_POST['customer']['address'];
    $dob = $_POST['customer']['dob'];
    $occupation = $_POST['customer']['occupation'];

    $house_type = $_POST['customer']['house_type'];
    $own_or_rent = $_POST['customer']['own_or_rent'];
    $landlord_approval = $_POST['customer']['landlord_approval'];
    $household_members = $_POST['customer']['household_members'];
    $children_in_house = $_POST['customer']['children_in_house'];
    $other_pets = $_POST['customer']['other_pets'];

    $adopt_reason = $_POST['customer']['adopt_reason'];
    $adopted_before = $_POST['customer']['adopted_before'];
    $cat_sleep = $_POST['customer']['cat_sleep'];
    $indoor_outdoor = $_POST['customer']['indoor_outdoor'];
    $care_away = $_POST['customer']['care_away'];

    // Hash the password for security
    $hashed_password = password_hash($_POST['customer']['password'], PASSWORD_DEFAULT);

    $_SESSION['customer'] = $_POST['customer'];

    // Database connection
    $conn = new mysqli("localhost", "username", "password", "database_name");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to insert data into the database
    $sql = "INSERT INTO adopter_personal_info (fname, lname, email, phone, address, date_of_birth, occupation, house_type, own_or_rent, landlord_approval, household_members, children_in_house, other_pets, adopt_reason, adopted_before, cat_sleep, indoor_outdoor, care_away)
            VALUES ('$fname', '$lname' ,'$email', '$phone', '$address', '$dob', '$occupation', '$house_type', '$own_or_rent', '$landlord_approval', '$household_members', '$children_in_house', '$other_pets', '$adopt_reason', '$adopted_before', '$cat_sleep', '$indoor_outdoor', '$care_away')";

    if ($conn->query($sql) === TRUE) {
        header("Location: living_situation.php");  // Redirect to the next page
        exit();  // Exit to ensure further code does not run
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Cat Adoption - Register</title>
    <link rel="stylesheet" href="style.css" />
</head>
<body>
    <nav>
        <div class="container">
            <a href="dashboard.php" class="brand">Simple</a>
            <ul class="nav-links">
                <li><a href="cats_list_guest.php">Cat List</a></li>
                <li><a href="login.html" class="btn-adopt">Login</a></li>
            </ul>
            <div class="menu-toggle">☰</div>
        </div>
    </nav>

    <main>
        <div class="adoptform-container">
            <section>
                <h1 style="text-align: center;">Register to become an Adopter</h1>
                <form method="post" action="register.php" id="CustomerLoginForm">
                    <h1 style="text-align: center;">Adopter's Information</h1>
                    
                        <div class="form-row">
                            <div class="form-group">
                                <label for="CustomerName">Full Name</label>
                                <input type="text" name="customer[name]" id="CustomerName" value="" required>
                            </div>
                            <div class="form-group">
                                <label for="CustomerLastName">Last Name</label>
                                <input type="text" name="customer[last_name]" id="CustomerLastName" value="" required>
                            </div>
                        </div>

                        <!-- Second Row: Email Address & Phone Number -->
                        <div class="form-row">
                            <div class="form-group">
                                <label for="CustomerEmail">Email Address</label>
                                <input type="email" name="customer[email]" id="CustomerEmail" value="" required>
                            </div>
                            <div class="form-group">
                                <label for="CustomerPhone">Phone Number</label>
                                <input type="text" name="customer[phone]" id="CustomerPhone" value="" required>
                            </div>
                        </div>

                    
                        <div class="form-row">
                        <div class="form-group">
                            <label for="CustomerDOB">Date of Birth</label>
                            <input type="date" name="customer[dob]" id="CustomerDOB" required>
                        </div>

                        <div class="form-group">
                            <label for="CustomerOccupation">Occupation</label>
                            <input type="text" name="customer[occupation]" id="CustomerOccupation" required>
                        </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="CustomerAddress">Address</label>
                                <input type="text" name="customer[address]" id="CustomerAddress" value="" required>
                            </div>
                        </div>


                    <h1 style="text-align: center;">Living Situation</h1>

                    <div class="form-group">
                        <label for="HouseType">Do you live in a house or apartment?</label>
                        <input type="text" name="customer[house_type]" id="HouseType" value="" required>
                    </div>

                    <div class="form-group">
                        <label for="OwnOrRent">Do you own or rent?</label>
                        <input type="text" name="customer[own_or_rent]" id="OwnOrRent" value="" required>
                    </div>

                    <div class="form-group">
                    <label for="LandlordApproval">If renting, do you have landlord approval for a pet?</label>
                    <div class="radio-group-centered">
                        <label class="radio-option">
                        <input type="radio" name="customer[landlord_approval]" value="Yes" required> Yes
                        </label>
                        <label class="radio-option">
                        <input type="radio" name="customer[landlord_approval]" value="No" required> No
                        </label>
                    </div>
                    </div>

                    <div class="form-group">
                        <label for="HouseholdMembers">How many people live in your household?</label>
                        <input type="text" name="customer[household_members]" id="HouseholdMembers" value="" required>
                    </div>

                    <div class="form-group">
                        <label for="ChildrenInHouse">Are there children in the household?</label>
                        <input type="text" name="customer[children_in_house]" id="ChildrenInHouse" required>
                    </div>

                    <div class="form-group">
                        <label for="OtherPets">Do you have other pets? If yes, provide details.</label>
                        <input type="text" name="customer[other_pets]" id="OtherPets" value="" required>
                    </div>

                    <h1 style="text-align: center;">Cat Care Commitment</h1>

                    <div class="form-group">
                        <label for="AdoptReason">Why do you want to adopt a cat?</label>
                        <input type="text" name="customer[adopt_reason]" id="AdoptReason" value="" required>
                    </div>

                    <div class="form-group">
                        <label for="AdoptedBefore">Have you adopted a pet before?</label>
                        <div class="radio-group-centered">
                        <label class="radio-option">
                        <input type="radio" name="customer[adopted_before]" value="Yes" required> Yes
                        </label>
                        <label class="radio-option">
                        <input type="radio" name="customer[adopted_before]" value="No" required> No
                        </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="CatSleep">Where will the cat sleep?</label>
                        <input type="text" name="customer[cat_sleep]" id="CatSleep" value="" required>
                    </div>

                    <div class="form-group">
                        <label for="IndoorOutdoor">Will the cat be indoors, outdoors, or both?</label>
                        <div class="radio-group-centered">
                        <label class="radio-option">
                        <input type="radio" name="customer[indoor_outdoor]" value="Indoor" required> Indoor
                        </label>
                        <label class="radio-option">
                        <input type="radio" name="customer[indoor_outdoor]" value="Outdoor" required> Outdoor
                        </label>
                        <label class="radio-option">
                        <input type="radio" name="customer[indoor_outdoor]" value="Both" required> Both
                        </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="CareAway">How will you care for the cat when you're away?</label>
                        <input type="text" name="customer[care_away]" id="CareAway" value="" required>
                    </div>

                    <button type="submit" class="btn">Submit</button>
                </form>

                <p class="">
                    <a href="login.html" class="cancel">Cancel</a>
                </p>
            </section>
        </div>
    </main>
</body>
</html>
