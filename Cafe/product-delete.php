<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Invalid request method']);
    exit;
}

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

if ($id <= 0) {
    echo json_encode(['error' => 'Invalid product id']);
    exit;
}

$productFile = 'product.json';

if (!file_exists($productFile)) {
    echo json_encode(['error' => 'Product file not found']);
    exit;
}

$jsonData = file_get_contents($productFile);
$products = json_decode($jsonData, true);

if (!is_array($products)) {
    echo json_encode(['error' => 'Invalid product data']);
    exit;
}

$found = false;
foreach ($products as $key => $product) {
    if ($product['id'] === $id) {
        unset($products[$key]);
        $found = true;
        break;
    }
}

if (!$found) {
    echo json_encode(['error' => 'Product not found']);
    exit;
}

// Reindex array
$products = array_values($products);

if (file_put_contents($productFile, json_encode($products, JSON_PRETTY_PRINT))) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Failed to save product']);
}
?>
