<?php
session_start();
include 'config.php';
header('Content-Type: application/json');

if(!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);
$text = trim($data['text']);
$rating = intval($data['rating']);

 // The value $text is taken directly from user input
 // without any sanitization 

if(empty($text) || $rating < 1 || $rating > 5) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit();
}

$user_id = $_SESSION['user_id'];

// Insecure insertion without prepared statements
$query = "INSERT INTO reviews (user_id, rating, text) VALUES ('$user_id', '$rating', '$text')";
if(mysqli_query($conn, $query)) {
    $review = [
        'username' => $_SESSION['name'],
        'rating' => $rating,
        'text' => $text,
        'created_at' => date('Y-m-d H:i:s')
    ];
    echo json_encode(['success' => true, 'review' => $review]);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
?>
