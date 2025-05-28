<?php
require_once 'config.php';
requireLogin();

$userId = $_SESSION['id'];
$query = "SELECT * FROM orders WHERE login_id = ? ORDER BY id DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Member Order History</title>
    <link rel="icon" href="images/logo.jpg?=2" />
    <link rel="stylesheet" href="css/member-history.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />

</head>
<?php
require_once 'config.php';
requireLogin();
?>

<body>
<?php
session_start();
$userRole = 'Member'; // default role
$isAdmin = false;
if (isset($_SESSION['usertype'])) {
    if ($_SESSION['usertype'] == 1) {
        $userRole = 'Admin';
        $isAdmin = true;
    }
}
?>
    <div class="container" style="display: flex; align-items: center; gap: 10px; margin-bottom: 15px;">
        <img src="images/logo3.png" alt="Logo" style="height: 60px; width: auto;" />
        <h1 style="margin: 0;">Order History</h1>
        <div class="userIcon" style="position: relative; display: inline-block; margin-left: auto;">
            <img src="images/icon.png" alt="User" id="userIcon" style="cursor: pointer; margin-left: 30px; height: 40px; width: auto;">
            <ul class="dropdown-menu" id="userDropdown" style="display: none; position: absolute; right: 0; background: white; list-style: none; padding: 10px; margin: 0; border: 1px solid #ccc; border-radius: 5px; min-width: 120px; z-index: 1000;">
                <li><a href="#" style="display: block; padding: 5px 10px; color: black; text-decoration: none;"><?php echo htmlspecialchars($userRole); ?></a></li>
                <?php if ($isAdmin): ?>
                    <li><a href="dashboard.html" style="display: block; padding: 5px 10px; color: black; text-decoration: none;">Dashboard</a></li>
                <?php endif; ?>
                <li><a href="logout.php" style="display: block; padding: 5px 10px; color: black; text-decoration: none;">Logout</a></li>
            </ul>
        </div>
    </div>
    <div class="container">
        <div style="display: flex; justify-content: flex-end; margin-bottom: 15px;">
            <div class="search--box" >
            
                <input type="text" placeholder="Search orders..." id="orderSearch"/>
                <i class="fa-solid fa-search"></i>
            </div>
        </div>
        <div style="text-align: left; margin-bottom: 15px;">
                <button onclick="window.location.href='home.php'" style="padding: 8px 12px; background-color: #F279D2; color: white; border: none; border-radius: 5px; cursor: pointer;">Make Another Order</button>
            </div>
        <?php if ($result->num_rows > 0): ?>
            <?php $totalAll = 0; ?>
            <ul class="orders-list">
                <?php while ($order = $result->fetch_assoc()): 
                    $infoStr = stripslashes($order['info']);
                    $items = json_decode($infoStr, true);
                    if (!$items) {
                        $items = [];
                    }
                    $totalAll += $order['returnPrice'];
                ?>
                    <li class="order-item">
                        <div class="order-id">Order #<?php echo htmlspecialchars($order['id']); ?></div>
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
                    </li>
                <?php endwhile; ?>
            </ul>
            <div class="total-all" style="text-align: right; margin-top: 20px;">
                <strong>Total: ₱<?php echo number_format($totalAll, 2); ?></strong>
            </div>
            
        <?php else: ?>
            <p>No orders found.</p>
        <?php endif; ?>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const userIcon = document.getElementById('userIcon');
            const userDropdown = document.getElementById('userDropdown');

            if(userIcon && userDropdown){
                userIcon.addEventListener('click', function(event){
                    event.stopPropagation();
                    if(userDropdown.style.display === 'block'){
                        userDropdown.style.display = 'none';
                    } else {
                        userDropdown.style.display = 'block';
                    }
                });

                document.addEventListener('click', function(){
                    userDropdown.style.display = 'none';
                });
            }
        });

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
                    location.reload();
                })
                .catch(error => {
                    alert('Failed to clear history.');
                    console.error(error);
                });
            }
        });

        document.addEventListener('DOMContentLoaded', () => {
            const orderSearch = document.getElementById('orderSearch');
            if (orderSearch) {
                orderSearch.addEventListener('input', () => {
                    const filter = orderSearch.value.toLowerCase();
                    document.querySelectorAll('.order-item').forEach(card => {
                        const text = card.textContent.toLowerCase();
                        if (text.includes(filter)) {
                            card.style.display = '';
                        } else {
                            card.style.display = 'none';
                        }
                    });
                });
            }
        });
    </script>
</body>
</html>
<?php
$stmt->close();
?>
