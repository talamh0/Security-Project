<?php
session_start();
include 'config.php';

// default error
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // get form inputs
    $email = isset($_POST["email"]) ? trim($_POST["email"]) : "";
    $password = isset($_POST["password"]) ? trim($_POST["password"]) : "";

    if (empty($email) || empty($password)) {
        $error = "Please fill in all fields.";

    } else {

        // still insecure (SQL Injection allowed)
        $query = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) === 1) {
            $row = mysqli_fetch_assoc($result);

            // Insecure MD5 password comparison
            if (md5($password) === $row["password"]) {

                $_SESSION['user_id'] = $row['id'];
                $_SESSION['name']    = $row['name'];

                header("Location: main.php");
                exit();

            } else {
                $error = "Wrong password.";
            }

        } else {
            $error = "User not found.";
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
