<?php  

function insertStore($pdo, $store_name, $locations, $contact_name) {

	$sql = "INSERT INTO stores (store_name, locations, contact_name) 
			VALUES(?,?,?)";

	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$store_name, $locations, $contact_name]);

	if ($executeQuery) {
		return true;
	}
}

function updateStore($pdo, $store_name, $locations, $contact_name, $store_id) {

	$sql = "UPDATE stores
				SET store_name = ?,
					locations = ?,
					contact_name = ?
				WHERE store_id = ?
			";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$store_name, $locations, $contact_name, $store_id]);
	
	if ($executeQuery) {
		return true;
	}

}

function deleteStore($pdo, $store_id) {
	$sql = "DELETE FROM stores WHERE store_id = ?";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$store_id]);

	if ($executeQuery) {
		return true;
	}
}

function getAllStore($pdo) {
	$sql = "SELECT * FROM stores";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute();

	if ($executeQuery) {
		return $stmt->fetchAll();
	}
}


function getAllCustomersByStoreID($pdo, $store_id) {
    $sql = "SELECT * FROM customers WHERE store_id = ?";
    $stmt = $pdo->prepare($sql);
    $executeQuery = $stmt->execute([$store_id]);

    if ($executeQuery) {
        return $stmt->fetchAll();
    }
    return []; 
}

function getStoreInfoByID($pdo, $store_id) {
    $sql = "SELECT * FROM stores WHERE store_id = ?";
    $stmt = $pdo->prepare($sql);
    $executeQuery = $stmt->execute([$store_id]);

    if ($executeQuery) {
        return $stmt->fetch();
    }

}

function getCustomersByStore($pdo, $store_id) {
    $stmt = $pdo->prepare("SELECT * FROM customers WHERE store_id = :store_id");
    $stmt->bindParam(':store_id', $store_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function insertCustomer($pdo, $customer_firstname, $customer_lastname, $email, $phone_number, $store_id) {
	$sql = "INSERT INTO customers (customer_firstname, customer_lastname, email, phone_number, store_id) 
			VALUES (?,?,?,?,?)";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$customer_firstname, $customer_lastname, $email, $phone_number, $store_id]);

	if ($executeQuery) {
		return true;
	}
}

function updateCustomer($pdo, $customer_firstname, $customer_lastname, $email, $phone_number, $store_id, $customer_id) {
	$sql = "UPDATE customers
			SET customer_firstname = ?,
				customer_lastname = ?,
				email = ?,
				phone_number = ?,
				store_id = ?
			WHERE customer_id = ?
			";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$customer_firstname, $customer_lastname, $email, $phone_number, $store_id, $customer_id]);

	if ($executeQuery) {
		return true;
	}
}

function deleteCustomer($pdo, $customer_id) {
    $sql = "DELETE FROM customers WHERE customer_id = ?";
    $stmt = $pdo->prepare($sql);
    $executeQuery = $stmt->execute([$customer_id]);

    if ($executeQuery) {
        return true;
    }
    return false;
}


function getAllCustomers($pdo) {
	$sql = "SELECT * FROM customers";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute();

	if ($executeQuery) {
		return $stmt->fetchAll();
	}
}

function getCustomerByID($pdo, $customer_id) {
	$sql = "SELECT * FROM customers WHERE customer_id = ?";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$customer_id]);

	if ($executeQuery) {
		return $stmt->fetch();
	}
}


// New User Management Code
require_once 'dbConfig.php';

function insertNewUser($pdo, $username, $password) {

	$checkUserSql = "SELECT * FROM user_passwords WHERE username = ?";
	$checkUserSqlStmt = $pdo->prepare($checkUserSql);
	$checkUserSqlStmt->execute([$username]);

	if ($checkUserSqlStmt->rowCount() == 0) {

		$sql = "INSERT INTO user_passwords (username,password) VALUES(?,?)";
		$stmt = $pdo->prepare($sql);
		$executeQuery = $stmt->execute([$username, $password]);

		if ($executeQuery) {
			$_SESSION['message'] = "User successfully inserted";
			return true;
		}

		else {
			$_SESSION['message'] = "An error occurred from the query";
		}

	}
	else {
		$_SESSION['message'] = "User already exists";
	}

	
}

function loginUser($pdo, $username, $password) {
	$sql = "SELECT * FROM user_passwords WHERE username=?";
	$stmt = $pdo->prepare($sql);
	$stmt->execute([$username]); 

	if ($stmt->rowCount() == 1) {
		$userInfoRow = $stmt->fetch();
		$usernameFromDB = $userInfoRow['username']; 
		$passwordFromDB = $userInfoRow['password'];

		if ($password == $passwordFromDB) {
			$_SESSION['username'] = $usernameFromDB;
			$_SESSION['message'] = "Login successful!";
			return true;
		}

		else {
			$_SESSION['message'] = "Password is invalid, but user exists";
		}
	}

	
	if ($stmt->rowCount() == 0) {
		$_SESSION['message'] = "Username doesn't exist from the database. You may consider registration first";
	}

}

function getAllUsers($pdo) {
	$sql = "SELECT * FROM user_passwords";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute();

	if ($executeQuery) {
		return $stmt->fetchAll();
	}

}

function getUserByID($pdo, $user_id) {
	$sql = "SELECT * FROM user_passwords WHERE user_id = ?";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$user_id]);
	if ($executeQuery) {
		return $stmt->fetch();
	}
}

?>
