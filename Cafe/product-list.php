<?php
header('Content-Type: application/json');
$productFile = 'product.json';

if (!file_exists($productFile)) {
    echo json_encode([]);
    exit;
}

$jsonData = file_get_contents($productFile);
$products = json_decode($jsonData, true);

echo json_encode($products);
?>
<?php
require_once 'config.php';
requireLogin();
?>
