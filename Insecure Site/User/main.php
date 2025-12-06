<?php
session_start();
include 'config.php';

// التحقق من تسجيل الدخول
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php?login_required=1");
    exit();
}

// بيانات المستخدم
$user_id = $_SESSION['user_id'];
$username = $_SESSION['name']; // أو $_SESSION['username']
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Six Flags – Attractions</title>
    <link rel="stylesheet" href="User-style.css">
</head>

<body>

    <!-- HEADER -->
    <header class="header">
        <nav class="nav-container">
            <img src="../image/sixflags.png" class="logo-img">
            <ul class="nav-menu">
                    <li><a href="#home">Home</a></li>
                    <li><a href="#rides">Rides</a></li>
                    <li><a href="#book">Book</a></li>
                    <li><a href="#contact" style="font-weight:700;">Contact</a></li>
                    <li><a href="dashboard.php" class="dashboard-link">Dashboard</a></li>
                </ul>
            <div class="right-logos">
                <img src="../image/qiddiya.png">
                <img src="../image/vision2030.png">
            </div>
        </nav>
    </header>

    <!-- HERO -->
    <section class="hero" id="home">
        <div>
            <h1>Welcome to Six Flags</h1>
            <p>Experience the thrill & unforgettable moments.</p>
        </div>
    </section>

    <!-- ATTRACTIONS -->
    <section class="carousel-section" id="rides">
        <h2 class="carousel-title">Our Attractions</h2>
        <button id="arrowLeft" class="carousel-arrow">❮</button>
        <button id="arrowRight" class="carousel-arrow">❯</button>
        <div class="carousel-container">
            <div id="carousel" class="carousel"></div>
        </div>
    </section>

    <!-- BOOK NOW -->
    <section class="book-banner" id="book">
        <div class="book-left">
            <img src="../image/boy.png">
        </div>
        <div class="book-right">
            <div class="book-right-content">
                <h2>Book Now</h2>
                <p>Get ready for the thrill — your adventure starts now!  
                Don’t miss the excitement </p>
                <a class="book-btn" href="home.php">Start Booking</a>
            </div>
        </div>
    </section>

    <!-- REVIEWS -->
    <section class="reviews-section" id="reviews">
        <h2 class="section-title" id="reviews-title">Customer Reviews</h2>
        <p class="section-subtitle" id="reviews-subtitle">
            See what our visitors are saying! Share your experience below.
        </p>

        <div class="reviews-container" id="reviews-container">

            <!-- REVIEWS LIST -->
            <div class="reviews-list" id="reviews-list">
                <!-- سيتم تحميل التعليقات من السيرفر هنا -->
            </div>

            <!-- ADD NEW REVIEW -->
            <form class="review-form" id="review-form">
                <textarea id="review-input" rows="5" placeholder="Write your review..." required></textarea>
                
                <div class="rating" id="rating">
                    <span data-value="1">☆</span>
                    <span data-value="2">☆</span>
                    <span data-value="3">☆</span>
                    <span data-value="4">☆</span>
                    <span data-value="5">☆</span>
                </div>
                <input type="hidden" id="review-rating" value="">

                <button type="submit" id="submit-review-btn">Submit Review</button>
            </form>
        </div>
    </section>

    <!-- CONTACT -->
    <section class="contact-section" id="contact">
        <h2 class="section-title" id="contact-title">Contact Us</h2>
        <p class="section-subtitle" id="contact-subtitle">
            Have questions? We’re here to help — feel free to reach out anytime!
        </p>
            <div class="contact-info" id="contact-info">
                <div class="info-item" id="location">
                    <div class="icon">📍</div>
                    <p>Riyadh, Saudi Arabia</p>
                </div>
                <div class="info-item" id="email">
                    <div class="icon">📧</div>
                    <p>support@sixflags.com</p>
                </div>
                <div class="info-item" id="phone">
                    <div class="icon">📞</div>
                    <p>+966 555 123 456</p>
                </div>
            </div>
    </section>

    <!-- FOOTER -->
    <footer>
        <p>© Six Flags Qiddiya. All Rights Reserved. — <?php echo date("Y"); ?></p>
    </footer>

    <script src="main.js"></script>

</body>
</html>
