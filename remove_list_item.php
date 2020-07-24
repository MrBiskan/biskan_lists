<?php
session_start();
require "config/config.php";

$isDeleted = false;

// Make sure we get a valid track id and track name
if ( !isset($_GET['id']) || empty($_GET['id']) ) {
	$error = "Invalid id";
}
else {
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	if ( $mysqli->connect_errno ) {
		echo $mysqli->connect_error;
		exit();
	}

	$statement = $mysqli->prepare("DELETE FROM list_items WHERE list_items.id = ?");
	$statement->bind_param("i", $_GET["id"]);
	$executed = $statement->execute();

	if(!$executed) {
		echo $mysqli->error;
		exit();
	}

	if($statement->affected_rows == 1) {
		$isDeleted = true;
		header("Location: lists.php"); 
	}

	$statement->close();
	$mysqli->close();
}

?>