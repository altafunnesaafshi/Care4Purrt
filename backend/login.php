<?php
session_start();
include('db.php');

if (isset($_SESSION['username'])) {
    switch ($_SESSION['role']) {
        case 'owner': header("Location: pet_owner_dashboard.php"); exit();
        case 'doctor': header("Location: doctor_dashboard.php"); exit();
        case 'admin': header("Location: admin_dashboard.php"); exit();
    }
}
/*
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    if ($username === "admin" && $password === "admin123") {
        $_SESSION['username'] = $username;
        $_SESSION['role'] = 'admin';
        header("Location: admin_dashboard.php");
        exit();
    }
    $stmt = $conn->prepare("SELECT username, password, role FROM users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($db_username, $db_password, $db_role);
        $stmt->fetch();

        if (password_verify($password, $db_password)) {
            $_SESSION['username'] = $db_username;
            $_SESSION['role'] = $db_role;

            switch ($db_role) {
                case 'owner': header("Location: pet_owner_dashboard.php"); break;
                case 'doctor': header("Location: doctor_dashboard.php"); break;
            }
            exit();
        } else {
            $error_message = "Invalid password!";
        }
    } else {
        $error_message = "No user found!";
    }
    $stmt->close();
}
*/
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - Care4Purrt</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
/* Page Background */
body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #E6E6FA, #F8F0FF);
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 30px;
    font-size: 22px; /* BIGGER base font */
}

/* FULL-SCREEN WIDE CARD */
.login-card {
    background: #ffffff;
    border-radius: 35px;
    padding: 70px 60px;
    width: 95%;                  /* ALMOST FULL SCREEN */
    max-width: 1400px;           /* But limited for desktops */
    box-shadow: 0 20px 50px rgba(0,0,0,0.2);
    text-align: center;
    transition: 0.3s ease;
}
.login-card:hover {
    transform: translateY(-5px);
}

/* Cute Pet Image */
.pet-image {
    width: 150px; /* BIGGER */
    margin-bottom: 30px;
}

/* Title */
.login-card h3 {
    color: #4B6CB7;
    margin-bottom: 40px;
    font-size: 3rem; /* HUGE title */
    font-weight: 700;
}

/* BIGGER Inputs */
.form-control {
    border-radius: 35px;
    padding: 25px 30px; /* Bigger padding */
    font-size: 1.6rem;  /* Larger text */
    border: 1px solid #ccc;
    box-shadow: inset 0 3px 8px rgba(0,0,0,0.08);
    transition: all 0.3s;
}
.form-control:focus {
    border-color: #4B6CB7;
    box-shadow: 0 0 12px rgba(75,108,183,0.4);
}

/* BIG login button */
.btn-login {
    background: linear-gradient(135deg, #4B6CB7, #182848);
    border: none;
    color: white;
    font-size: 2rem; /* Bigger */
    font-weight: 700;
    border-radius: 40px;
    padding: 25px;
    width: 100%;
    transition: 0.3s ease;
}
.btn-login:hover {
    transform: scale(1.05);
    box-shadow: 0 12px 40px rgba(75,108,183,0.5);
}

/* Error alert */
.alert {
    border-radius: 20px;
    padding: 20px;
    font-size: 1.4rem;
    margin-bottom: 20px;
}

/* Footer Link */
.text-center {
    font-size: 1.6rem;
    margin-top: 35px;
}
.text-center a {
    color: #4B6CB7;
    font-weight: 600;
}
.text-center a:hover {
    text-decoration: underline;
}
</style>
</head>
<body>

<div class="login-card">

    <img src="https://cdn-icons-png.flaticon.com/512/616/616408.png" class="pet-image" alt="Pet Icon">

    <h3>Welcome to Care4Purrt</h3>

    <div id="msgBox"></div>

    <form id="loginForm" method="POST">
        <div class="mb-4">
            <input type="text" class="form-control" name="username" placeholder="Username" required>
        </div>

        <div class="mb-4">
            <input type="password" class="form-control" name="password" placeholder="Password" required>
        </div>

        <button type="submit" class="btn-login">Login</button>
    </form>

    <p class="text-center">Don't have an account? <a href="register.php">Sign Up</a></p>

</div>

<script>
const form = document.getElementById('loginForm');
const msgBox = document.getElementById('msgBox');

form.addEventListener('submit', async (e) => {
  e.preventDefault();
  if (msgBox) msgBox.innerHTML = '';

  const fd = new FormData(form);

  try {
    const res = await fetch('api/auth.php?action=login', { method: 'POST', body: fd });
    const data = await res.json();

    if (!data.ok) {
      const msg = data.error || 'Login failed';
      if (msgBox) msgBox.innerHTML = `<div class="alert alert-danger">${msg}</div>`;
      else alert(msg);
      return;
    }

    if (data.role === 'admin') window.location.href = 'admin_dashboard.php';
    else if (data.role === 'doctor') window.location.href = 'doctor_dashboard.php';
    else window.location.href = 'pet_owner_dashboard.php';

  } catch (err) {
    const msg = 'Network error. Please try again.';
    if (msgBox) msgBox.innerHTML = `<div class="alert alert-danger">${msg}</div>`;
    else alert(msg);
  }
});
</script>
</body>
</html>
