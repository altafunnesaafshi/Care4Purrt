<?php
session_start();
include "db_connect.php";

$message = "";

if (isset($_POST['signup'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if ($username === "" || $password === "") {
        $message = "Please fill up all fields.";
    } else {
        // check if username already exists
        $checkSql = "SELECT id FROM users WHERE username = ?";
        $checkStmt = mysqli_prepare($conn, $checkSql);
        mysqli_stmt_bind_param($checkStmt, "s", $username);
        mysqli_stmt_execute($checkStmt);
        $checkResult = mysqli_stmt_get_result($checkStmt);

        if (mysqli_num_rows($checkResult) > 0) {
            $message = "This username is already taken.";
        } else {
            // hash password for safety
            $hashedPass = password_hash($password, PASSWORD_DEFAULT);

            $insertSql = "INSERT INTO users (username, password) VALUES (?, ?)";
            $insertStmt = mysqli_prepare($conn, $insertSql);
            mysqli_stmt_bind_param($insertStmt, "ss", $username, $hashedPass);

            if (mysqli_stmt_execute($insertStmt)) {
                $message = "Signup successful! You can login now.";
            } else {
                $message = "Something went wrong. Please try again.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Signup - ULAB Shop</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header class="topbar">
        <div class="container nav">
            <div class="brand">Shop</div>
            <nav class="menu">
                <a href="index.php">Home</a>
                <a href="index.php">Products</a>
                <a href="index.php#about">About</a>
                <a href="login.php">Login</a>
                <a class="active" href="signup.php">Signup</a>
            </nav>
        </div>
    </header>

    <section class="hero">
        <div class="container hero-inner">
            <h1>Create an account</h1>
            <p>Signup to access your account and explore more.</p>

            <?php if ($message !== ""): ?>
                <p style="color: red; margin-bottom: 16px;">
                    <?php echo htmlspecialchars($message); ?>
                </p>
            <?php endif; ?>

            <form method="POST" style="max-width: 320px; margin: 0 auto; display: flex; flex-direction: column; gap: 12px;">
                <label>
                    Username:
                    <input type="text" name="username" placeholder="Enter username">
                </label>

                <label>
                    Password:
                    <input type="password" name="password" placeholder="Enter password">
                </label>

                <button class="btn" type="submit" name="signup">Signup</button>
            </form>

            <p style="margin-top: 12px;">
                Already have an account?
                <a href="login.php">Login here</a>
            </p>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <p>© 2025 ULAB Shop. All Rights Reserved.</p>
        </div>
    </footer>
</body>

</html>