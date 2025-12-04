<?php
session_start();
include 'config.php';
header('Content-Type: application/json');

// التحقق من تسجيل الدخول
if(!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

// قراءة البيانات المرسلة من fetch
$data = json_decode(file_get_contents("php://input"), true);
$text = trim($data['text']);
$rating = intval($data['rating']);

// التحقق من صحة البيانات
if(empty($text) || $rating < 1 || $rating > 5) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit();
}

$user_id = $_SESSION['user_id'];

// إدراج التعليق في قاعدة البيانات
$query = "INSERT INTO reviews (user_id, rating, text) VALUES ('$user_id', '$rating', '$text')";
if(mysqli_query($conn, $query)) {
    $last_id = mysqli_insert_id($conn);

    // استرجاع التعليق المضاف مع اسم المستخدم
    $query2 = "
        SELECT r.id, u.name AS username, r.rating, r.text, r.created_at
        FROM reviews r
        JOIN users u ON r.user_id = u.id
        WHERE r.id = '$last_id'
    ";
    $res = mysqli_query($conn, $query2);
    $review = mysqli_fetch_assoc($res);

    echo json_encode(['success' => true, 'review' => $review]);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
?>
