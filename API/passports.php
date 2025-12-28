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
if (!function_exists('save_upload')) {
    function save_upload(array $file, string $subdir): array {
        if (!isset($file['tmp_name']) || $file['error'] !== 0) {
            return [false, 'No file uploaded', ''];
        }
        $allowed = ['jpg','jpeg','png','webp'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed, true)) {
            return [false, 'Only jpg/jpeg/png/webp allowed', ''];
        }
        $baseDir = realpath(__DIR__ . '/../uploads');
        if ($baseDir === false) $baseDir = __DIR__ . '/../uploads';

        $targetDir = $baseDir . '/' . $subdir;
        if (!is_dir($targetDir)) {
            @mkdir($targetDir, 0775, true);
        }
        $safeName = uniqid('pp_', true) . '.' . $ext;
        $targetPath = $targetDir . '/' . $safeName;

        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            return [false, 'Upload move failed', ''];
        }
        $relative = 'uploads/' . $subdir . '/' . $safeName;
        return [true, 'Uploaded', $relative];
    }
}
if (!isset($_SESSION['username'], $_SESSION['role'])) {
    json_out(['ok' => false, 'error' => 'Unauthorized'], 401);
}
$user = $_SESSION['username'];
$role = $_SESSION['role'];
$method = $_SERVER['REQUEST_METHOD'];

function generate_passport_no(mysqli $conn): string {
    for ($i = 0; $i < 10; $i++) {
        $candidate = 'PP' . date('Ymd') . '-' . str_pad((string)random_int(0, 9999), 4, '0', STR_PAD_LEFT);
        $stmt = $conn->prepare("SELECT 1 FROM pet_passports WHERE passport_number = ? LIMIT 1");
        $stmt->bind_param("s", $candidate);
        $stmt->execute();
        $exists = $stmt->get_result()->fetch_row();
        if (!$exists) return $candidate;
    }
    return 'PP' . date('Ymd') . '-' . uniqid();
}
if ($method === 'GET') {
    if ($role !== 'owner') {
        json_out(['ok' => false, 'error' => 'Only owners can view their passports from this endpoint'], 403);
    }
    $stmt = $conn->prepare("
        SELECT pp.*, p.name AS pet_name, COALESCE(p.pet_age, p.age) AS pet_age, p.owner_name, p.pet_picture
        FROM pet_passports pp
        JOIN pets p ON pp.pet_id = p.id
        WHERE p.owner_name = ?
        ORDER BY pp.id DESC
    ");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $res = $stmt->get_result();

    $rows = [];
    while ($row = $res->fetch_assoc()) $rows[] = $row;

    json_out(['ok' => true, 'data' => $rows]);
}
if ($method === 'POST') {
    if ($role !== 'doctor' && $role !== 'admin') {
        json_out(['ok' => false, 'error' => 'Only doctor/admin can generate or update passports'], 403);
    }
    $pet_id = (int)($_POST['pet_id'] ?? 0);
    $vaccination_status = trim($_POST['vaccination_status'] ?? '');
    $vaccination_date   = trim($_POST['vaccination_date'] ?? '');

    if ($pet_id <= 0 || $vaccination_status === '' || $vaccination_date === '') {
        json_out(['ok' => false, 'error' => 'pet_id, vaccination_status, vaccination_date are required'], 422);
    }
    $pet_picture = '';
    $passport_picture = '';

    if (!empty($_FILES['pet_picture']['name'])) {
        [$ok, $msg, $path] = save_upload($_FILES['pet_picture'], 'pets');
        if (!$ok) json_out(['ok' => false, 'error' => 'Pet picture: ' . $msg], 422);
        $pet_picture = $path;
    }
    if (!empty($_FILES['passport_picture']['name'])) {
        [$ok, $msg, $path] = save_upload($_FILES['passport_picture'], 'passports');
        if (!$ok) json_out(['ok' => false, 'error' => 'Passport picture: ' . $msg], 422);
        $passport_picture = $path;
    }
    $stmtPet = $conn->prepare("SELECT name FROM pets WHERE id = ? LIMIT 1");
    $stmtPet->bind_param("i", $pet_id);
    $stmtPet->execute();
    $petRow = $stmtPet->get_result()->fetch_assoc();
    if (!$petRow) json_out(['ok' => false, 'error' => 'Invalid pet_id'], 404);
    $pet_name = $petRow['name'] ?? '';

    // check existing passport
    $stmtCheck = $conn->prepare("SELECT id FROM pet_passports WHERE pet_id = ? LIMIT 1");
    $stmtCheck->bind_param("i", $pet_id);
    $stmtCheck->execute();
    $existing = $stmtCheck->get_result()->fetch_assoc();

    if ($existing) {
        // update
        $sql = "UPDATE pet_passports
                SET vaccination_status=?, vaccination_date=?"
             . ($pet_picture ? ", pet_picture=?" : "")
             . ($passport_picture ? ", passport_picture=?" : "")
             . " WHERE pet_id=?";

        if ($pet_picture && $passport_picture) {
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssi", $vaccination_status, $vaccination_date, $pet_picture, $passport_picture, $pet_id);
        } elseif ($pet_picture && !$passport_picture) {
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssi", $vaccination_status, $vaccination_date, $pet_picture, $pet_id);
        } elseif (!$pet_picture && $passport_picture) {
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssi", $vaccination_status, $vaccination_date, $passport_picture, $pet_id);
        } else {
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssi", $vaccination_status, $vaccination_date, $pet_id);
        }

        if (!$stmt->execute()) json_out(['ok' => false, 'error' => 'DB error: ' . $stmt->error], 500);

        json_out(['ok' => true, 'message' => 'Passport updated successfully.']);
    }
    $passport_no = generate_passport_no($conn);

    $stmt = $conn->prepare("
        INSERT INTO pet_passports
        (pet_id, name, passport_number, vaccination_status, vaccination_date, pet_picture, passport_picture)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");

    $pet_pic_val = $pet_picture;         
    $pass_pic_val = $passport_picture;   
    $stmt->bind_param("issssss", $pet_id, $pet_name, $passport_no, $vaccination_status, $vaccination_date, $pet_pic_val, $pass_pic_val);

    if (!$stmt->execute()) {
        json_out(['ok' => false, 'error' => 'DB error: ' . $stmt->error], 500);
    }

    json_out(['ok' => true, 'message' => 'Passport generated successfully.', 'passport_number' => $passport_no]);
}
json_out(['ok' => false, 'error' => 'Method not allowed'], 405);



