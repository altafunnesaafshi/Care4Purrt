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
    if ($role === 'owner') {
        $stmt = $conn->prepare("
            SELECT id, name, COALESCE(pet_age, age) AS age, owner_name, health_status, diet_plan, other_recommendations, doctor_name, doctor_specialty, pet_picture
            FROM pets
            WHERE owner_name = ?
            ORDER BY id DESC
        ");
        $stmt->bind_param("s", $user);
        $stmt->execute();
        $res = $stmt->get_result();
    } else {
        $res = mysqli_query($conn, "
            SELECT id, name, COALESCE(pet_age, age) AS age, owner_name, health_status, diet_plan, other_recommendations, doctor_name, doctor_specialty, pet_picture
            FROM pets
            ORDER BY id DESC
        ");
    }
    $rows = [];
    while ($row = $res->fetch_assoc()) $rows[] = $row;
    json_out(['ok' => true, 'data' => $rows]);
}
if ($method === 'POST') {
    if ($role !== 'doctor') {
        json_out(['ok' => false, 'error' => 'Only doctors can update health status'], 403);
    }
    $pet_id = (int)($_POST['pet_id'] ?? 0);
    $health_status = trim($_POST['health_status'] ?? '');
    $diet_plan = trim($_POST['diet_plan'] ?? '');
    $other_recommendations = trim($_POST['other_recommendations'] ?? '');
    $doctor_name = trim($_POST['doctor_name'] ?? '');
    $doctor_specialty = trim($_POST['doctor_specialty'] ?? '');

    if ($pet_id <= 0 || $health_status === '' || $diet_plan === '' || $doctor_name === '' || $doctor_specialty === '') {
        json_out(['ok' => false, 'error' => 'Missing required fields.'], 422);
    }
    $stmt = $conn->prepare("
        UPDATE pets
        SET health_status=?, diet_plan=?, other_recommendations=?, doctor_name=?, doctor_specialty=?
        WHERE id=?
    ");
    $stmt->bind_param("sssssi", $health_status, $diet_plan, $other_recommendations, $doctor_name, $doctor_specialty, $pet_id);

    if (!$stmt->execute()) {
        json_out(['ok' => false, 'error' => 'DB error: ' . $stmt->error], 500);
    }
    json_out(['ok' => true, 'message' => 'Health status updated successfully.']);
}
json_out(['ok' => false, 'error' => 'Method not allowed'], 405);
