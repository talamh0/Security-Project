<?php
// --------------------------------------------------
// Session Protection
// --------------------------------------------------
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// --------------------------------------------------
// Connect to Database
// --------------------------------------------------
require_once "config.php";

$user_id = $_SESSION['user_id'];


// --------------------------------------------------
// Fetch Logged-in User Information
// --------------------------------------------------
$stmt = $conn->prepare("SELECT name, email FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();


// --------------------------------------------------
// Fetch Total Bookings
// --------------------------------------------------
$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM bookings WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$total_bookings = $stmt->get_result()->fetch_assoc()['total'];


// --------------------------------------------------
// Fetch Recent Bookings (limit 5)
// --------------------------------------------------
$stmt = $conn->prepare("
    SELECT e.name AS event_name, b.booking_date, b.total_price
    FROM bookings b
    JOIN events e ON b.event_id = e.id
    WHERE b.user_id = ?
    ORDER BY b.booking_date DESC
    LIMIT 5
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$recent = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="User-style.css">

    <style>
        /* ===== Dashboard Styling (Matches Your Theme) ===== */
        body {
            font-family: "Poppins","Tajawal",sans-serif;
            background: linear-gradient(135deg, #FFF4C2, #FFDDEA, #EEDCFF);
            margin: 0;
            padding: 0;
        }

        .dashboard-container {
            max-width: 900px;
            background: white;
            margin: 70px auto;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }

        h1 {
            color: var(--navy);
        }

        .info-box {
            background: #FFF8CC;
            border: 2px solid #FFD700;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 25px;
        }

        .info-box p {
            margin: 6px 0;
            font-size: 1rem;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table th {
            background: var(--navy);
            color: white;
            padding: 10px;
            text-align: left;
            font-size: 0.9rem;
        }

        table td {
            padding: 10px;
            background: #fff;
            border-bottom: 1px solid #ddd;
            font-size: 0.9rem;
        }

        .btn-area {
            margin-top: 25px;
            display: flex;
            gap: 15px;
        }

        a.btn {
            display: inline-block;
            padding: 12px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            text-align: center;
        }

        .events-btn {
            background: var(--yellow-main);
            color: black;
        }

        .logout-btn {
            background: #D80000;
            color: white;
        }

        .no-data {
            padding: 10px;
            color: #444;
            font-style: italic;
        }
    </style>
</head>

<body>

<div class="dashboard-container">

    <h1>Welcome, <?= htmlspecialchars($user['name']); ?>!</h1>

    <div class="info-box">
        <p><strong>Your Email:</strong> <?= htmlspecialchars($user['email']); ?></p>
        <p><strong>Total Bookings:</strong> <?= $total_bookings; ?></p>
    </div>

    <h2>Recent Bookings</h2>

    <?php if ($recent->num_rows > 0): ?>
        <table>
            <tr>
                <th>Event</th>
                <th>Date</th>
                <th>Price (SAR)</th>
            </tr>
            <?php while ($row = $recent->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['event_name']); ?></td>
                    <td><?= $row['booking_date']; ?></td>
                    <td><?= $row['total_price']; ?> SAR</td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p class="no-data">No recent bookings yet.</p>
    <?php endif; ?>


    <div class="btn-area">
        <a href="main.php" class="btn events-btn">Back to main</a>
        <a href="logout.php" class="btn logout-btn">Logout</a>
    </div>

</div>

</body>
</html>
