<?php
require_once 'config.php';

session_start();
if (!isset($_SESSION['usertype']) || $_SESSION['usertype'] != 1) {
    header("Location: home.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: profile-content.php");
    exit;
}

$id = intval($_GET['id']);

// Prevent admin from deleting their own account
if ($id == $_SESSION['id']) {
    header("Location: profile-content.php?error=cannot_delete_self");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
        $stmt = $conn->prepare("DELETE FROM login WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $stmt->close();
            header("Location: profile-content.php?success=account_deleted");
            exit;
        } else {
            $stmt->close();
            $error = "Failed to delete account.";
        }
    } else {
        header("Location: profile-content.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Delete Account</title>
    <link rel="stylesheet" href="css/dashboard.css" />
    <link rel="stylesheet" href="css/account-create.css" />
    <link rel="icon" href="images/logo.jpg?=2" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
</head>
<body>
    <div class="sidebar">
        <div class="logo"></div>
        <ul class="menu">
            <li>
                <a href="dashboard.html" class="sidebar-link">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="profile-content.php" class="sidebar-link active">
                    <i class="fas fa-user"></i>
                    <span>Profile</span>
                </a>
            </li>
            <li>
                <a href="orderlist.php" class="sidebar-link">
                    <i class="fas fa-list"></i>
                    <span>Order History</span>
                </a>
            </li>
            <li>
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
        </ul>
    </div>
    <div class="main-content">
        <div class="header--wrapper">
            <div class="header--title">
                <span>Primary</span>
                <h2>Delete Account</h2>
            </div>
            <div class="user--info">
                <div class="search--box">
                    <i class="fa-solid fa-search"></i>
                    <input type="text" placeholder="Search" />
                </div>
                <img src="images/logo.jpg" alt="" />
            </div>
        </div>
        <div class="card--container">
            <?php if (isset($error)): ?>
                <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <p>Are you sure you want to delete this account?</p>
            <form method="post" action="">
                <button type="submit" name="confirm" value="yes" class="btn-submit" style="background-color: #d9534f;
    color: white;
    font-weight: 700;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    padding: 12px 20px;
    transition: background-color 0.3s ease;">Yes</button>
                <a href="profile-content.php" class="btn-cancel">Cancel</a>
            </form>
        </div>
    </div>
</body>
</html>
