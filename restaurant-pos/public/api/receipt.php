<?php
// api/receipt_data.php
header('Content-Type: application/json');
require_once __DIR__ . '/db.php';

$order_id = (int)($_GET['order_id'] ?? 0);
if (!$order_id) { http_response_code(400); echo json_encode(['error'=>'order_id required']); exit; }

$stmt = $mysqli->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();
$stmt->close();

$stmt2 = $mysqli->prepare("SELECT oi.*, m.name FROM order_items oi JOIN menu_items m ON m.id = oi.menu_id WHERE oi.order_id = ?");
$stmt2->bind_param("i", $order_id);
$stmt2->execute();
$items = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt2->close();

echo json_encode(['order' => $order, 'items' => $items]);
