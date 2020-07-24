<?php
	session_start();
	require "config/config.php";

	$isUpdated = false;

	// First check that required fields have been filled out
	if ( !isset($_GET['id']) || empty($_GET['id']) ||
		!isset($_GET['list_id']) || empty($_GET['list_id']) ||
		!isset($_GET['checked']) || empty($_GET['checked']) ) {

		header("Location: lists.php?list_id=0");
	}
	else {

		// 1. Connect to DB
		$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		if ( $mysqli->connect_errno ) {
			echo $mysqli->connect_error;
			exit();
		}

		$watched = "";

		if($_GET['checked'] == "true"){
			$watched = "yes";
		}
		else if ($_GET['checked'] == "false"){
			$watched = "no";
		}

		// --- Prepared statement 
		$statement = $mysqli->prepare("UPDATE list_items 
			SET watched = ?
			WHERE id = ? AND list_id = ?");
		// bind the ? placeholder with the actual user input
		// first param: is the data type for each user input. one string.
		// the rest of the param is the variable that holds the user input information, in order of the ? placeholders
		$statement->bind_param("sii", $watched, $_GET["id"], $_GET["list_id"]);

		// execute the statement, aka query the DB
		$executed = $statement->execute();

		// check for errors
		if(!$executed) {
			echo $mysqli->error;
		}

		// if updated is succesful, $mysqli->affected_rows will return the number of records taht were updated. in our case, we should receive 1 because only ONE record was updated
		if($statement->affected_rows == 1) {
			$isUpdated = true;
			header("Location: lists.php?list_id=" . $_GET["list_id"]);
		}

		$statement->close();

		$mysqli->close();

	}
?>