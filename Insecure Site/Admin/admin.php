<?php
session_start();
// Insecure access control: session is started but there is no user identity or role stored from the database.

// Insecure: Only a simple flag is used to identify an admin session.
if(isset($_SESSION['admin_logged_in'])){
    header("Location: manageEvents.php"); // No additional role or permission check here.
    exit();
}

// Store login error message
$error = "";

// Process login form submission
if(isset($_POST['login'])){
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Insecure access control: admin identity is hard-coded and not linked to any user record or role.
    // Any user who knows these credentials will gain full admin privileges.
    if($username === "admin" && $password === "admin123"){
        
        // No server-side verification of admin role beyond matching hard-coded username/password.
        $_SESSION['admin_logged_in'] = true;
        header("Location: manageEvents.php");
        exit();
    } else {
        $error = "Invalid username or password.";
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
    <img src="../image/sixflags.png" alt="Six Flags Logo"> 
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
