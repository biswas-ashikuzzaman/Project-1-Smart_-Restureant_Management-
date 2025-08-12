<?php
// api/add_order.php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/db.php';

$data = json_decode(file_get_contents('php://input'), true);
if (!$data) { http_response_code(400); echo json_encode(['error'=>'invalid_json']); exit; }

$table_no = $data['table_no'] ?? null;
$items = $data['items'] ?? [];

if (!$table_no || !is_array($items) || count($items) === 0) {
    http_response_code(400);
    echo json_encode(['error' => 'table_no and items required']);
    exit;
}

// Insert order
$stmt = $mysqli->prepare("INSERT INTO orders (table_no, status) VALUES (?, 'pending')");
$stmt->bind_param("s", $table_no);
$stmt->execute();
$order_id = $stmt->insert_id;
$stmt->close();

$total_amount = 0.0;
$it_stmt = $mysqli->prepare("INSERT INTO order_items (order_id, menu_id, quantity, price) VALUES (?, ?, ?, ?)");
foreach ($items as $it) {
    $menu_id = (int)$it['id'];
    $qty = (int)$it['qty'];

    // get price
    $pstmt = $mysqli->prepare("SELECT price FROM menu_items WHERE id = ?");
    $pstmt->bind_param("i", $menu_id);
    $pstmt->execute();
    $pres = $pstmt->get_result()->fetch_assoc();
    $pstmt->close();
    $price = $pres ? (float)$pres['price'] : 0.0;
    $subtotal = $price * $qty;
    $total_amount += $subtotal;

    $it_stmt->bind_param("iiid", $order_id, $menu_id, $qty, $price);
    $it_stmt->execute();

    // create KOT ticket row
    $kstmt = $mysqli->prepare("INSERT INTO kot_tickets (order_id, item_id, qty, status) VALUES (?, ?, ?, 'pending')");
    $kstmt->bind_param("iii", $order_id, $menu_id, $qty);
    $kstmt->execute();
    $kstmt->close();
}
$it_stmt->close();

// update total
$ustmt = $mysqli->prepare("UPDATE orders SET total_amount = ? WHERE id = ?");
$ustmt->bind_param("di", $total_amount, $order_id);
$ustmt->execute();
$ustmt->close();

echo json_encode(['success' => true, 'order_id' => $order_id, 'total' => $total_amount]);
