<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Order Here</title>
    <link rel="icon" href="images/logo.jpg?=2" />
    <link rel="stylesheet" href="css/style.css" />
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

<div class="container">
    <header>
        <img src="images/logo3.png" />
        <h1>LIST PRODUCT</h1>
        <div class="iconCart">
            <img src="icon.png" />
            <div class="totalQuantity">0</div>

            <div class="userIcon" style="position: relative; display: inline-block;">
                <img src="images/icon.png" alt="User" id="userIcon" style="cursor: pointer; margin-left: 30px;" />
                <ul
                    class="dropdown-menu"
                    id="userDropdown"
                    style="display: none; position: absolute; right: 0; background: white; list-style: none; padding: 10px; margin: 0; border: 1px solid #ccc; border-radius: 5px; min-width: 120px; z-index: 1000;"
                >
                    <li>
                        <a href="#" style="display: block; padding: 5px 10px; color: black; text-decoration: none;"><?php echo htmlspecialchars($userRole); ?></a>
                    </li>
                    <?php if ($isAdmin): ?>
                    <li>
                        <a href="dashboard.html" style="display: block; padding: 5px 10px; color: black; text-decoration: none;">Dashboard</a>
                    </li>
                    <?php else: ?>
                    <li>
                        <a href="member-history.php" style="display: block; padding: 5px 10px; color: black; text-decoration: none;">Order History</a>
                    </li>
                    <?php endif; ?>
                    <li>
                        <a href="logout.php" style="display: block; padding: 5px 10px; color: black; text-decoration: none;">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <?php
    $products = [];
    $productFile = 'product.json';
    if (file_exists($productFile)) {
        $jsonData = file_get_contents($productFile);
        $products = json_decode($jsonData, true);
    }
    ?>
    <div class="listProduct">
        <?php foreach ($products as $product): ?>
        <div class="item">
            <img src="images/<?php echo htmlspecialchars(basename($product['image'])); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" />
            <h2><?php echo htmlspecialchars($product['name']); ?></h2>
            <div class="price">₱<?php echo number_format($product['price'], 2); ?></div>
            <button>Add To Cart</button>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<div class="cart">
    <h2>CART</h2>

    <div class="listCart">
        <div class="item">
            <img src="images/fries1.jpg" />
            <div class="content">
                <div class="name">CoPilot / Black / Automatic</div>
                <div class="price">₱550 / 1 product</div>
            </div>
            <div class="quantity">
                <button>-</button>
                <span class="value">3</span>
                <button>+</button>
            </div>
        </div>
    </div>

    <div class="buttons">
        <div class="close">CLOSE</div>
        <div class="checkout">
            <a href="checkout.php">CHECKOUT</a>
        </div>
    </div>
</div>

<script src="ajax/app.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const userIcon = document.getElementById('userIcon');
        const userDropdown = document.getElementById('userDropdown');

        if (userIcon && userDropdown) {
            userIcon.addEventListener('click', function (event) {
                event.stopPropagation();
                if (userDropdown.style.display === 'block') {
                    userDropdown.style.display = 'none';
                } else {
                    userDropdown.style.display = 'block';
                }
            });

            document.addEventListener('click', function () {
                userDropdown.style.display = 'none';
            });
        }
    });
</script>
</body>
</html>
