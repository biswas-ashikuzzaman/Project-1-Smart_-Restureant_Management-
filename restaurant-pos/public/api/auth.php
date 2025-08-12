<?php
// api/auth.php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/db.php';

$action = $_GET['action'] ?? '';

if ($action === 'login') {
    $data = json_decode(file_get_contents('php://input'), true);
    $username = $data['username'] ?? '';
    $password = $data['password'] ?? '';

    if (!$username || !$password) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid data']);
        exit;
    }

    $stmt = $mysqli->prepare("SELECT id, username, password_hash, role FROM users WHERE username = ?");
    if (!$stmt) { http_response_code(500); echo json_encode(['error'=>'prepare_failed']); exit; }
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $res = $stmt->get_result();
    $user = $res->fetch_assoc();
    $stmt->close();

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user'] = [
            'id' => (int)$user['id'],
            'username' => $user['username'],
            'role' => $user['role'],
        ];
        echo json_encode(['success' => true, 'user' => $_SESSION['user']]);
    } else {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Invalid credentials']);
    }
    exit;
}

if ($action === 'me') {
    echo json_encode(['user' => $_SESSION['user'] ?? null]);
    exit;
}

if ($action === 'logout') {
    session_destroy();
    echo json_encode(['success' => true]);
    exit;
}

http_response_code(400);
echo json_encode(['error' => 'unknown_action']);
