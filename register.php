<?php
	session_start();

	require "config/config.php";

	$error = "";
	$isInserted = false;
	
	// Connect to the database by creating an instance of the MySQLi class
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	if ( $mysqli->errno ) {
		echo $mysqli->error;
		exit();
	}

	// escape any special characters like quotes
	$email = $mysqli->real_escape_string($_POST["email"]);
	$username = $mysqli->real_escape_string($_POST["username"]);
	$password = $mysqli->real_escape_string($_POST["password"]);
	$confirm_password = $mysqli->real_escape_string($_POST["confirm-password"]);

	//Perform overwriting checks
	$username_exists = false;
	$email_exists = false;

	//Check if email already exists
	$sql = "SELECT * FROM users WHERE users.email = '" . $_POST["email"] . "'";

	$user_results = $mysqli->query($sql);
	if(!$user_results) {
		echo $mysqli->error;
		exit();
	}

	if($row = $user_results->fetch_assoc()){
		$email_exists = true;
	}

	//Check if username already exists
	$sql = "SELECT * FROM users WHERE users.username = '" . $_POST["username"] . "'";

	$user_results = $mysqli->query($sql);
	if(!$user_results) {
		echo $mysqli->error;
		exit();
	}

	if($row = $user_results->fetch_assoc()){
		$username_exists = true;
	}

	if($username_exists && $email_exists){
		$error = "error_3";
		header("Location: login_register.php?error=" . $error); 
	}
	else if(!$username_exists && $email_exists){
		$error = "error_4";
		header("Location: login_register.php?error=" . $error); 
	}
	else if($username_exists && !$email_exists){
		$error = "error_5";
		header("Location: login_register.php?error=" . $error); 
	}
	else {
		$current_date = date('Y-m-d H:i:s');

		$sql = "INSERT INTO users (email, username, password, date_joined )

		VALUES ('" . $_POST["email"] . "', '" . $_POST["username"] . "', '"  . $_POST["password"] . "', '" 
		. $current_date . "');";

		$results = $mysqli->query($sql);
		if(!$results) {
			echo $mysqli->error;
			exit();
		}
		
		if($mysqli->affected_rows == 1) {
			$isInserted = true;
			$_SESSION["username"] = $_POST["username"];
			$_SESSION["email"] = $_POST["email"];
			header("Location: add_list.php?list_name=Favourites"); 
		}
		else{
			exit();
		}
	}
?>