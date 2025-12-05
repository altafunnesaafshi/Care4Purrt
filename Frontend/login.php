<?php
session_start();
include "db_connect.php";

$message = "";

if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if ($username === "" || $password === "") {
        $message = "Please enter username and password.";
    } else {
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) === 1) {
            $user = mysqli_fetch_assoc($result);

            // check password
            if (password_verify($password, $user['password'])) {
                // store into session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];

                // redirect to new page
                header("Location: welcome.php");
                exit;
            } else {
                $message = "Incorrect password.";
            }
        } else {
            $message = "No user found with this username.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login - ULAB Shop</title>
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
                <a class="active" href="login.php">Login</a>
                <a href="signup.php">Signup</a>
            </nav>
        </div>
    </header>

    <section class="hero">
        <div class="container hero-inner">
            <h1>Login</h1>
            <p>Login with your username and password.</p>

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

                <button class="btn" type="submit" name="login">Login</button>
            </form>

            <p style="margin-top: 12px;">
                Don’t have an account yet?
                <a href="signup.php">Signup here</a>
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