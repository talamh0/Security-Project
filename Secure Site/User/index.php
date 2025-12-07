<?php
// load core security configurations (https enforcement, secure cookies) and start session
require_once 'security_config.php';

// load database connection settings
include 'config.php';

// default error message variable
$error = "";

// handle login form submission 
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // get and sanitize form inputs
    $username = isset($_POST["name"]) ? trim($_POST["name"]) : "";
    $password = isset($_POST["password"]) ? trim($_POST["password"]) : "";

    // validate inputs (check if fields are empty)
    if (empty($username) || empty($password)) {
        $error = "please fill in all fields.";

    } else {

        // check username in database using prepared statement 
        $stmt = $conn->prepare("SELECT id, name, password, role FROM users WHERE name = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();

        $result = $stmt->get_result();

        // check if username was found
        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();

            // verify submitted password against the stored bcrypt hash
            if (password_verify($password, $row["password"])) {

                // login successful: store secure session data
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['name']    = $row['name'];
                $_SESSION['role']    = $row['role'];

                // redirect to main page
                header("Location: main.php");
                exit();

            } else {
                // password verification failed
                $error = "wrong password.";
            }

        } else {
            // username not found
            $error = "user not found.";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>login | event booking system</title>

    <link rel="stylesheet" href="user-style.css">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
</head>

<body style="
    background: linear-gradient(rgba(0, 0, 0, 0.45), rgba(0, 0, 0, 0.45)),
    url('/web/image/hero-bg.png') no-repeat center center fixed;
    background-size: cover;
">

<div class="login-page">
<div class="auth-box">

    <div style="text-align:center; margin-bottom:20px;">
        <img src="../image/sixflags.png" alt="logo" style="width:160px;">
    </div>

    <h2 style="text-align:center;">login</h2>

    <?php
    // success message after registration
    if (isset($_GET["registered"])) {
        echo "<div class='success'>account created successfully. you can now login.</div>";
    }

    // user tried accessing a protected page
    if (isset($_GET["login_required"])) {
        echo "<div class='error'>please login to continue.</div>";
    }

    // display login errors
    if (!empty($error)) {
        echo "<div class='error'>$error</div>";
    }
    ?>

    <form method="POST" action="">
        <input type="text" name="name" placeholder="username">
        <input type="password" name="password" placeholder="password">
        <button type="submit">login</button>
    </form>

    <div class="link">
        not a member? <a href="register.php">create an account</a>
    </div>

</div>
</div>

</body>
</html>
