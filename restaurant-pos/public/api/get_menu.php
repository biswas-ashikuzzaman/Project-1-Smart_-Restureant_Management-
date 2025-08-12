<?php
// api/get_menu.php
header('Content-Type: application/json');
require_once __DIR__ . '/db.php';

$q = $_GET['q'] ?? '';
if ($q) {
    $like = '%' . $mysqli->real_escape_string($q) . '%';
    $stmt = $mysqli->prepare("SELECT id, name, price, category, image FROM menu_items WHERE name LIKE ? OR category LIKE ? AND is_active = 1");
    $stmt->bind_param("ss", $like, $like);
    $stmt->execute();
    $res = $stmt->get_result();
    $rows = $res->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    echo json_encode($rows);
    exit;
}

$result = $mysqli->query("SELECT id, name, price, category, image FROM menu_items WHERE is_active = 1 ORDER BY id ASC");
$menu = $result->fetch_all(MYSQLI_ASSOC);
echo json_encode($menu);
