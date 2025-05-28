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
$error = '';
$success = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $usertype_id = intval($_POST['usertype_id']);

    // Check if username or email already exists for other users
    $stmt = $conn->prepare("SELECT id FROM login WHERE (username = ? OR email = ?) AND id != ?");
    $stmt->bind_param("ssi", $username, $email, $id);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $error = "Username or email already exists for another user.";
    } else {
        $stmt->close();
        $stmt = $conn->prepare("UPDATE login SET username = ?, email = ?, usertype_id = ? WHERE id = ?");
        $stmt->bind_param("ssii", $username, $email, $usertype_id, $id);
        if ($stmt->execute()) {
            $success = "Account updated successfully.";
        } else {
            $error = "Error updating account: " . $stmt->error;
        }
    }
    $stmt->close();
}

// Fetch user data
$stmt = $conn->prepare("SELECT username, email, usertype_id FROM login WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($username, $email, $usertype_id);
if (!$stmt->fetch()) {
    $stmt->close();
    header("Location: profile-content.php");
    exit;
}
$stmt->close();

// Fetch usertypes for dropdown
$usertypes = [];
$result = $conn->query("SELECT id, name FROM usertype");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $usertypes[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Edit Account</title>
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
                <h2>Edit Account</h2>
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
            <?php if ($error): ?>
                <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="success-message"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            <form method="post" action="" class="account-create-form">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required value="<?php echo htmlspecialchars($username); ?>" />
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($email); ?>" />
                </div>
                <div class="form-group">
                    <label for="usertype_id">User Type</label>
                    <select id="usertype_id" name="usertype_id" required>
                        <?php foreach ($usertypes as $type): ?>
                            <option value="<?php echo $type['id']; ?>" <?php if ($type['id'] == $usertype_id) echo 'selected'; ?>>
                                <?php echo htmlspecialchars($type['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn-submit">Update Account</button>
                <a href="profile-content.php" class="btn-cancel">Cancel</a>
            </form>
        </div>
    </div>
</body>
</html>
