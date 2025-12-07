<?php
// Load security configurations (HTTPS enforcement, session setup) and start session.
require_once 'security_config.php';

// load database connection settings
include 'config.php';
header('Content-Type: application/json');

// Role-based access control (RBAC):
// Only authenticated users with role = 'user' are allowed to submit reviews.
// Any request without the correct role is rejected with a JSON error.
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

// read raw json data sent from javascript fetch api
$data = json_decode(file_get_contents("php://input"), true);
$text = trim($data['text']);
$rating = intval($data['rating']);

// validate received data (check for emptiness and valid rating range)
if(empty($text) || $rating < 1 || $rating > 5) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit();
}

$user_id = $_SESSION['user_id'];

// sanitize text input to prevent cross-site scripting (xss) during output
// ent_quotes is used to handle both single and double quotes securely
$clean_text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');


// insert the new review into the database using a prepared statement
$stmt = $conn->prepare("INSERT INTO reviews (user_id, rating, text) VALUES (?, ?, ?)");
$stmt->bind_param("iis", $user_id, $rating, $clean_text);

if ($stmt->execute()) {
    $last_id = $stmt->insert_id;;

    // retrieve the newly added review along with the username for response
    // a prepared statement is used for secure retrieval by id
     $stmt2 = $conn->prepare("
        SELECT r.id, u.name AS username, r.rating, r.text, r.created_at
        FROM reviews r
        JOIN users u ON r.user_id = u.id
        WHERE r.id = ?
    ");
    $stmt2->bind_param("i", $last_id);
    $stmt2->execute();
    $result = $stmt2->get_result();
    $review = $result->fetch_assoc();

    echo json_encode(['success' => true, 'review' => $review]);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
?>
