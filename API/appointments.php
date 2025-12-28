<?php
require_once __DIR__ . '/_bootstrap.php';
if (method() === 'POST') {
    require_login('owner');

    $data = input();
    $owner = $_SESSION['username'];
    $doctor_username   = trim($data['doctor'] ?? '');
    $pet_type          = trim($data['pet_type'] ?? '');
    $pet_age           = trim($data['pet_age'] ?? '');
    $pet_problem       = trim($data['pet_problem'] ?? '');
    $appointment_date  = trim($data['appointment_date'] ?? '');
    $appointment_time  = trim($data['appointment_time'] ?? '');

    if ($doctor_username === '' || $appointment_date === '' || $appointment_time === '') {
        json_fail('Doctor, date, and time are required.', 422);
    }
    $stmt = $conn->prepare("
        INSERT INTO appointments
        (pet_owner_username, doctor_username, pet_type, pet_age, pet_problem, appointment_date, appointment_time)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param(
        "sssssss",
        $owner, $doctor_username, $pet_type, $pet_age, $pet_problem, $appointment_date, $appointment_time
    );

    if (!$stmt->execute()) {
        json_fail('Failed to book appointment.', 500);
    }

    json_ok(['message' => 'Appointment booked successfully.']);
}
if (method() === 'GET') {
    require_login();
    $role = $_SESSION['role'];
    $user = $_SESSION['username'];

    if ($role === 'owner') {
        $stmt = $conn->prepare("SELECT * FROM appointments WHERE pet_owner_username=? ORDER BY id DESC");
        $stmt->bind_param("s", $user);
    } elseif ($role === 'doctor') {
        $stmt = $conn->prepare("SELECT * FROM appointments WHERE doctor_username=? ORDER BY id DESC");
        $stmt->bind_param("s", $user);
    } else {
        $stmt = $conn->prepare("SELECT * FROM appointments ORDER BY id DESC");
    }
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    json_ok(['appointments' => $rows]);
}
json_fail('Method not allowed.', 405);





