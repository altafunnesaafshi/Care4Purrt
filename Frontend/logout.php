<?php
session_start();

// clear all session variables
session_unset();

// destroy the session
session_destroy();

// redirect to login page (or index.php if you prefer)
header("Location: login.php");
exit;
