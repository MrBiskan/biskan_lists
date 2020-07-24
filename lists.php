<?php
	session_start();
	require "config/config.php";

	if(!isset($_SESSION["email"]) || empty($_SESSION["email"])){
		echo "ERROR";
		exit();
	}
	else{
		// Connect to the database by creating an instance of the MySQLi class
		$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		// Check for DB connection errors
		// connect_errno returns an integer - 0 if no erros, 1 or more if there is an error
		if( $mysqli->connect_errno ) {
			echo $mysqli->connect_error;
			exit();
		}

		$list_id = "";

		if(!isset($_GET["list_id"]) || empty($_GET["list_id"])){
			$list_id = 0;
		}
		else{
			$list_id = $_GET["list_id"];
		}

		//Get first list id
		$sql = "SELECT * FROM lists 
				JOIN users 
				ON lists.email = users.email
				WHERE users.email = '" . $_SESSION["email"] . "';";

		$check_results = $mysqli->query($sql);
		if(!$check_results) {
			echo $mysqli->error;
			exit();
		}

		if($row = $check_results->fetch_assoc()){
			if($list_id == 0){
				$list_id = $row["id"];
			}
		}

		$email = $_SESSION["email"];

		$id = "";
		$title = "";
		$watched = "";
		$synopsis = "";
		$poster_path = "";
		$backdrop_path = "";
		$trailer_path = "";
		$rating = "";
		$release_date = "";
		

		$sql = "SELECT list_items.id, list_items.title, list_items.watched, list_items.synopsis, list_items.poster_path, list_items.backdrop_path, list_items.trailer_path, list_items.rating, list_items.release_date  FROM list_items 
				JOIN lists
				ON list_items.list_id = lists.id
				JOIN users 
				ON lists.email = users.email
				WHERE users.email = '" . $email . "' AND lists.id = " . $list_id . ";";

		$search_results = $mysqli->query($sql);
		if(!$search_results) {
			echo $mysqli->error;
			exit();
		}		

		$sql = "SELECT * FROM lists 
				JOIN users 
				ON lists.email = users.email
				WHERE users.email = '" . $email . "';";

		$list_results = $mysqli->query($sql);
		if(!$list_results) {
			echo $mysqli->error;
			exit();
		}
	}
	
?>

<!DOCTYPE html>
<html>
<head>
	<title>Biskan Lists | My Lists</title>
	<link href="https://fonts.googleapis.com/css2?family=Permanent+Marker&family=Questrial&display=swap" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/shared_styles.css">
	<link rel="stylesheet" type="text/css" href="css/lists.css">
	<link rel="stylesheet" type="text/css" href="css/responsive.css">
	<link rel="icon" href="img/icon.jpg">

	<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
</head>
<body>
	<div id="navbar">
		<div id="logo">Biskan Lists</div>
		<div id="menu-item-bar">
			<a href="index.php" class="menu-item">Home</a>
			<a href="#" class="menu-item current-page">Lists</a>
			<a href="logout.php" class="menu-item">Logout</a>
		</div>
	</div>

	<div id="content">
		<div id="search-container">
			<form action="search_results.php" method="GET"  id="search-form">
				<input type="text" name="search-term" id="search-bar" placeholder="Search a movie name!">
				<button type="submit" id="search-submit-button">Search</button>
			</form>
		</div>

		<div id="user-header">
			<h1>
				Welcome <?php echo $_SESSION["username"]; ?> to your Lists Page
			</h1>
			<span>Check out your lists</span>

			<span id="select-a-list">Select a list</span>

			<div id="dropdown-row">
				<select id="dropdown">
					<?php while($row1 = $list_results->fetch_assoc()): ?>
						<option value="<?php echo $row1['id'] ?>" <?php if(isset($_GET["list_id"]) && $_GET["list_id"] != 0 && $row1['id'] == $_GET["list_id"]){echo "selected";}?> >
							<?php echo $row1["name"] ?>
						</option>
					<?php endwhile; ?>
				</select>
				<form id="dummy" method="POST"></form>
				<button id="create-list-button" onclick="createList()">Create a List</button>
			</div>
		</div>

		<div id="results">

			<?php while($row = $search_results->fetch_assoc()): ?>

				<div class="list-item" id="<?php echo $row['id']; ?>">
					<div class="rc1">
						<?php $total_image_path = "https://image.tmdb.org/t/p/w300" . $row['poster_path']; ?>
						<img class="poster-image" src="<?php if($row['poster_path'] != "" && $row['poster_path'] != "null"){
							echo $total_image_path;
						}else{
							echo "img/no-poster.jpg";
						} ?>" alt="<?php if(!empty($row['poster_path'])){
							echo "Poster of " . $row['title'];
						}else{
							echo "No poster found for this image";
						} ?>" height="190px">
					</div>

					<div class="rc2">
						<span class="movie-title"><?php echo $row["title"]; ?></span>
						Rating: <span class="rating"><?php echo $row["rating"]; ?></span> | Release Date: <span class="release-date"><?php echo $row["release_date"]; ?></span>
						<p class="synopsis"><?php echo $row["synopsis"]; ?></p>
					</div>

					<div class="rc3">
						<form class="toggle-form" action="toggle.php?id=<?php echo $row['id']; ?>&list_id=<?php echo $list_id?>" method="POST" id="toggle-form-<?php echo $row['id']?>">
							<div class="watched-div">
								<label class="watched">
									Watched
								</label>
								<input type="checkbox" class="toggle-checkbox" id="toggle-<?php echo $row['id']?>"
								<?php if(!empty($row['watched']) && $row['watched']=="yes"){ echo "checked"; } ?> 
								data-toggle="toggle" data-style="ios" data-onstyle="success" onchange="alertChange('toggle-<?php echo $row['id']?>', <?php echo $row['id']?>)">
							</div>
						</form>
						
						<button class="delete-item" value="<?php echo $row['id']?>" onclick="deleteItem(<?php echo $row['id']?>);">Delete from List</button>
					</div>
				</div>

			<?php endwhile; ?>

			<div id="empty" style="display: <?php if($search_results->num_rows == 0){echo 'flex';}else{echo 'none';} ?>;">
				<img src="img/empty.png" alt="Empty Icon">
				<h1>Nothing to see here! Go add stuff!</h1>
			</div>

		</div>
		
	</div>

	<div id="footer">
		Created by Abasiakan Victor Udobong, ITP 303 2020
	</div>

	<div id="overlay"></div>

	<div id="create-list" class="pop-up">
		<div class="pop-up-content">
			<form action="add_list.php" method="GET" id="add-list-form">
				<label>Name your list!</label>
				<input type="text" name="list_name">
				<button type="submit">Create List</button>
			</form>
		</div>
	</div>

	<div id="remove-confirm" class="pop-up">
		<div class="pop-up-content">
			<span>Are you sure you want to remove this item?</span>
			<form action="remove_list_item.php" method="POST">
				<button>Remove Item</button>
			</form>
		</div>
	</div>

	<div id="success-message" class="pop-up">
		<div class="pop-up-content">
			<span>SUCCESS!</span>
		</div>
	</div>

	<script type="text/javascript">
		document.querySelector("#overlay").addEventListener("click", function(){
			document.querySelector("#overlay").style.display = "none";
			document.querySelector("#create-list").style.display = "none";
			document.querySelector("#remove-confirm").style.display = "none";
			document.querySelector("#success-message").style.display = "none";
		});

		function createList(){
			document.querySelector("#overlay").style.display = "block";
			document.querySelector("#create-list").style.display = "flex";
		}

		function deleteItem(i){
			document.querySelector("#overlay").style.display = "block";
			document.querySelector("#remove-confirm").style.display = "flex";
			document.querySelector("#remove-confirm form").action = 
			"remove_list_item.php?list_id=<?php echo $list_id?>&id=" + i;
		}

		document.querySelector("#dropdown").addEventListener("change", function(){
			var tmp_form = document.querySelector("#dummy");
			tmp_form.action = "lists.php?list_id=" + this.value;
			tmp_form.submit();
		});

		document.querySelector("#search-form").onsubmit = function(event) {
			event.preventDefault();

			if(document.querySelector("#search-bar").value.length == 0){

				//document.querySelector("#search-bar").className = "invalid-input";
				//document.querySelector("#error-message").style.display = "block";
			}
			else {
				this.submit();
			}
		}
	</script>

	<script
	  src="https://code.jquery.com/jquery-3.5.1.min.js"
	  integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
	  crossorigin="anonymous">
	  	$('.watched-div .toggle').change(function() {
		  alert($(this).prop('checked'))
		})
	</script>
	<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
	<script type="text/javascript" src="js/lists.js"></script>
</body>
</html>