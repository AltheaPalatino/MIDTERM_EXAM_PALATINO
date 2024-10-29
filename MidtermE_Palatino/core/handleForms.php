<?php 
session_start(); // Start session management at the top

require_once 'dbConfig.php'; 
require_once 'models.php';

// Insert a new store
if (isset($_POST['insertStoreBtn'])) {
	$query = insertStore($pdo, $_POST['store_name'], $_POST['locations'], $_POST['contact_name']);
	if ($query) {
		header("Location: ../index.php");
		exit();
	} else {
		echo "Insertion failed";
	}
}

// Edit an existing store
if (isset($_POST['editStoreBtn'])) {
	$query = updateStore($pdo, $_POST['store_name'], $_POST['locations'], $_POST['contact_name'], $_GET['store_id']);
	if ($query) {
		header("Location: ../index.php");
		exit();
	} else {
		echo "Edit failed";
	}
}

// Delete a store
if (isset($_POST['deleteStoreBtn'])) {
	$query = deleteStore($pdo, $_GET['store_id']);
	if ($query) {
		header("Location: ../index.php");
		exit();
	} else {
		echo "Deletion failed";
	}
}

// Insert a new customer
if (isset($_POST['insertCustomerBtn'])) {
	$query = insertCustomer($pdo, $_POST['customer_firstname'], $_POST['customer_lastname'], $_POST['email'], $_POST['phone_number'], $_GET['store_id']);
	if ($query) {
		header("Location: ../viewcustomers.php?store_id=" . $_GET['store_id']);
		exit();
	} else {
		echo "Insertion failed";
	}
}

// Edit an existing customer
if (isset($_POST['editCustomerBtn'])) {
	$query = updateCustomer($pdo, $_POST['customer_firstname'], $_POST['customer_lastname'], $_POST['email'], $_POST['phone_number'], $_GET['store_id'], $_GET['customer_id']); 
	if ($query) {
		header("Location: ../viewcustomers.php?store_id=" . $_GET['store_id']);
		exit();
	} else {
		echo "Update failed";
	}
}

// Delete a customer
if (isset($_POST['deleteCustomerBtn'])) {
	$query = deleteCustomer($pdo, $_GET['customer_id']);
	if ($query) {
		header("Location: ../viewcustomers.php?store_id=" . $_GET['store_id']);
		exit();
	} else {
		echo "Deletion failed";
	}
}

// User registration
if (isset($_POST['registerUserBtn'])) {
	$username = $_POST['username'];
	$password = sha1($_POST['password']);
	if (!empty($username) && !empty($password)) {
		$insertQuery = insertNewUser($pdo, $username, $password);
		if ($insertQuery) {
			header("Location: ../login.php");
			exit();
		} else {
			$_SESSION['message'] = "Registration failed. Please try again.";
			header("Location: ../register.php");
			exit();
		}
	} else {
		$_SESSION['message'] = "Please fill out all registration fields!";
		header("Location: ../register.php");
		exit();
	}
}

// User login
if (isset($_POST['loginUserBtn'])) {
	$username = $_POST['username'];
	$password = sha1($_POST['password']);

	if (!empty($username) && !empty($password)) {
		$loginQuery = loginUser($pdo, $username, $password);
		if ($loginQuery) {
			header("Location: ../index.php");
		} else {
			header("Location: ../login.php");
		}
	} else {
		$_SESSION['message'] = "Please make sure the input fields are not empty for the login!";
		header("Location: ../login.php");
	}
}

// User logout
if (isset($_GET['logoutAUser'])) {
	unset($_SESSION['username']);
	unset($_SESSION['user_id']);
	session_destroy();
	header("Location: ../login.php");
	exit();
}
?>
