<?php
session_start();
include 'config.php';
header('Content-Type: application/json');

// استعلام لجلب التعليقات مع اسم المستخدم
$query = "
    SELECT r.id, u.name AS username, r.rating, r.text, r.created_at
    FROM reviews r
    JOIN users u ON r.user_id = u.id
    ORDER BY r.created_at DESC
";

$result = mysqli_query($conn, $query);

$reviews = [];
while($row = mysqli_fetch_assoc($result)) {
    $reviews[] = $row;
}

echo json_encode($reviews);
?>
