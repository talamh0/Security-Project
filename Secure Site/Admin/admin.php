<?php
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.cookie_samesite', Strict);
session_start();

if(isset($_SESSION['admin_logged_in'])){
    header("Location: manageEvents.php");
    exit();
}

$error = "";

if(isset($_POST['login'])){

    include("config.php");

    // sanitize input
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    
    $stmt = $conn->prepare("SELECT id, username, password FROM admin WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    
    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();

        
        if (password_verify($password, $admin['password'])) {

           
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['username'] = $admin['username'];

            header("Location: manageEvents.php");
            exit();

        } else {
            $error = "Incorrect password.";
        }

    } else {
        $error = "Admin not found.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <link rel="stylesheet" href="Admin-style.css">
</head>
<body class="admin-login-body">

<div class="admin-login-card">

<div class="admin-login-logo">
    <img src="../image/Six-Flags-login.png" alt="Six Flags Logo"> 
</div>

<h2>Admin Login</h2>

        <!-- Display login error message -->
        <?php if($error): ?>
            <p class="alert-error" style="text-align:center; margin-bottom:12px;">
                <?php echo $error; ?>
            </p>
        <?php endif; ?>
        <!-- Admin Login Form -->
        <form method="POST">
            <input type="text" name="username" placeholder="Username">
            <input type="password" name="password" placeholder="Password">

            <button type="submit" name="login" class="btn btn-primary" style="width:100%;">Login</button>
        </form>
    </div>

</body>
</html>
