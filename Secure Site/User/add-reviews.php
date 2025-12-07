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

// التحقق من تسجيل الدخول
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
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

// تنظيف مدخلات النص لمنع XSS
$clean_text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');


// إدراج التعليق في قاعدة البيانات
$stmt = $conn->prepare("INSERT INTO reviews (user_id, rating, text) VALUES (?, ?, ?)");
$stmt->bind_param("iis", $user_id, $rating, $clean_text);

if ($stmt->execute()) {
    $last_id = $stmt->insert_id;;

    // استرجاع التعليق المضاف مع اسم المستخدم
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
