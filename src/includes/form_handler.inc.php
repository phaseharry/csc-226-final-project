<?php
include_once "../dbconnect.php";
//sanitize function
function sanitize($str)
{
	$str = trim($str);
	$str = stripcslashes($str);
	$str = htmlspecialchars($str);

	return $str;
}

//form handler
$input = [];
if ($_POST && isset($_POST['submit'])) {
	if (empty($_POST['username'])) {
		$input['username'] = "Missing Username!";
	}
	if (empty($_POST['password'])) {
		$input['password'] = "Missing Password!";
	}
	$username = sanitize($_POST['username']);
	$password = sanitize($_POST['password']);

	$q = "SELECT first_name, email, cid FROM customer WHERE email = ?  AND password = ? LIMIT 1";
	$stmt = $conn->prepare($q);
	$stmt->bind_param("ss", $username, $password);
	$stmt->execute();
	$result = $stmt->get_result();
	$user;
	if ($result->num_rows == 1) {
		$user = $result->fetch_all(MYSQLI_ASSOC)[0];
	}

	if (isset($user)) {
		var_dump($user);
		//set cookie
		echo $user["email"];
		setcookie("first_name", $user["first_name"], time() + 3600);
		setcookie("email", $user["email"], time() + 3600);
		setcookie("customer_id", $user["cid"], time() + 3600);
		$conn->close();
		header("Location: index.php");
	} else {
		$input['username'] = "Incorrect Password or Username!";
		$input['password'] = "Incorrect Password or Username!";
	}
}
