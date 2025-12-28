<?php
session_start();
include('db.php');

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'doctor') {
    header("Location: login.php");
    exit();
}
$username = $_SESSION['username'];
$sql = "SELECT * FROM pet_moods";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pet Mood Logs - Doctor Dashboard</title>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.7.4/lottie.min.js"></script>

<style>
/* General Body */
body {
    font-family: 'Poppins', sans-serif;
    margin: 0;
    background: linear-gradient(135deg, #8EC5FC, #E0C3FC);
    color: #111;
}

/* Sidebar */
.sidebar {
    width: 280px;
    height: 100vh;
    position: fixed;
    left: 0; top: 0;
    padding: 30px 20px;
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(12px);
    box-shadow: 0 6px 25px rgba(0,0,0,0.15);
    border-radius: 0 25px 25px 0;
}
.sidebar h3 {
    text-align: center;
    margin-bottom: 40px;
    color: #333;
    font-size: 2rem;
    font-weight: 700;
}
.sidebar a {
    display: block;
    padding: 18px;
    margin-bottom: 15px;
    font-size: 1.3rem;
    border-radius: 12px;
    text-decoration: none;
    color: #555;
    transition: all 0.3s;
}
.sidebar a:hover {
    background: #7F00FF;
    color: #fff;
    transform: translateX(6px);
}

/* Main Content */
.content {
    margin-left: 300px;
    padding: 60px;
}
.page-title {
    font-size: 3.5rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 15px;
    color: #333;
}

/* Glassmorphic Table Card */
.table-card {
    background: rgba(255,255,255,0.95);
    padding: 50px;
    border-radius: 30px;
    box-shadow: 0 8px 35px rgba(0,0,0,0.2);
    animation: fadeIn 0.7s ease;
}

/* Table Style */
table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    font-size: 1.3rem;
}
th {
    background: #7F00FF;
    color: #fff;
    text-align: center;
    font-weight: 700;
    padding: 18px;
    border-radius: 10px 10px 0 0;
    font-size: 1.5rem;
}
td {
    background: rgba(255,255,255,0.9);
    padding: 16px;
    color: #333;
    text-align: center;
    font-size: 1.3rem;
}
tr:hover td {
    background: rgba(127,0,255,0.2);
}

/* Lottie Pet */
#lottie-pet {
    width: 180px;
    height: 180px;
    margin: 10px auto 25px;
}

/* Animations */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <div id="lottie-pet"></div>
    <h3><i class="fa-solid fa-user-doctor"></i> Doctor</h3>
    <a href="doctor_dashboard.php"><i class="fa-solid fa-chart-line"></i> Dashboard</a>
    <a href="view_mood_logs.php"><i class="fa-solid fa-heart"></i> Pet Mood Logs</a>
    <a href="doctor_profile.php"><i class="fa-solid fa-id-card"></i> Profile</a>
    <a href="logout.php" style="color:#ff7676;"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
</div>

<!-- Main Content -->
<div class="content">
    <h2 class="page-title"><i class="fa-solid fa-heart-pulse"></i> Pet Mood Logs</h2>
    <div class="table-card mt-5">
        <table class="table table-striped table-hover text-center">
            <thead>
                <tr>
                    <th>Owner</th>
                    <th>Energy</th>
                    <th>Appetite</th>
                    <th>Social</th>
                    <th>Play</th>
                    <th>Sleep</th>
                    <th>Vocal</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?= htmlspecialchars($row['username']); ?></td>
                        <td><?= htmlspecialchars($row['energy_level']); ?></td>
                        <td><?= htmlspecialchars($row['appetite']); ?></td>
                        <td><?= htmlspecialchars($row['social_interaction']); ?></td>
                        <td><?= htmlspecialchars($row['play_behavior']); ?></td>
                        <td><?= htmlspecialchars($row['sleep_rest']); ?></td>
                        <td><?= htmlspecialchars($row['vocalization']); ?></td>
                        <td><?= htmlspecialchars($row['other_info']); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<script>
lottie.loadAnimation({
    container: document.getElementById('lottie-pet'),
    renderer: 'svg',
    loop: true,
    autoplay: true,
    path: "https://assets4.lottiefiles.com/packages/lf20_myejiggj.json"
});
</script>
</body>
</html>

