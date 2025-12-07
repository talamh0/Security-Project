<?php
// FORCE HTTPS
if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === "off") {
    $redirect = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header("Location: $redirect");
    exit();
}

ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.cookie_samesite', 1);

session_start();


include 'config.php';
header('Content-Type: application/json');

$query = "
          SELECT r.id, u.name AS username, r.rating, r.text, r.created_at
          FROM reviews r
          JOIN users u ON r.user_id = u.id
          ORDER BY r.created_at DESC
          ";

$result = mysqli_query($conn, $query);

$reviews = [];
while($row = mysqli_fetch_assoc($result)){
    $reviews[] = [
        "username" => htmlspecialchars($row['username'], ENT_QUOTES),
        "rating" => $row['rating'],
        "text" => htmlspecialchars($row['text'], ENT_QUOTES),
        "created_at" => $row['created_at']
    ];
}

echo json_encode($reviews);
?>
