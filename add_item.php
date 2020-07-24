<?php
	session_start();
	require "config/config.php";

	$error = "";
	$isInserted = false;
	if ( !isset($_GET['title']) || 
		empty($_GET['title']) ) {

		$error = "The title field is a required field.";
	}
	else {
		// Connect to the database by creating an instance of the MySQLi class
		$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		if ( $mysqli->errno ) {
			header("Location: lists.php"); 
			exit();
		}

		$id = $_GET["id"];
		$list_id = $_GET["list_id"];
		$title = $_GET["title"];
		$watched = $_GET["watched"];
		$starred = $_GET["starred"];
		$synopsis = $_GET["synopsis"];
		$poster_path = $_GET["poster_path"];
		$backdrop_path = $_GET["backdrop_path"];
		$trailer_path = $_GET["trailer_path"];
		$rating = $_GET["rating"];
		$release_date = $_GET["release_date"];

		//Check for list id
		$sql = "SELECT * FROM lists 
				JOIN users 
				ON lists.email = users.email
				WHERE users.email = '" . $_SESSION["email"] . "' 
				AND lists.id = " . $list_id . ";";

		$check_results = $mysqli->query($sql);
		if(!$check_results) {
			header("Location: lists.php"); 
			exit();
		}

		if($row = $check_results->fetch_assoc()){
			$list_id = $row["id"];
		}

		//Check if record exists
		$sql = "SELECT * FROM list_items 
				JOIN lists
				ON list_items.list_id = lists.id
				JOIN users 
				ON lists.email = users.email
				WHERE users.email = '" . $_SESSION["email"] . "' 
				AND lists.id = " . $list_id . "
				AND list_items.id = " . $id . ";";

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
		$title = $mysqli->real_escape_string($title);
		$synopsis = $mysqli->real_escape_string($synopsis);

		// THE SQL STATEMENT
		$sql = "INSERT INTO list_items (id, list_id, title, watched, synopsis, poster_path, backdrop_path, trailer_path, rating, release_date )

			VALUES(" . $id . "," . $list_id . ", '"  . $title
			. "', 'no', '" . $synopsis . "', '"  . $poster_path . "', '" 
			. $backdrop_path . "', '"  . $trailer_path . "', '"  . $rating . "', '"  . $release_date . "');";

		$results = $mysqli->query($sql);
		if(!$results) {
			echo $mysqli->error;
			echo "\n\n" . $sql; 
			exit();
		}
		
		if($mysqli->affected_rows == 1) {
			$isInserted = true;
			header("Location: lists.php?list_id=" . $list_id); 
		}
	}
?>