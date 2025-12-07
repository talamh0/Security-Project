<?php
session_start();
include 'config.php';

// default error
$error = "";

// handle login form
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // get form inputs
    $username = isset($_POST["name"]) ? trim($_POST["name"]) : "";
    $password = isset($_POST["password"]) ? trim($_POST["password"]) : "";

    if (empty($username) || empty($password)) {
        $error = "Please fill in all fields.";

    } else {

        // ❌ Insecure MD5 hashing (required for the vulnerable version)
        $hashed_password = md5($password);

        // still insecure SQL Injection
        $query = "SELECT * FROM users WHERE name = '$username' AND password = '$hashed_password'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) >= 1) {

            $row = mysqli_fetch_assoc($result);

            $_SESSION['user_id'] = $row['id'];
            $_SESSION['name']    = $row['name'];

            header("Location: main.php");
            exit();

        } else {
            $error = "Wrong username or password.";
        }
    }
}
?>
            <!-- login form -->
            <form method="POST" action="">
                <input type="email" name="email" placeholder="Email Address">
                <input type="password" name="password" placeholder="Password">
                <button type="submit">Login</button>
            </form>

            <!-- register link -->
            <div class="link">
                Not a member? <a href="register.php">Create an account</a>
            </div>

        </div>
    </div>

</body>
</html>
