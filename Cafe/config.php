<?php
error_reporting(1);
session_start();

$conn = mysqli_connect('localhost','root','','cafe');

if (isset($_POST['lbtn'])) {
		$usn = $conn ->real_escape_string($_POST['usn']);
		$pw = $conn -> real_escape_string($_POST['pw']);
		$stmt = "SELECT * FROM login WHERE username = '$usn'";
		$check = mysqli_query($conn,$stmt);
		$row = mysqli_num_rows($check);
		if ($row>0) {
			$datafetch = mysqli_fetch_assoc($check);
			$pw2 = $datafetch['password'];
			$id = $datafetch['id'];
			if ((substr($pw2, 0, 4) === '$2y$' && password_verify($pw, $pw2)) || $pw === $pw2) {
				session_start();
				$_SESSION['id'] = $id;
				$_SESSION['usertype'] = $datafetch['usertype_id'];  // store usertype in session
				echo "1";
			} else {
				echo "credential not matched";
			}
		} else {
			echo "user is not exist";
		}
	}
	if (isset($_POST['checkBtn'])) {
		if (!isset($_SESSION['id'])) {
			echo "User not logged in.";
			exit;
		}
	
		$info = $conn->real_escape_string($_POST['inform']);
		$quantity = $conn->real_escape_string($_POST['oquantity']);
		$returnPrice = $conn->real_escape_string($_POST['rPrice']);
		$name = $conn->real_escape_string($_POST['pname']);
		$phone = $conn->real_escape_string($_POST['pnumber']);
		$address = $conn->real_escape_string($_POST['paddress']);
		$country = $conn->real_escape_string($_POST['ocountry']);
		$city = $conn->real_escape_string($_POST['ocity']);
		$login_id = $_SESSION['id'];
	
$stmt = $conn->prepare("INSERT INTO orders (info, quantity, returnPrice, oname, phone, address, country, city, login_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sdisssssi", $info, $quantity, $returnPrice, $name, $phone, $address, $country, $city, $login_id);
	
		if ($stmt->execute()) {
			echo "Order placed successfully.";
		} else {
			echo "Failed to place order: " . $stmt->error;
		}
		$stmt->close();
	}

	if (isset($_POST['regBtn'])) {
		$username = $conn->real_escape_string($_POST['username']);
		$email = $conn->real_escape_string($_POST['email']);
		$password = $_POST['password']; // do not escape password before hashing
		$utype = intval($_POST['utype']); // ensure integer
	
		// Check if username already exists
		$stmt = $conn->prepare("SELECT id FROM login WHERE username = ?");
		$stmt->bind_param("s", $username);
		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows > 0) {
			echo "Username already exists";
			$stmt->close();
			exit;
		}
		$stmt->close();
	
		// Check if email already exists
		$stmt = $conn->prepare("SELECT id FROM login WHERE email = ?");
		$stmt->bind_param("s", $email);
		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows > 0) {
			echo "Email has already been used";
			$stmt->close();
			exit;
		}
		$stmt->close();
	
		// Hash the password
	
		// Insert new user
		$stmt = $conn->prepare("INSERT INTO login (username, email, password, utype, usertype_id) VALUES (?, ?, ?, ?, ?)");
		// Assuming usertype_id is a single integer, e.g., 2 or 3
		$stmt->bind_param("sssii", $username, $email, $password, $utype, $usertype_id);
		$usertype_id = 2; // or set accordingly
		if ($stmt->execute()) {
			$_SESSION['login_id'] = $stmt->insert_id; // store user id
			echo "success";
		} else {
			echo "Error: " . $stmt->error;
		}
		$stmt->close();
	}
	// Check if user is logged in
	function isLoggedIn() {
		return isset($_SESSION['id']);
	}
	
	// Check if user is admin
	function isAdmin() {
		return isset($_SESSION['usertype']) && $_SESSION['usertype'] == 1;
	}
	
	// Redirect if not logged in
	function requireLogin() {
		if(!isLoggedIn()) {
			header("Location: index.php");
			exit;
		}
	}
	
	// Redirect if not admin
	function requireAdmin() {
		requireLogin();
		if(!isAdmin()) {
			header("Location: home.php");
			exit;
		}
	}

	// Clear order history for logged in user
	if (isset($_POST['clearHistory'])) {
		if (!isset($_SESSION['id'])) {
			echo "User not logged in.";
			exit;
		}
		$login_id = $_SESSION['id'];
		$stmt = $conn->prepare("DELETE FROM orders WHERE login_id = ?");
		$stmt->bind_param("i", $login_id);
		if ($stmt->execute()) {
			echo "Order history cleared.";
		} else {
			echo "Failed to clear order history: " . $stmt->error;
		}
		$stmt->close();
		exit;
	}
	?>