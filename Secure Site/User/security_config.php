<?php
// 1. FORCE HTTPS REDIRECTION
// Ensures all communication is encrypted (TLS/SSL) to protect credentials and session tokens from man-in-the-middle attacks.
if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === "off") {
    $redirect = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header("Location: $redirect");
    exit();
}

// 2. SECURE SESSION COOKIE PARAMETERS
// Enhances the security of the session token stored in the user's cookie.
session_set_cookie_params([
    'secure' => true,      // Cookie is only sent over HTTPS (required due to the check above).
    'httponly' => true,    // Prevents client-side JavaScript access, defending against XSS.
    'samesite' => 'Strict' // Strongest defense against Cross-Site Request Forgery (CSRF).
]);

// 3. START SESSION
session_start();
?>
