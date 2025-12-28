<?php
session_start();
include('db.php');

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php"); 
    exit();
}

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $sql = "DELETE FROM users WHERE id='$user_id'";

    if (mysqli_query($conn, $sql)) {
        header("Location: view_all_users.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    echo "Invalid request.";
}
?>
