<?php
// public/kot_print.php
// Expects ?order_id=...
require_once __DIR__ . '/../api/db.php';
$order_id = (int)($_GET['order_id'] ?? 0);
if (!$order_id) { echo "order_id required"; exit; }

$stmt = $mysqli->prepare("SELECT o.*, oi.*, m.name FROM orders o JOIN order_items oi ON oi.order_id = o.id JOIN menu_items m ON m.id = oi.menu_id WHERE o.id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$res = $stmt->get_result();
$rows = $res->fetch_all(MYSQLI_ASSOC);
$stmt->close();

if (count($rows) === 0) { echo "No items found"; exit; }
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>KOT #<?= $order_id ?></title>
<style>
body{font-family: Arial; font-size: 14px; }
.kot { width: 320px; margin: 0 auto; border:1px solid #000; padding:10px; }
</style>
</head>
<body onload="window.print()">
<div class="kot">
  <h4>KOT — Order #<?= $order_id ?></h4>
  <div>Table: <?= htmlspecialchars($rows[0]['table_no']) ?></div>
  <hr>
  <ul>
  <?php foreach ($rows as $r): ?>
    <li><?= htmlspecialchars($r['name']) ?> × <?= $r['quantity'] ?></li>
  <?php endforeach; ?>
  </ul>
</div>
</body>
</html>
