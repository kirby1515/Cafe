<?php
require_once 'config.php';
requireLogin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Order History</title>
    <link rel="icon" href="images/logo.jpg?=2">
    <link rel="stylesheet" href="css/dashboard.css" />
    <link rel="stylesheet" href="css/orderlist.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
</head>
<?php
require_once 'config.php';
requireLogin();
?>

<body>
    <div class="sidebar">
        <div class="logo"></div>
        <ul class="menu">
            <li>
                <a href="dashboard.html">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="profile-content.php" class="sidebar-link">
                    <i class="fas fa-user"></i>
                    <span>Profile</span>
                </a>
            </li>
            <li class="active">
                <a href="orderlist.php" class="sidebar-link">
                    <i class="fas fa-list"></i>
                    <span>Order History</span>
                </a>
            </li>
            <li>
                <a href="product-content.php" class="sidebar-link" data-page="product-content.php">
                    <i class="fa-brands fa-product-hunt"></i>
                    <span>Products</span>
                </a>
            </li>
            <li>
                <a href="account-create.php" class="sidebar-link">
                    <i class="fa-solid fa-user-plus"></i>
                    <span>Add Account</span>
                </a>
            </li>
            <li>
                <a href="home.php" class="sidebar-link">
                    <i class="fas fa-arrow-left"></i>
                    <span>Back</span>
                </a>
            </li>
            <li class="logout">
                <a href="logout.php">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </div>
   <div class="main-content">
        <div class="header--wrapper">
            <div class="header--title">
                <span>Primary</span>
                <h2>Order History</h2>
            </div>
            <div class="user--info">
                <div class="search--box">
                    <i class="fa-solid fa-search"></i>
                <input type="text" placeholder="Search orders..." id="orderSearch" />
            </div>
            <img src="images/logo.jpg" alt="" />
        </div>
    </div>

    <div class="orders-container">
            <?php
            $user_condition = isAdmin() ? "" : "WHERE o.login_id = " . $_SESSION['user_id'];
            $query = "SELECT o.*, l.username FROM `orders` o 
                     JOIN login l ON o.login_id = l.id 
                     $user_condition
                     ORDER BY o.id DESC";
            $result = mysqli_query($conn, $query);
            
            if(mysqli_num_rows($result) > 0):
                $totalAll = 0;
                while($order = mysqli_fetch_assoc($result)):
                    $infoStr = stripslashes($order['info']);
                    $items = json_decode($infoStr, true);
                    if (!$items) {
                        $items = [];
                    }
                    $totalAll += $order['returnPrice'];
            ?>
                <div class="order-card">
                    <div class="order-header">
                        <h3>Order #<?php echo $order['id']; ?></h3>
                        <?php if(isAdmin()): ?>
                            <p>By: <?php echo htmlspecialchars($order['username']); ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="order-details">
                        <p>Name: <?php echo htmlspecialchars($order['oname']); ?></p>
                        <p>Phone: <?php echo htmlspecialchars($order['phone']); ?></p>
                        <p>Address: <?php echo htmlspecialchars($order['address']); ?>, <?php echo htmlspecialchars($order['city']); ?>, <?php echo htmlspecialchars($order['country']); ?></p>
                        <p>Quantity: <?php echo htmlspecialchars($order['quantity']); ?></p>
                        <p>Total Price: ₱<?php echo number_format($order['returnPrice'], 2); ?></p>
                        <p>Items:</p>
                        <?php if (!empty($items)): ?>
                            <ul>
                                <?php foreach ($items as $item): ?>
                                    <li><div class="name"><?php echo htmlspecialchars($item['name'] ?? $item); ?></div> - Quantity: <?php echo htmlspecialchars($item['quantity'] ?? ''); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p>No item details available.</p>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endwhile; ?>
            <div class="total-all" style="text-align: right; margin-top: 20px;">
                <strong>Total: ₱<?php echo number_format($totalAll, 2); ?></strong>
            </div>
            <div style="text-align: right; margin-top: 10px;">
                <button id="clearHistoryBtn" style="padding: 8px 12px; background-color: #f44336; color: white; border: none; border-radius: 5px; cursor: pointer; margin-right: 10px;">Clear History</button>
            </div>
            <?php
            else:
            ?>
                <p class="no-orders">No orders found.</p>
            <?php endif; ?>
        </div>
    </div>

    <script>
        document.getElementById('clearHistoryBtn').addEventListener('click', () => {
            if (confirm('Are you sure you want to clear your order history? This action cannot be undone.')) {
                fetch('config.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: 'clearHistory=1'
                })
                .then(response => response.text())
                .then(text => {
                    alert(text);
                    // Clear dashboard data cache in localStorage
                    localStorage.removeItem('dashboardData');
                    location.reload();
                })
                .catch(error => {
                    alert('Failed to clear history.');
                    console.error(error);
                });
            }
        });
    </script>

    <script>
        const orderSearch = document.getElementById('orderSearch');
        orderSearch.addEventListener('input', () => {
            const filter = orderSearch.value.toLowerCase();
            document.querySelectorAll('.order-card').forEach(card => {
                const text = card.textContent.toLowerCase();
                if (text.includes(filter)) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>
