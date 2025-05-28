<?php
require_once 'config.php';

// Fetch all users with their usertype names
$query = "SELECT login.id, login.username, login.email, usertype.name AS usertype_name 
          FROM login 
          JOIN usertype ON login.usertype_id = usertype.id";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>User Accounts</title>
    <link rel="stylesheet" href="css/dashboard.css" />
    <link rel="stylesheet" href="css/account-create.css" />
    <link rel="icon" href="images/logo.jpg?=2" />
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
                <a href="dashboard.html" class="sidebar-link">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="active">
                <a href="profile-content.php" class="sidebar-link">
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
                <a href="javascript:history.back()" class="sidebar-link">
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
                <h2>User Accounts</h2>
            </div>
            <div class="user--info">
                
                <img src="images/logo.jpg" alt="Logo" />
            </div>
        </div>
        <div class="card--container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>User Type</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && mysqli_num_rows($result) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['id']); ?></td>
                                <td><?php echo htmlspecialchars($row['username']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td><?php echo htmlspecialchars($row['usertype_name']); ?></td>
                                <td class="actions">
                                    <a href="account-edit.php?id=<?php echo $row['id']; ?>">Edit</a> | 
                                    <a href="account-delete.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this account?');">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="no-accounts">No accounts found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
<p><a href="account-create.php" class="btn-submit" style="display: inline-block; padding: 10px 20px; background-color: #F279D2; color: white; border-radius: 4px; text-decoration: none; font-weight: 600; margin-top: 20px;">Create New Account</a></p>
        </div>
    </div>
</body>
</html>
