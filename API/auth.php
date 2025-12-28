<?php
require_once __DIR__ . '/_bootstrap.php';
$action = $_GET['action'] ?? '';
if (method() === 'GET' && $action === 'me') {
    if (empty($_SESSION['username'])) {
        json_ok(['user' => null]);
    }
    json_ok(['user' => [
        'username' => $_SESSION['username'],
        'role' => $_SESSION['role']
    ]]);
}
if (method() === 'POST' && $action === 'login') {
    $data = input();
    $username = trim($data['username'] ?? '');
    $password = trim($data['password'] ?? '');

    if ($username === '' || $password === '') {
        json_fail('Username and password are required.', 422);
    }
    if ($username === 'admin' && $password === 'admin123') {
        $_SESSION['username'] = 'admin';
        $_SESSION['role'] = 'admin';
        json_ok(['message' => 'Logged in as admin.']);
    }
    $stmt = $conn->prepare("SELECT username, password, role FROM users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();

    if (!$row || !password_verify($password, $row['password'])) {
        json_fail('Invalid credentials.', 401);
    }
    $_SESSION['username'] = $row['username'];
    $_SESSION['role'] = $row['role'];

    json_ok(['message' => 'Login successful.', 'role' => $row['role']]);
}
if (method() === 'POST' && $action === 'logout') {
    session_destroy();
    json_ok(['message' => 'Logged out.']);
}
json_fail('Invalid route.', 404);

