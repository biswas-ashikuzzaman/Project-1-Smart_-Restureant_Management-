<?php
include 'db.php';
header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

if ($action === 'create') {
    $data = json_decode(file_get_contents('php://input'), true);
    $table_no = $data['table_no'];
    $items = $data['items'];

    $stmt = $conn->prepare("INSERT INTO orders (table_no, total_amount) VALUES (?, 0)");
    $stmt->bind_param("i", $table_no);
    $stmt->execute();
    $order_id = $stmt->insert_id;

    $total = 0;
    foreach ($items as $item) {
        $menu_id = $item['menu_id'];
        $qty = $item['quantity'];

        $priceRes = $conn->query("SELECT price FROM menu WHERE id = $menu_id");
        $price = $priceRes->fetch_assoc()['price'];
        $total += $price * $qty;

        $conn->query("INSERT INTO order_items (order_id, menu_id, quantity) VALUES ($order_id, $menu_id, $qty)");
    }

    $conn->query("UPDATE orders SET total_amount = $total WHERE id = $order_id");

    echo json_encode(["status" => "success", "order_id" => $order_id]);
}

if ($action === 'list') {
    $result = $conn->query("SELECT * FROM orders");
    $orders = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($orders);
}
