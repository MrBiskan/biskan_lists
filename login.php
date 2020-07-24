<?php
	session_start();

	require "config/config.php";

	$error = "";

	// Connect to the database by creating an instance of the MySQLi class
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	if ( $mysqli->errno ) {
		echo $mysqli->error;
		exit();
	}

	// escape any special characters like quotes
	$email = $mysqli->real_escape_string($_POST["email"]);
	$password = $mysqli->real_escape_string($_POST["password"]);

	//Check if account exists
	$sql = "SELECT * FROM users WHERE users.email = '" . $_POST["email"] . "'";

	$user_results = $mysqli->query($sql);
	if(!$user_results) {
		echo $mysqli->error;
		exit();
	}

	if($row = $user_results->fetch_assoc()){
		//Check if password is correct
		if($row["password"] == $_POST["password"]) {
			//redirect if correct
			$_SESSION["username"] = $row["username"];
			$_SESSION["email"] = $row["email"];
			header("Location: index.php"); 
		}
		else {
			$error = "error_2";
			header("Location: login_register.php?error=" . $error); 
		}
	}
	else {
		$error = "error_1";
		header("Location: login_register.php?error=" . $error); 
	}
?>