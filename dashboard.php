<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$id = $_SESSION['id'];
$conn = new mysqli("localhost", "root", "", "catadopt");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Fetch user data
$user_result = $conn->query("SELECT * FROM users WHERE id = $id");
$user = $user_result->fetch_assoc();

// Fetch adoption history
$history_result = $conn->query("
    SELECT a.id, a.adopted_at, c.name AS cat_name, a.status
    FROM adoptions a
    JOIN cat c ON a.cat_id = c.catID
    WHERE a.user_id = $id
    ORDER BY a.adopted_at DESC
");
?>



<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Dashboard | FurEver</title>
    <link rel="stylesheet" href="style.css" />

    <!--Favicon-->
    <link rel="icon" href="img/favicon.ico" type="image/x-icon" />
    </head>

    <body>
        <nav class="fixed-navbar">
            <div class="container">
            <a href="dashboard.php" class="brand">
                <img src="img/fureverhomeLogo.png" alt="Simple Logo" >
            </a>
            <ul class="nav-links">
                <li><a href="logout.php" onclick="return confirm('Are you sure you want to log out?')">Log out</a></li>
                
            </ul>
            <div class="menu-toggle" id="menu-toggle">☰</div>
            </div>

            <!-- Bottom Centered Navigation -->
            <div class="bottom-nav">
            <ul class="centered-links">
                <li><a href="what_We_do.html">What we do</a></li>
                <li><a href="who_we_are.html">Who are we</a></li>
                <li><a href="contact_us.html">Contact us</a></li>
            </ul>
            </div>
        </nav>

        <section id="dashboard">
        <div class="side-menu" id="side-menu">
        <ul>
            <li><a href="cat_list_user.php" class="btn-adopt">Cat List</a></li> 
            <li><a href="dashboard.php" class="btn-adopt">Home</a></li> 
            <li><a href="logout.php" class="btn-adopt" onclick="return confirm('Are you sure you want to log out?')">Log out</a></li> 
        
        </ul>
        </div>
        </section>
        
        <section>
        <!--user profile-->
        <div class="userbar-container">
            <div class="userbar-left">
            <img src="<?= $user['avatar'] ? htmlspecialchars($user['avatar']) : 'https://via.placeholder.com/50' ?>" alt="Avatar" class="userbar-avatar" />

            <span class="userbar-greeting">Welcome back, <?= htmlspecialchars($user['fname']) ?></span>

            </div>
            <a href="profile.php" class="userbar-edit-button">Edit Profile</a>
        </div>
            
        <!-- Main Dashboard Section -->
        <div class="dashboard">
            <div class="hero-section">
                <img src="img/banner.jpg" alt="Cute cats for adoption" class="hero-image" />
                <h1 class="hero-text">Welcome to FurEver Home</h1>
                </div>

                <!-- Floating Card Section -->
            <div class="adopt-box">
                <h2>Adopt</h2>
                <p>Give a loving cat a second chance at life. Your new best friend is waiting!</p>
                <a href="cat_list_user.php" class="adopt-btn">View Cats for Adoption</a>
            </div>

            <!-- Adoption History Card -->
            <div class="history-card">
                <div class="card-header">
                    <h2>Adoption History</h2>
                </div>

                <?php if ($history_result->num_rows > 0): ?>
                    <?php while($adoption = $history_result->fetch_assoc()): ?>
                        <div class="history-item">
                            <div class="history-info">
                                <p>
                                    Cat: <?= htmlspecialchars($adoption['cat_name']) ?> - 
                                    <?php
                                    if (isset($adoption['status'])) {
                                        $status = strtolower($adoption['status']);
                                        if ($status === 'approved') {
                                            echo 'Adopted';
                                        } elseif ($status === 'rejected') {
                                            echo 'Rejected';
                                        } else {
                                            echo 'Pending';
                                        }
                                    }
                                ?>

                                    on <?= date('d M Y', strtotime($adoption['adopted_at'])) ?>
                                </p>

                                <div class="history-actions-vertical">
                                    <?php if ($adoption['status'] === 'Pending'): ?>
                                        <a href="edit_adoption.php?id=<?= $adoption['id'] ?>" class="btn-edit">Edit</a>
                                        <a href="cancel_request.php?id=<?= $adoption['id'] ?>" class="btn-cancel" onclick="return confirm('Are you sure you want to cancel this request?')">Cancel</a>
                                    <?php elseif ($adoption['status'] === 'rejected'): ?>
                                    <?php endif; ?>

                                    <button class="toggle-feedback">Give Feedback</button>
                                </div>
                            </div>
                            <form class="feedback-form" method="post" action="submit_feedback.php">
                                <input type="hidden" name="adoption_id" value="<?= $adoption['id'] ?>">
                                <label for="feedback">Feedback:</label>
                                <textarea name="feedback" rows="3" placeholder="Share your experience..."></textarea>
                                <button type="submit">Submit</button>
                            </form>
                        </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="padding: 1rem;">You have not adopted any cats yet.</p>
            <?php endif; ?>

            </div>

            
            </div>      
            </div>
        </div>
</section>
<script src="menu.js"></script>       
<script src="profile.js"></script>
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