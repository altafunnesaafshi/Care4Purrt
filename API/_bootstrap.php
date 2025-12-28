<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/../db.php';
header('Content-Type: application/json; charset=utf-8');

function json_ok(array $data = [], int $code = 200): void {
    http_response_code($code);
    echo json_encode(['ok' => true] + $data, JSON_UNESCAPED_UNICODE);
    exit;
}
function json_fail(string $message, int $code = 400, array $extra = []): void {
    http_response_code($code);
    echo json_encode(['ok' => false, 'error' => $message] + $extra, JSON_UNESCAPED_UNICODE);
    exit;
}
function require_login(?string $role = null): void {
    if (empty($_SESSION['username']) || empty($_SESSION['role'])) {
        json_fail('Unauthorized. Please login.', 401);
    }
    if ($role !== null && $_SESSION['role'] !== $role) {
        json_fail('Forbidden for your role.', 403);
    }
}
function method(): string {
    return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
}
function input(): array {
    //accept form-data OR raw JSON
    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
    if (stripos($contentType, 'application/json') !== false) {
        $raw = file_get_contents('php://input');
        $parsed = json_decode($raw, true);
        return is_array($parsed) ? $parsed : [];
    }
    return $_POST ?? [];
}



