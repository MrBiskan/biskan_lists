<?php
	session_start();
	require "config/config.php";

	$error = "";
	$isInserted = false;
	if ( !isset($_GET['list_name']) || 
		empty($_GET['list_name']) ) {

		$error = "The list name field is a required field.";
	}
	else {
		// Connect to the database by creating an instance of the MySQLi class
		$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		if ( $mysqli->errno ) {
			header("Location: lists.php"); 
			exit();
		}

		$list_name = $_GET["list_name"];

		//Check if record exists
		$sql = "SELECT * FROM lists 
				JOIN users 
				ON lists.email = users.email
				WHERE users.email = '" . $_SESSION["email"] . "' 
				AND lists.name = '" . $list_name . "';";

		$check_results = $mysqli->query($sql);
		if(!$check_results) {
			header("Location: lists.php"); 
			exit();
		}

		if($check_results->num_rows != 0){
			header("Location: lists.php"); 
			exit();
		}

		// escape any special characters like quotes
		$list_name = $mysqli->real_escape_string($list_name);

		// THE SQL STATEMENT
		// Only added track name, media type, genre, and composer for simplicity sake
		$sql = "";

		if($list_name == "Favourites"){
			$sql = "INSERT INTO lists (id, email, name ) VALUES(0, '" . $_SESSION["email"] . "', '" . $list_name . "');";
		}
		else{
			$sql = "INSERT INTO lists (email, name ) VALUES('" . $_SESSION["email"] . "', '" . $list_name . "');";
		}
		

		$results = $mysqli->query($sql);
		if(!$results) {
			header("Location: lists.php"); 			
			exit();
		}
		
		if($mysqli->affected_rows == 1) {
			$isInserted = true;
		}

		header("Location: lists.php?list_id=0"); 
	}
?>