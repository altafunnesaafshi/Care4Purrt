<?php
require_once __DIR__ . '/_bootstrap.php';
if (!function_exists('json_out')) {
    function json_out($payload, int $code = 200): void {
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($payload);
        exit;
    }
}
if (!isset($_SESSION['username'], $_SESSION['role'])) {
    json_out(['ok' => false, 'error' => 'Unauthorized'], 401);
}
$user = $_SESSION['username'];
$role = $_SESSION['role'];
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    if ($role === 'doctor') {
        $sql = "SELECT * FROM pet_moods ORDER BY id DESC";
        $res = mysqli_query($conn, $sql);
    } else {
        $stmt = $conn->prepare("SELECT * FROM pet_moods WHERE username = ? ORDER BY id DESC");
        $stmt->bind_param("s", $user);
        $stmt->execute();
        $res = $stmt->get_result();
    }

    $rows = [];
    while ($row = $res->fetch_assoc()) $rows[] = $row;

    json_out(['ok' => true, 'data' => $rows]);
}

if ($method === 'POST') {
    if ($role !== 'owner') {
        json_out(['ok' => false, 'error' => 'Only pet owners can log moods'], 403);
    }

    $energy_level       = trim($_POST['energy_level'] ?? '');
    $appetite           = trim($_POST['appetite'] ?? '');
    $social_interaction = trim($_POST['social_interaction'] ?? '');
    $play_behavior      = trim($_POST['play_behavior'] ?? '');
    $sleep_rest         = trim($_POST['sleep_rest'] ?? '');
    $vocalization       = trim($_POST['vocalization'] ?? '');
    $other_info         = trim($_POST['other_info'] ?? '');

    if ($energy_level === '' || $appetite === '' || $social_interaction === '' || $play_behavior === '' || $sleep_rest === '' || $vocalization === '') {
        json_out(['ok' => false, 'error' => 'Please fill in all required mood fields.'], 422);
    }

    $stmt = $conn->prepare("
        INSERT INTO pet_moods
        (username, energy_level, appetite, social_interaction, play_behavior, sleep_rest, vocalization, other_info)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("ssssssss", $user, $energy_level, $appetite, $social_interaction, $play_behavior, $sleep_rest, $vocalization, $other_info);

    if (!$stmt->execute()) {
        json_out(['ok' => false, 'error' => 'DB error: ' . $stmt->error], 500);
    }

    json_out(['ok' => true, 'message' => 'Pet mood logged successfully.']);
}
json_out(['ok' => false, 'error' => 'Method not allowed'], 405);
