<?php
// public/receipt.php
require_once __DIR__ . '/../api/db.php';
$order_id = (int)($_GET['order_id'] ?? 0);
if (!$order_id) { echo "order_id required"; exit; }

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

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Receipt #<?= $order_id ?></title>
<style>
body{ font-family: Arial; }
.table { width:100%; border-collapse: collapse; }
.table th, .table td { border:1px solid #ddd; padding:8px; }
</style>
</head>
<body onload="window.print()">
  <h3>Restaurant Receipt</h3>
  <p>Order ID: <?= $order_id ?> | Table: <?= htmlspecialchars($order['table_no']) ?></p>
  <table class="table">
    <tr><th>Item</th><th>Qty</th><th>Price</th></tr>
    <?php foreach ($items as $it): ?>
      <tr>
        <td><?= htmlspecialchars($it['name']) ?></td>
        <td><?= (int)$it['quantity'] ?></td>
        <td>৳<?= number_format($it['price'] * $it['quantity'], 2) ?></td>
      </tr>
    <?php endforeach; ?>
  </table>
  <h4>Total: ৳<?= number_format($order['total_amount'], 2) ?></h4>
</body>
</html>
