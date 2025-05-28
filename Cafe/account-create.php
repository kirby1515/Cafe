<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Create Account</title>
    <link rel="stylesheet" href="css/dashboard.css" />
    <link rel="stylesheet" href="css/account-create.css" />
    <link rel="icon" href="images/logo.jpg?=2" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
</head>
<body>
    <?php
require_once 'config.php';
requireLogin();
?>

    <div class="sidebar">
        <div class="logo"></div>
        <ul class="menu">
<?php
session_start();
$isAdmin = false;
if (isset($_SESSION['usertype']) && $_SESSION['usertype'] == 1) {
    $isAdmin = true;
}
?>
<?php if ($isAdmin): ?>
            <li>
                <a href="dashboard.html" class="sidebar-link">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="profile-content.php" class="sidebar-link">
                    <i class='fas fa-user'></i>
                    <span>Profile</span>
                </a>
            </li>
<?php endif; ?>
            <li>
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
            <li class="active">
                <a href="account-create.php" class="sidebar-link">
                    <i class="fa-solid fa-user-plus"></i>
                    <span>Add Account</span>
                </a>
            </li>
            <li class="logout">
                <a href="logout.php">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span>Logout</span>
                </a>
            </li>
            <li>
                <a href="home.php" class="sidebar-link">
                    <i class="fas fa-arrow-left"></i>
                    <span>Back</span>
                </a>
            </li>
        </ul>
    </div>
    <div class="main-content">
        <div class="header--wrapper">
            <div class="header--title">
                <span>Primary</span>
                <h2>Create Account</h2>
            </div>
            <div class="user--info">
                
                <img src="images/logo.jpg" alt="Logo" />
            </div>
        </div>
        <div class="card--container">
            <form class="account-create-form">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required placeholder="Enter username" />
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required placeholder="Enter email" />
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required placeholder="Enter password" />
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required placeholder="Confirm password" />
                </div>
                <div class="form-group">
                    <label for="accounttype">Account Type</label>
                    <select id="accounttype" name="accounttype" required>
                        <option value="2" selected>Member</option>
                        <option value="3">Staff</option>
                    </select>
                </div>
                <button id="registerbutton" type="button" class="btn-submit" value="register" onclick="return regBtn()">Create Account</button>
            </form>
        </div>
    </div>
    <script src="ajax/main.js"></script>
</body>
</html>
