<?php
require_once __DIR__ . '/../config/db.php';
$db = getDB();

try {
    $sql = "ALTER TABLE posters ADD COLUMN is_popup TINYINT(1) DEFAULT 0;";
    $db->exec($sql);
    echo "Column 'is_popup' added successfully!";
} catch (PDOException $e) {
    echo "Error updating table: " . $e->getMessage();
}
?>
