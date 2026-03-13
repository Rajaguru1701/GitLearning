<?php
// API: Get all categories with subcategories
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../config/db.php';

$db = getDB();
$cats = $db->query("SELECT * FROM categories ORDER BY sort_order ASC, name ASC")->fetchAll();

foreach ($cats as &$cat) {
    $stmt = $db->prepare("SELECT * FROM subcategories WHERE category_id = ? ORDER BY name ASC");
    $stmt->execute([$cat['id']]);
    $cat['subcategories'] = $stmt->fetchAll();
}

echo json_encode(['success' => true, 'data' => $cats]);
?>
