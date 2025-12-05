    /* REVIEWS JS */

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
                fetch("add-reviews.php", {
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

    /*  CAROUSEL JS */
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
