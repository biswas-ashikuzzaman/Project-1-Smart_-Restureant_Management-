<?php
// api/kot.php
header('Content-Type: application/json');
require_once __DIR__ . '/db.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    // return pending/active kot tickets
    $res = $mysqli->query("SELECT k.id, k.order_id, k.item_id, k.qty, k.status, m.name AS item_name, o.table_no FROM kot_tickets k JOIN menu_items m ON m.id = k.item_id JOIN orders o ON o.id = k.order_id WHERE k.status != 'served' ORDER BY k.created_at ASC");
    echo json_encode($res->fetch_all(MYSQLI_ASSOC));
    exit;
}

if ($method === 'POST') {
    // update status
    $data = json_decode(file_get_contents('php://input'), true);
    $id = (int)$data['id'];
    $status = $mysqli->real_escape_string($data['status']);
    if (!$id || !$status) { http_response_code(400); echo json_encode(['error'=>'invalid']); exit; }
    $mysqli->query("UPDATE kot_tickets SET status = '{$status}' WHERE id = {$id}");
    echo json_encode(['success' => true]);
    exit;
}

http_response_code(405);
echo json_encode(['error' => 'method_not_allowed']);
