<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "catadopt");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch feedback along with user and cat info
$sql = "
    SELECT 
        f.id, f.feedback_text, f.created_at,
        u.fname AS user_name,
        c.name AS cat_name
    FROM feedback f
    JOIN users u ON f.user_id = u.id
    JOIN adoptions a ON f.adoption_id = a.id
    JOIN cat c ON a.cat_id = c.catID
    ORDER BY f.created_at DESC
";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Feedback | FurEver</title>
  <link rel="stylesheet" href="admin_style.css" />
  <link rel="icon" href="img/favicon.ico" type="image/x-icon">
  <style>
    .feedback-container {
      max-width: 900px;
      margin: 2rem auto;
      padding: 1rem;
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .feedback-entry {
      border-bottom: 1px solid #ccc;
      padding: 1rem 0;
    }

    .feedback-entry:last-child {
      border-bottom: none;
    }

    .feedback-meta {
      font-size: 0.9rem;
      color: #555;
      margin-bottom: 0.5rem;
    }

    .feedback-text {
      font-size: 1.05rem;
      line-height: 1.5;
    }

    h1 {
      text-align: center;
      margin-top: 2rem;
    }
    
  </style>
</head>
<body>

  <h1>User Feedback</h1>

  <div class="feedback-container">
    <?php if ($result && $result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
        <div class="feedback-entry">
          <div class="feedback-meta">
            <strong><?= htmlspecialchars($row['user_name']) ?></strong> on 
            <em><?= htmlspecialchars($row['cat_name']) ?></em> — 
            <?= date("F j, Y, g:i a", strtotime($row['created_at'])) ?>
          </div>
          <div class="feedback-text">
            <?= nl2br(htmlspecialchars($row['feedback_text'])) ?>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p>No feedback submitted yet.</p>
    <?php endif; ?>
  </div>
  
  <div class="button-group"> 
      <a href="admin_dashboard.php"  class="back-btn">Back to Dashboard</a>
  </div>
</body>
</html>

<?php $conn->close(); ?>
