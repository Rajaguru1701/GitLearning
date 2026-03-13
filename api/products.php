<?php
// API: Get products by category or subcategory
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../config/db.php';

$db = getDB();

$category_id    = isset($_GET['category_id'])    ? (int)$_GET['category_id']    : 0;
$subcategory_id = isset($_GET['subcategory_id']) ? (int)$_GET['subcategory_id'] : 0;
$featured       = isset($_GET['featured']) && $_GET['featured'] == '1';
$limit          = isset($_GET['limit']) ? (int)$_GET['limit'] : 100;

$sql    = "SELECT p.*, c.name AS category_name, s.name AS subcategory_name
           FROM products p
           LEFT JOIN categories c ON p.category_id = c.id
           LEFT JOIN subcategories s ON p.subcategory_id = s.id
           WHERE p.stock > 0";
$params = [];

if ($subcategory_id) {
    $sql .= " AND p.subcategory_id = ?";
    $params[] = $subcategory_id;
} elseif ($category_id) {
    $sql .= " AND p.category_id = ?";
    $params[] = $category_id;}

if ($featured) {
    $sql .= " AND p.is_featured = 1";
}

$sql .= " ORDER BY p.created_at DESC LIMIT ?";
$params[] = $limit;

$stmt = $db->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

// Format image paths
foreach ($products as &$p) {
    if ($p['image'] && strpos($p['image'], 'http') !== 0) {
        $p['image'] = '../uploads/' . $p['image'];
    }
    $p['sizes_array'] = $p['sizes'] ? explode(',', $p['sizes']) : [];
}

echo json_encode(['success' => true, 'data' => $products]);
?>
