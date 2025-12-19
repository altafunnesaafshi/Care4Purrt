<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Care4Purrt</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #183a5cff;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 220px;
            height: 100%;
            background-color: #343a40;
            padding-top: 30px;
            color: #fff;
        }
        .sidebar h3 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 1.5rem;
            font-weight: bold;
        }
        .sidebar a {
            display: block;
            color: #fff;
            padding: 15px 20px;
            text-decoration: none;
            font-size: 1rem;
            border-radius: 8px;
            margin: 5px 10px;
        }
        .sidebar a:hover {
            background-color: #495057;
        }

        /* Content */
        .content {
            margin-left: 220px;
            padding: 30px 40px;
        }

        /* Hero Section */
        .hero {
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            margin-bottom: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 400px;
        }
        .hero img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: brightness(0.7);
        }
        .hero-text {
            position: relative;
            color: #fff;
            text-align: center;
            z-index: 2;
        }
        .hero-text h1 {
            font-size: 3rem;
            font-weight: bold;
            text-shadow: 2px 2px 6px rgba(0,0,0,0.6);
        }
        .hero-text p {
            font-size: 1.2rem;
            text-shadow: 1px 1px 4px rgba(0,0,0,0.5);
        }

        /* Dashboard Cards */
        .dashboard .card {
            border-radius: 20px;
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .dashboard .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
        }
        .card-img-top {
            height: 250px;
            object-fit: cover;
        }
        .card-title {
            font-weight: bold;
        }

        /* Footer */
        .footer {
            background-color: #343a40;
            color: #fff;
            text-align: center;
            padding: 15px;
            margin-top: 40px;
            border-radius: 10px;
        }

        @media (max-width: 768px) {
            .content {
                margin-left: 0;
                padding: 20px;
            }
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h3><?php echo htmlspecialchars($username); ?></h3>
        <a href="view_all_users.php"><i class="fas fa-users me-2"></i>View All Users</a>
        <a href="generate_pet_passport.php"><i class="fas fa-passport me-2"></i>Generate Pet Passport</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a>
    </div>

    <!-- Main Content -->
    <div class="content">
        <!-- Hero Section -->
        <div class="hero">
            <img src="https://images.unsplash.com/photo-1601758123927-8d6b012f6e69?auto=format&fit=crop&w=1470&q=80" alt="Pets Dashboard">
            <div class="hero-text">
                <h1>Welcome, Admin <?php echo htmlspecialchars($username); ?>!</h1>
                <p>Manage users and generate pet passports efficiently.</p>
            </div>
        </div>

        <!-- Dashboard Cards -->
        <div class="row row-cols-1 row-cols-md-2 g-4 dashboard">
            <div class="col">
                <div class="card">
                    <img src="https://i.pinimg.com/736x/2b/37/02/2b3702312eccb2f7f91dc9690f465c6a.jpg" class="card-img-top" alt="Manage Users">
                    <div class="card-body text-center">
                        <h5 class="card-title">Manage Users</h5>
                        <p class="card-text">View, add, edit, and delete users from the system.</p>
                        <a href="view_all_users.php" class="btn btn-primary">Go to Users</a>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card">
                    <img src="https://i.pinimg.com/1200x/e9/b4/6c/e9b46c1fd760294fda10f39ed10f96f3.jpg" class="card-img-top" alt="Pet Passport">
                    <div class="card-body text-center">
                        <h5 class="card-title">Generate Pet Passport</h5>
                        <p class="card-text">Create official pet passports with medical records.</p>
                        <a href="generate_pet_passport.php" class="btn btn-success">Generate Passport</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer mt-5">
            &copy; 2025 Care4Purrt. All rights reserved.
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<!-- Go Back to Landing Page -->
<div class="text-center mt-4">
    <a href="landing.php" class="btn btn-warning btn-lg">
        <i class="fas fa-home me-2"></i>Go Back to Homepage
    </a>
</div>
