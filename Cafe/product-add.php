<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Invalid request method']);
    exit;
}

require_once 'config.php';
requireLogin();

$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$price = isset($_POST['price']) ? floatval($_POST['price']) : 0;

if ($name === '' || $price <= 0) {
    echo json_encode(['error' => 'Invalid input']);
    exit;
}

$image = '';
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = 'images/';
    $tmpName = $_FILES['image']['tmp_name'];
    $fileName = basename($_FILES['image']['name']);
    $targetFilePath = $uploadDir . $fileName;

    $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

    if (!in_array($fileType, $allowedTypes)) {
        echo json_encode(['error' => 'Only JPG, JPEG, PNG, and GIF files are allowed']);
        exit;
    }

    if (move_uploaded_file($tmpName, $targetFilePath)) {
        // Save with 'images/' prefix
        $image = 'images/' . $fileName;
    } else {
        echo json_encode(['error' => 'Failed to upload image']);
        exit;
    }
} else {
    echo json_encode(['error' => 'Image file is required']);
    exit;
}

$productFile = 'product.json';

if (!file_exists($productFile)) {
    $products = [];
} else {
    $jsonData = file_get_contents($productFile);
    $products = json_decode($jsonData, true);
    if (!is_array($products)) {
        $products = [];
    }
}

// Generate new unique id
$maxId = 0;
foreach ($products as $product) {
    if ($product['id'] > $maxId) {
        $maxId = $product['id'];
    }
}
$newId = $maxId + 1;

$newProduct = [
    'id' => $newId,
    'name' => $name,
    'price' => $price,
    'image' => $image // store with 'images/' prefix
];

$products[] = $newProduct;

if (file_put_contents($productFile, json_encode($products, JSON_PRETTY_PRINT))) {
    echo json_encode(['success' => true, 'product' => $newProduct]);
} else {
    echo json_encode(['error' => 'Failed to save product']);
}
?>
