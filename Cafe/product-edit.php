<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Invalid request method']);
    exit;
}

require_once 'config.php';
requireLogin();

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$price = isset($_POST['price']) ? floatval($_POST['price']) : 0;

if ($id <= 0 || $name === '' || $price <= 0) {
    echo json_encode(['error' => 'Invalid input']);
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
foreach ($products as &$product) {
    if ($product['id'] === $id) {
        $product['name'] = $name;
        $product['price'] = $price;

        // Handle image upload if provided
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
                $product['image'] = $fileName;
            } else {
                echo json_encode(['error' => 'Failed to upload image']);
                exit;
            }
        } else {
            // If no new image uploaded, keep existing image
            if (isset($_POST['image']) && trim($_POST['image']) !== '') {
                $product['image'] = trim($_POST['image']);
            }
        }

        $found = true;
        break;
    }
}

if (!$found) {
    echo json_encode(['error' => 'Product not found']);
    exit;
}

if (file_put_contents($productFile, json_encode($products, JSON_PRETTY_PRINT))) {
    echo json_encode(['success' => true, 'product' => $product]);
} else {
    echo json_encode(['error' => 'Failed to save product']);
}
?>
