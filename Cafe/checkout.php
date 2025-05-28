<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Checkout</title>
    <link rel="stylesheet" href="css/style.css" />
    <link rel="icon" href="images/logo.jpg?=2" />
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
if (isset($_SESSION['usertype']) && $_SESSION['usertype'] == 1) {
    $userRole = 'Admin';
    $isAdmin = true;
}
?>
<header style="position: relative; width: 100%; height: 60px; background-color: white; display: flex; justify-content: flex-end; align-items: center; padding: 0 20px; box-sizing: border-box;">
    <img src="images/logo3.png" />
    <div class="returnCart" style="margin-right: auto;">
        <a href="home.php" style="display: block; padding: 10px 15px; color: black; text-decoration: none; font-weight: bold;">Order Again</a>
    </div>
    <div class="userIcon dropdown" style="position: relative; display: inline-block;">
        <img src="images/icon.png" alt="User" id="userIcon" style="cursor: pointer;" />
        <ul class="dropdown-menu" id="userDropdown" style="display: none; position: absolute; right: 0; background: white; list-style: none; padding: 10px; margin: 0; border: 1px solid #ccc; border-radius: 5px; min-width: 120px; z-index: 1000;">
            <li><a href="#" style="display: block; padding: 5px 10px; color: black; text-decoration: none;"><?php echo htmlspecialchars($userRole); ?></a></li>
            <?php if ($isAdmin): ?>
            <li><a href="dashboard.html" style="display: block; padding: 5px 10px; color: black; text-decoration: none;">Dashboard</a></li>
            <?php else: ?>
                <li><a href="member-history.php" style="display: block; padding: 5px 10px; color: black; text-decoration: none;">Order History</a></li>
            <?php endif; ?>
            <li><a href="logout.php" style="display: block; padding: 5px 10px; color: black; text-decoration: none;">Logout</a></li>
        </ul>
    </div>
</header>
<div class="container">
    <div class="checkoutLayout">
        <div class="returnCart">
            <h1>List Product in Cart</h1>
            <div class="list">
                <div class="item">
                    <img src="images/burger.jpg" />
                    <div class="info" id="info">
                        <div class="name">PRODUCT 1</div>
                        <div class="price">$22/1 product</div>
                    </div>
                    <div class="quantity" id="quantity">5</div>
                    <div class="returnPrice" id="returnPrice">$433.3</div>
                </div>
            </div>
        </div>
        <div class="right">
            <h1>Checkout</h1>
            <form id="checkoutForm">
                <div class="form">
                    <div class="group">
                        <label for="name">Full Name</label>
                        <input type="text" name="name" id="oname" required />
                    </div>
                    <div class="group">
                        <label for="phone">Phone Number</label>
                        <input type="text" name="phone" id="phone" required />
                    </div>
                    <div class="group">
                        <label for="address">Address</label>
                        <input type="text" name="address" id="address" required />
                    </div>
                    <div class="group">
                        <label for="country">Country</label>
                        <input type="text" name="country" id="country" required />
                    </div>
                    <div class="group">
                        <label for="city">City</label>
                        <input type="text" name="city" id="city" required />
                    </div>
                </div>
                <div class="return">
                    <div class="row">
                        <div>Total Quantity</div>
                        <div class="totalQuantity">70</div>
                    </div>
                    <div class="row">
                        <div>Total Price</div>
                        <div class="totalPrice">$900</div>
                    </div>
                </div>
                <button type="button" id="checkoutbutton" class="buttonCheckout" onclick="return checkBtn(event)">CHECKOUT</button>
            </form>
            <script>
                // Pass user role from PHP session to JavaScript as global variable
                window.userRole = "<?php echo isset($_SESSION['usertype']) && $_SESSION['usertype'] == 1 ? 'Admin' : 'Member'; ?>";
            </script>
        </div>
    </div>
</div>
<script src="ajax/checkout.js"></script>
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
