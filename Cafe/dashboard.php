<?php
require_once 'config.php';
requireLogin();

// Get today's date
$today = date('Y-m-d');

// Query total orders today
$orderQuery = $conn->prepare("SELECT COUNT(*) as total_orders FROM orders WHERE DATE(created_at) = ?");
if (!$orderQuery) {
    die("Prepare failed: " . $conn->error);
}
$orderQuery->bind_param("s", $today);
$orderQuery->execute();
$orderResult = $orderQuery->get_result();
$totalOrders = $orderResult->fetch_assoc()['total_orders'] ?? 0;
$orderQuery->close();

// Query total payments today (sum of returnPrice)
$paymentQuery = $conn->prepare("SELECT SUM(returnPrice) as total_payments FROM orders WHERE DATE(created_at) = ?");
if (!$paymentQuery) {
    die("Prepare failed: " . $conn->error);
}
$paymentQuery->bind_param("s", $today);
$paymentQuery->execute();
$paymentResult = $paymentQuery->get_result();
$totalPayments = $paymentResult->fetch_assoc()['total_payments'] ?? 0;
$paymentQuery->close();

// Query total customers today (distinct login_id)
$customerQuery = $conn->prepare("SELECT COUNT(DISTINCT login_id) as total_customers FROM orders WHERE DATE(created_at) = ?");
if (!$customerQuery) {
    die("Prepare failed: " . $conn->error);
}
$customerQuery->bind_param("s", $today);
$customerQuery->execute();
$customerResult = $customerQuery->get_result();
$totalCustomers = $customerResult->fetch_assoc()['total_customers'] ?? 0;
$customerQuery->close();

// Query total proceed today (assuming proceed means orders with status 'proceed')
$proceedQuery = $conn->prepare("SELECT COUNT(*) as total_proceed FROM orders WHERE DATE(created_at) = ? AND status = 'proceed'");
if (!$proceedQuery) {
    die("Prepare failed: " . $conn->error);
}
$proceedQuery->bind_param("s", $today);
$proceedQuery->execute();
$proceedResult = $proceedQuery->get_result();
$totalProceed = $proceedResult->fetch_assoc()['total_proceed'] ?? 0;
$proceedQuery->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/dashboard.css" />
</head>
<body>
    <div class="dashboard-container">
        <h1>Dashboard</h1>
        <div class="today-data">
            <div class="card">
                <h2>Today's Orders</h2>
                <p><?php echo htmlspecialchars($totalOrders); ?></p>
            </div>
            <div class="card">
                <h2>Today's Payments</h2>
                <p>₱<?php echo number_format($totalPayments, 2); ?></p>
            </div>
            <div class="card">
                <h2>Today's Customers</h2>
                <p><?php echo htmlspecialchars($totalCustomers); ?></p>
            </div>
            <div class="card">
                <h2>Today's Proceed</h2>
                <p><?php echo htmlspecialchars($totalProceed); ?></p>
            </div>
        </div>
    </div>
</body>
</html>
