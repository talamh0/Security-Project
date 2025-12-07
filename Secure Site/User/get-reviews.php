<?php
// Load security configurations (HTTPS enforcement, session setup) and start session.
require_once 'security_config.php';

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
// loop through results and apply xss protection before output
while($row = mysqli_fetch_assoc($result)){
    $reviews[] = [
              // sanitize username to prevent xss
        "username" => htmlspecialchars($row['username'], ENT_QUOTES),
        "rating" => $row['rating'],
        // sanitize review text to prevent xss
        "text" => htmlspecialchars($row['text'], ENT_QUOTES),
        "created_at" => $row['created_at']
    ];
}
// return the sanitized data as a json array
echo json_encode($reviews);
?>
