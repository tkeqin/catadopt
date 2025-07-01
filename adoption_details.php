<?php
session_start();

// Restrict to admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "catadopt");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Fetch request_id from the query string
if (isset($_GET['request_id'])) {
    $request_id = $_GET['request_id'];
    
    // Fetch the adoption details using the request_id
    $sql = "SELECT a.id, a.adopted_at, a.status, u.fname, u.email, u.phone, u.address, u.date_of_birth, u.occupation, 
            u.house_type, u.own_or_rent, u.landlord_approval, u.household_members, u.children_in_house, u.other_pets, 
            u.adopt_reason, u.adopted_before, u.cat_sleep, u.indoor_outdoor, u.care_away, c.name AS cat_name
            FROM adoptions a
            JOIN users u ON a.user_id = u.id
            JOIN cat c ON a.cat_id = c.catID
            WHERE a.id = $request_id"; // Use request_id instead of cat_id
    
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $adoption = $result->fetch_assoc();
    } else {
        die("No adoption details found.");
    }
} else {
    die("No request_id provided.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Adoption Details | FurEver</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="icon" href="img/favicon.ico" type="image/x-icon">
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #f8f9fa;
      margin: 0;
      padding: 0;
    }

    h1 {
      text-align: center;
      margin-top: 2rem;
      color: #333;
    }

    .detail-card {
      max-width: 800px;
      margin: 2rem auto;
      background: #fff;
      padding: 2rem;
      border-radius: 10px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .detail-grid {
      display: grid;
      grid-template-columns: 1fr 2fr;
      row-gap: 1rem;
      column-gap: 1.5rem;
    }

    .label {
      font-weight: 600;
      color: #555;
      text-align: right;
    }

    .value {
      color: #333;
    }

    .back-link {
      display: block;
      text-align: center;
      margin: 2rem 0;
      font-size: 1rem;
      color: #007bff;
      text-decoration: none;
    }

    .back-link:hover {
      text-decoration: underline;
    }

    @media (max-width: 600px) {
      .detail-grid {
        grid-template-columns: 1fr;
        text-align: left;
      }
      .label {
        text-align: left;
      }
    }
  </style>
</head>
<body>

  <h1>Adoption Request Details</h1>

  <div class="detail-card">
    <div class="detail-grid">
      <div class="label">Cat Name</div>
      <div class="value"><?= htmlspecialchars($adoption['cat_name']) ?></div>

      <div class="label">Adopter Name</div>
      <div class="value"><?= htmlspecialchars($adoption['fname']) ?></div>

      <div class="label">Email</div>
      <div class="value"><?= htmlspecialchars($adoption['email']) ?></div>

      <div class="label">Phone</div>
      <div class="value"><?= htmlspecialchars($adoption['phone']) ?></div>

      <div class="label">Address</div>
      <div class="value"><?= htmlspecialchars($adoption['address']) ?></div>

      <div class="label">Date of Birth</div>
      <div class="value"><?= htmlspecialchars($adoption['date_of_birth']) ?></div>

      <div class="label">Occupation</div>
      <div class="value"><?= htmlspecialchars($adoption['occupation']) ?></div>

      <div class="label">House Type</div>
      <div class="value"><?= htmlspecialchars($adoption['house_type']) ?></div>

      <div class="label">Own or Rent</div>
      <div class="value"><?= htmlspecialchars($adoption['own_or_rent']) ?></div>

      <div class="label">Landlord Approval</div>
      <div class="value"><?= htmlspecialchars($adoption['landlord_approval']) ?></div>

      <div class="label">Household Members</div>
      <div class="value"><?= htmlspecialchars($adoption['household_members']) ?></div>

      <div class="label">Children in House</div>
      <div class="value"><?= htmlspecialchars($adoption['children_in_house']) ?></div>

      <div class="label">Other Pets</div>
      <div class="value"><?= htmlspecialchars($adoption['other_pets']) ?></div>

      <div class="label">Reason for Adopting</div>
      <div class="value"><?= htmlspecialchars($adoption['adopt_reason']) ?></div>

      <div class="label">Adopted Before</div>
      <div class="value"><?= htmlspecialchars($adoption['adopted_before']) ?></div>

      <div class="label">Where Will Cat Sleep?</div>
      <div class="value"><?= htmlspecialchars($adoption['cat_sleep']) ?></div>

      <div class="label">Indoor or Outdoor</div>
      <div class="value"><?= htmlspecialchars($adoption['indoor_outdoor']) ?></div>

      <div class="label">Care When Away</div>
      <div class="value"><?= htmlspecialchars($adoption['care_away']) ?></div>
    </div>
  </div>

  <a href="admin_adoption_requests.php" class="back-link">← Back to Adoption Requests</a>

</body>
</html>
