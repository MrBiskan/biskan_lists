<?php
	session_start();
	require "config/config.php";

	$is_logged_in = false;

	if(isset($_SESSION["username"]) && !empty($_SESSION["username"])){
		$is_logged_in = true;
	}

	if($is_logged_in){
		// Connect to the database by creating an instance of the MySQLi class
		$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		// Check for DB connection errors
		// connect_errno returns an integer - 0 if no erros, 1 or more if there is an error
		if( $mysqli->connect_errno ) {
			echo $mysqli->connect_error;
			exit();
		}

		$email = $_SESSION["email"];		

		$sql = "SELECT * FROM lists 
				JOIN users 
				ON lists.email = users.email
				WHERE users.email = '" . $email . "';";

		$search_results = $mysqli->query($sql);
		if(!$search_results) {
			echo $mysqli->error;
			exit();
		}
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Biskan Lists | Home</title>
	<link href="https://fonts.googleapis.com/css2?family=Permanent+Marker&family=Questrial&display=swap" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="css/shared_styles.css">
	<link rel="stylesheet" type="text/css" href="css/index.css">
	<link rel="stylesheet" type="text/css" href="css/responsive.css">
	<link rel="icon" href="img/icon.jpg">
</head>
<body>
	<div id="navbar">
		<div id="logo">Biskan Lists</div>
		<div id="menu-item-bar">
			<a href="#" class="menu-item current-page">Home</a>
			<?php if($is_logged_in): ?>
				<a href="lists.php" class="menu-item">Lists</a>
				<a href="logout.php" class="menu-item">Logout</a>
			<?php else: ?>
				<a href="login_register.php" class="menu-item">Login/Sign Up</a>
			<?php endif; ?>
		</div>
	</div>

	<div id="content">
		<div id="search-container">
			<form action="search_results.php" method="GET"  id="search-form">
				<input type="text" name="search-term" id="search-bar" placeholder="Search a movie name!">
				<button type="submit" id="search-submit-button">Search</button>
			</form>
		</div>

		<div id="welcome">
			Welcome <?php if(!$is_logged_in){echo "internet traveler"; } else {echo $_SESSION["username"]; } ?>!
		</div>

		<div id="results">
			<h2 id="title">
				Now Showing
			</h2>

			<div class="loader-wrapper">
		    	<span class="loader"><span class="loader-inner"></span></span>
		    </div>
		</div>
		
	</div>

	<div id="footer">
		Created by Abasiakan Victor Udobong, ITP 303 2020
	</div>

	<div id="overlay"></div>
	<div id="add-to-list" class="pop-up">
		<div class="pop-up-content">
			<form action="add_item.php" method="POST" id="add-list-form">
				<label>Choose a list to add to</label>
				<select name="list_id">
					<?php if($is_logged_in): ?>
						<?php while($row = $search_results->fetch_assoc()) : ?>
							<option value="<?php echo $row['id']?>"><?php echo $row["name"]?></option>
						<?php endwhile; ?>
					<?php endif; ?>
				</select>
				<button type="submit">Add to List</button>
			</form>
		</div>
	</div>

	<div id="not-logged-in" class="pop-up">
		<div class="pop-up-content">
			<span>You are not logged in!</span>
			<span>Login or create an account to create and add lists</span>
			<form action="login_register.php">
				<button type="submit">Log in/Sign Up</button>
			</form>
		</div>
	</div>

	<script type="text/javascript">
		function linkLogin() {
			location.replace("login_register.php");
		}

		function linkLists() {
			location.replace("lists.php");
		}

		document.querySelector("#overlay").addEventListener("click", function(){
			document.querySelector("#overlay").style.display = "none";
			document.querySelector("#add-to-list").style.display = "none";
			document.querySelector("#not-logged-in").style.display = "none";
		});

		document.querySelector("#add-to-list form").addEventListener('submit', function(){
			event.preventDefault();
			var add_form = document.querySelector("#add-to-list form");
			add_form.action = add_form.action.split('&list_id=')[0] + "&list_id=" + document.querySelector("#add-to-list select").value;
			console.log(add_form.action);
			this.submit();
		});
	</script>

	<script type="text/javascript">
		<?php
			if($is_logged_in){
				echo "var is_logged_in = true;"; 
			} 
			else {
				echo "var is_logged_in = false;"; 
			}
		?>
	</script>

	<script src="js/index.js"></script>
</body>
</html>