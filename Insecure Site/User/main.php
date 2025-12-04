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
        <img src="/web/image/sixflags.png" class="logo-img">
        <ul class="nav-menu">
            <li><a href="#home">Home</a></li>
            <li><a href="#rides">Rides</a></li>
            <li><a href="#book">Book</a></li>
            <li><a href="#contact" style="font-weight:700;">Contact</a></li>
        </ul>
        <div class="right-logos">
            <img src="/web/image/qiddiya.png">
            <img src="/web/image/vision2030.png">
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
        <img src="/web/image/boy.png">
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

<!-- REVIEWS JS -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    const reviewsList = document.getElementById("reviews-list");
    const reviewForm = document.getElementById("review-form");
    const ratingInput = document.getElementById("review-rating");
    const ratingStars = document.querySelectorAll("#rating span");

    // جلب التعليقات من السيرفر
    fetch("get-reviews.php")
    .then(res => res.json())
    .then(data => {
        reviewsList.innerHTML = "";
        data.forEach(review => addReviewToList(review, false));
    });

    // تفعيل النجوم التفاعلية
    ratingStars.forEach(star => {
        star.addEventListener("mouseover", () => highlightStars(star.dataset.value));
        star.addEventListener("mouseout", () => highlightStars(ratingInput.value));
        star.addEventListener("click", () => {
            ratingInput.value = star.dataset.value;
            highlightStars(ratingInput.value);
        });
    });

    function highlightStars(count) {
        ratingStars.forEach(star => {
            star.style.color = (star.dataset.value <= count) ? "var(--yellow-main)" : "#ccc";
        });
    }

    // إضافة تعليق جديد للسيرفر والواجهة
    reviewForm.addEventListener("submit", e => {
        e.preventDefault();
        const text = document.getElementById("review-input").value.trim();
        const rating = ratingInput.value;

        if(text && rating){
            fetch("add-review.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ text, rating })
            })
            .then(res => res.json())
            .then(data => {
                if(data.success){
                    addReviewToList(data.review, true);
                    reviewForm.reset();
                    highlightStars(0);
                } else {
                    alert(data.message || "Failed to submit review");
                }
            });
        }
    });

    // دالة لإضافة تعليق للقائمة
    function addReviewToList(review, prepend = true) {
        const reviewItem = document.createElement("div");
        reviewItem.classList.add("review-item");

        const reviewHeader = document.createElement("div");
        reviewHeader.classList.add("review-header");

        const reviewerName = document.createElement("div");
        reviewerName.classList.add("reviewer-name");
        reviewerName.textContent = review.username;

        const reviewStars = document.createElement("div");
        reviewStars.classList.add("review-rating");
        reviewStars.textContent = "⭐".repeat(review.rating);

        reviewHeader.appendChild(reviewerName);
        reviewHeader.appendChild(reviewStars);

        const reviewText = document.createElement("p");
        reviewText.classList.add("review-text");
        reviewText.textContent = review.text;

        reviewItem.appendChild(reviewHeader);
        reviewItem.appendChild(reviewText);

        if(prepend){
            reviewsList.prepend(reviewItem);
        } else {
            reviewsList.appendChild(reviewItem);
        }
    }
});
</script>


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


<!-- CAROUSEL JS -->
<script>
const rides = [
    { title: "Steam Racer", img: "/web/image/Iron_Rattler.png", desc: "The iconic steampunk coaster of Qiddiya." },
    { title: "Orbital Spin", img: "/web/image/Gyrospin.png", desc: "A thrilling spinning experience with neon lights." },
    { title: "Falcon Loop", img: "/web/image/Spitfire.jpg", desc: "A high-speed coaster with insane drops." },
    { title: "Carousel Kids", img: "/web/image/Arabian_Carousel.jpg", desc: "A gentle and fun ride for families." }
];

const carousel = document.getElementById("carousel");
let index = 0;

function buildCarousel() {
    rides.forEach(ride => {
        const item = document.createElement("div");
        item.className = "carousel-item";
        item.innerHTML = `
            <div class="card">
                <img src="${ride.img}">
                <div class="card-title">${ride.title}</div>
                <div class="card-desc">${ride.desc}</div>
            </div>`;
        carousel.appendChild(item);
    });
    updateCarousel();
}

function updateCarousel() {
    const items = document.querySelectorAll(".carousel-item");
    items.forEach((item, i) => {
        const offset = (i - index + rides.length) % rides.length;
        if (offset === 0) {
            item.style.transform = "translate(-50%, -50%) translateZ(0) scale(1)";
            item.style.opacity = "1";
        } else if (offset === 1 || offset === rides.length - 1) {
            const side = offset === 1 ? 1 : -1;
            item.style.transform =
              `translate(-50%, -50%) translateX(${side * 350}px) translateZ(-200px) scale(0.8) rotateY(${side * 20}deg)`;
            item.style.opacity = "0.8";
        } else {
            item.style.transform = "translate(-50%, -50%) translateZ(-500px) scale(0.5)";
            item.style.opacity = "0";
        }
    });
}

buildCarousel();
setInterval(() => {
    index = (index + 1) % rides.length;
    updateCarousel();
}, 4000);

document.getElementById("arrowLeft").onclick = () => {
    index = (index - 1 + rides.length) % rides.length;
    updateCarousel();
};
document.getElementById("arrowRight").onclick = () => {
    index = (index + 1) % rides.length;
    updateCarousel();
};
</script>

<!-- FOOTER -->
<footer>
    <p>© Six Flags Qiddiya. All Rights Reserved. — <?php echo date("Y"); ?></p>
</footer>

</body>
</html>
