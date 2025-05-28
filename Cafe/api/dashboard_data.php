<?php
header('Content-Type: application/json');
require_once '../config.php';

if (!isset($_SESSION)) {
    session_start();
}

/*
// Check if user is logged in
if (!isset($_SESSION['id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}
*/

$conn = $GLOBALS['conn'] ?? null;
if (!$conn) {
    $conn = mysqli_connect('localhost', 'root', '', 'cafe');
    if (!$conn) {
        echo json_encode(['error' => 'Database connection failed']);
        exit;
    }
}

 
// Query total orders
$orderQuery = $conn->prepare("SELECT COUNT(*) as total_orders FROM orders");
$orderQuery->execute();
$orderResult = $orderQuery->get_result();
$totalOrders = $orderResult->fetch_assoc()['total_orders'] ?? 0;
$orderQuery->close();

// Query total payments (sum of returnPrice)
$paymentQuery = $conn->prepare("SELECT SUM(returnPrice) as total_payments FROM orders");
$paymentQuery->execute();
$paymentResult = $paymentQuery->get_result();
$totalPayments = $paymentResult->fetch_assoc()['total_payments'] ?? 0;
$paymentQuery->close();

// Query total customers (distinct oname)
$customerQuery = $conn->prepare("SELECT COUNT(DISTINCT oname) as total_customers FROM orders");
$customerQuery->execute();
$customerResult = $customerQuery->get_result();
$totalCustomers = $customerResult->fetch_assoc()['total_customers'] ?? 0;
$customerQuery->close();

// Query total proceed (assuming proceed means orders with status 'proceed')
$proceedQuery = $conn->prepare("SELECT COUNT(*) as total_proceed FROM orders WHERE status = 'proceed'");
$proceedQuery->execute();
$proceedResult = $proceedQuery->get_result();
$totalProceed = $proceedResult->fetch_assoc()['total_proceed'] ?? 0;
$proceedQuery->close();

echo json_encode([
    'totalOrders' => (int)$totalOrders,
    'totalPayments' => (float)$totalPayments,
    'totalCustomers' => (int)$totalCustomers,
    'totalProceed' => (int)$totalProceed
]);
?>
