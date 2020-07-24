<!DOCTYPE html>
<html>
<head>
	<title>Biskan Lists | Login</title>
	<link href="https://fonts.googleapis.com/css2?family=Permanent+Marker&family=Questrial&display=swap" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="css/shared_styles.css">
	<link rel="stylesheet" type="text/css" href="css/login.css">
	<link rel="stylesheet" type="text/css" href="css/responsive.css">
	<link rel="icon" href="img/icon.jpg">
</head>
<body>
	<div id="navbar">
		<div id="logo">Biskan Lists</div>
		<div id="menu-item-bar">
			<a href="index.php" class="menu-item">Home</a>
			<a href="#" class="menu-item current-page">Login/Sign Up</a>
		</div>
	</div>

	<div id="content">
		<div id="login">
			<h2>Login</h2>
			<form action="login.php" method="POST" id="login-form">
				<label for="email">Email</label>
				<input type="text" name="email" placeholder="Your email address" id="email">
				<span class="error" id="email-error">Error message</span>

				<label for="password">Password</label>
				<input type="password" name="password" placeholder="A strong password" id="password">
				<span class="error" id="password-error">Error message</span>

				<button type="submit">Login</button>
			</form>
		</div>
		<div id="register">
			<h2>Register</h2>
			<form action="register.php" method="POST" id="register-form">
				<label for="email">Email</label>
				<input type="text" name="email" placeholder="Your email address" id="r-email">
				<span class="error" id="r-email-error">Error message</span>

				<label for="username">Username</label>
				<input type="text" name="username" placeholder="Username" id="r-username">
				<span class="error" id="r-username-error">Error message</span>

				<label for="password">Password</label>
				<input type="password" name="password" placeholder="A strong password" id="r-password">
				<span class="error" id="r-password-error">Error message</span>

				<label for="confirm-password">Confirm Password</label>
				<input type="password" name="confirm-password" placeholder="Retype your password" id="r-confirm-password">
				<span class="error" id="r-confirm-password-error">Error message</span>

				<button type="submit">Register</button>
			</form>
		</div>
	</div>

	<div id="footer">
		Created by Abasiakan Victor Udobong, ITP 303 2020
	</div>
	<script type="text/javascript">
		var username_taken_error = false;
		var email_taken_error = false;
		var email_not_exist = false;
		var invalid_password = false;

		<?php 
			if(isset($_GET["error"]) && !empty($_GET["error"])){
				if($_GET["error"] == "error_1"){
					echo "email_not_exist = true;";
				}
				else if($_GET["error"] == "error_2"){
					echo "invalid_password = true;";
				}
				else if($_GET["error"] == "error_3"){
					echo "username_taken_error = true; email_taken_error = true;";
				}
				else if($_GET["error"] == "error_4"){
					echo "email_taken_error = true;";
				}
				else if($_GET["error"] == "error_5"){
					echo "username_taken_error = true;";
				}
			}
		?>
		
	</script>
	<script type="text/javascript" src="js/login.js"></script>
</body>
</html>