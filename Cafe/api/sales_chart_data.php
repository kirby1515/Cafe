<?php
header('Content-Type: application/json');
require_once '../config.php';

if (!isset($_SESSION)) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

$conn = $GLOBALS['conn'] ?? null;
if (!$conn) {
    $conn = mysqli_connect('localhost', 'root', '', 'cafe');
    if (!$conn) {
        echo json_encode(['error' => 'Database connection failed']);
        exit;
    }
}

// Get last 7 days dates
$dates = [];
$sales = [];
for ($i = 6; $i >= 0; $i--) {
    $dates[] = date('Y-m-d', strtotime("-$i days"));
    $sales[date('Y-m-d', strtotime("-$i days"))] = 0;
}

// Query sales grouped by date for last 7 days
$query = $conn->prepare("SELECT DATE(created_at) as order_date, SUM(returnPrice) as total_sales FROM orders WHERE DATE(created_at) >= ? GROUP BY DATE(created_at)");
$startDate = date('Y-m-d', strtotime('-6 days'));
$query->bind_param("s", $startDate);
$query->execute();
$result = $query->get_result();

while ($row = $result->fetch_assoc()) {
    $order_date = $row['order_date'];
    $total_sales = (float)$row['total_sales'];
    if (isset($sales[$order_date])) {
        $sales[$order_date] = $total_sales;
    }
}
$query->close();

echo json_encode([
    'labels' => $dates,
    'sales' => array_values($sales)
]);
?>
