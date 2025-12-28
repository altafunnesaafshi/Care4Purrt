<?php
require_once __DIR__ . '/_bootstrap.php';
require_login('owner');
$username = $_SESSION['username'];

if (method() === 'GET') {
    $stmt = $conn->prepare("SELECT * FROM pets WHERE owner_username=? ORDER BY id DESC");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $pets = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    json_ok(['pets' => $pets]);
}
if (method() === 'POST') {
    $data = input();
    $name = trim($data['name'] ?? '');
    $type = trim($data['type'] ?? '');
    $age  = trim($data['age'] ?? '');

    if ($name === '' || $type === '') {
        json_fail('Pet name and type are required.', 422);
    }
    $stmt = $conn->prepare("INSERT INTO pets (owner_username, name, type, age) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $name, $type, $age);

    if (!$stmt->execute()) {
        json_fail('Failed to add pet.', 500);
    }

    json_ok(['message' => 'Pet added successfully.']);
}
if (method() === 'PUT') {
    $id = (int)($_GET['id'] ?? 0);
    if ($id <= 0) json_fail('Invalid pet id.', 422);

    $data = input();
    $name = trim($data['name'] ?? '');
    $type = trim($data['type'] ?? '');
    $age  = trim($data['age'] ?? '');

    $stmt = $conn->prepare("UPDATE pets SET name=?, type=?, age=? WHERE id=? AND owner_username=?");
    $stmt->bind_param("sssis", $name, $type, $age, $id, $username);

    if (!$stmt->execute()) json_fail('Failed to update pet.', 500);
    json_ok(['message' => 'Pet updated.']);
}
if (method() === 'DELETE') {
    $id = (int)($_GET['id'] ?? 0);
    if ($id <= 0) json_fail('Invalid pet id.', 422);

    $stmt = $conn->prepare("DELETE FROM pets WHERE id=? AND owner_username=?");
    $stmt->bind_param("is", $id, $username);

    if (!$stmt->execute()) json_fail('Failed to delete pet.', 500);
    json_ok(['message' => 'Pet deleted.']);
}

json_fail('Method not allowed.', 405);

