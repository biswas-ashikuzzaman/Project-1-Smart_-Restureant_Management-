<?php
// api/get_orders.php
header('Content-Type: application/json');
require_once __DIR__ . '/db.php';

$result = $mysqli->query("SELECT o.*, IFNULL(u.username,'') AS created_by FROM orders o LEFT JOIN users u ON u.id = o.created_by ORDER BY o.created_at DESC LIMIT 50");
$orders = $result->fetch_all(MYSQLI_ASSOC);
echo json_encode($orders);
