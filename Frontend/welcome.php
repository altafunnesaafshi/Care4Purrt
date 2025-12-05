<?php
session_start();

// if user is not logged in, send them back to login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Welcome - ULAB Shop</title>
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
                <!-- When logged in, Login/Signup can be hidden if you want -->
                <a href="logout.php">Logout</a>
            </nav>
        </div>
    </header>

    <section class="hero">
        <div class="container hero-inner">
            <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
            <p>You are now logged in. This page is protected by PHP session.</p>

            <p style="margin-top: 16px;">
                <a href="index.php" class="btn">Back to Products</a>
                <a href="logout.php" class="btn" style="margin-left: 8px;">Log Out</a>
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