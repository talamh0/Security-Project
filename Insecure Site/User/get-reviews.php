<?php
session_start();
include 'config.php';
header('Content-Type: application/json');

 // No prepared statements are used here
// this query vulnerable to SQL Injection.
$query = "
    SELECT r.id, u.name AS username, r.rating, r.text, r.created_at
    FROM reviews r
    JOIN users u ON r.user_id = u.id
    ORDER BY r.created_at DESC
";

$result = mysqli_query($conn, $query);

// the review content is returned without sanitization
// which may allow XSS when displayed.

$reviews = [];
while($row = mysqli_fetch_assoc($result)) {
    $reviews[] = $row;
}

echo json_encode($reviews);
?>
