var email_error = document.querySelector("#email-error");
var password_error = document.querySelector("#password-error");

var r_email_error = document.querySelector("#r-email-error");
var r_username_error = document.querySelector("#r-username-error");
var r_password_error = document.querySelector("#r-password-error");
var r_confirm_password_error = document.querySelector("#r-confirm-password-error");

if(email_not_exist){
	email_error.innerHTML = "This email does not exist in our records, you can sign up though";
	email_error.style.display = "block";
	is_valid = false;
}

if(invalid_password){
	password_error.innerHTML = "This password doesn't match our records";
	password_error.style.display = "block";
	is_valid = false;
}

if(email_taken_error){
	r_email_error.innerHTML = "This email has been taken by another user";
	r_email_error.style.display = "block";
	is_valid = false;
}

if(username_taken_error){
	r_username_error.innerHTML = "This username has been taken by another user";
	r_username_error.style.display = "block";
	is_valid = false;
}

function validateEmail(email) {
    const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}

document.querySelector("#login-form").onsubmit = function(event) {
	event.preventDefault();

	email_error.style.display = "none";
	password_error.style.display = "none";

	var is_valid = true;

	if(document.querySelector("#email").value.length == 0){
		email_error.innerHTML = "Please enter your email";
		email_error.style.display = "block";
		is_valid = false;
	}

	if(document.querySelector("#password").value.length == 0){
		password_error.innerHTML = "Please enter your password";
		password_error.style.display = "block";
		is_valid = false;
	}

	if(is_valid){
		email_error.style.display = "none";
		password_error.style.display = "none";
		this.submit();
	}
}

document.querySelector("#register-form").onsubmit = function(event) {
	event.preventDefault();

	r_email_error.style.display = "none";
	r_username_error.style.display = "none";
	r_password_error.style.display = "none";
	r_confirm_password_error.style.display = "none";

	var is_valid = true;

	if(document.querySelector("#r-email").value.length == 0){
		r_email_error.innerHTML = "Please enter your email";
		r_email_error.style.display = "block";
		is_valid = false;
	}

	if(!validateEmail(document.querySelector("#r-email").value)){
		r_email_error.innerHTML = "Please enter a valid email";
		r_email_error.style.display = "block";
		is_valid = false;
	}

	if(document.querySelector("#r-username").value.length == 0){
		r_username_error.innerHTML = "Please enter a username";
		r_username_error.style.display = "block";
		is_valid = false;
	}

	if(document.querySelector("#r-password").value.length == 0){
		r_password_error.innerHTML = "Please enter a password";
		r_password_error.style.display = "block";
		is_valid = false;
	}

	if(document.querySelector("#r-confirm-password").value.length == 0){
		r_confirm_password_error.innerHTML = "Please re-enter your password";
		r_confirm_password_error.style.display = "block";
		is_valid = false;
	}

	if(document.querySelector("#r-password").value.length > 0 && 
		document.querySelector("#r-password").value.length < 5){
		r_password_error.innerHTML = "Password must be at least 5 characters";
		r_password_error.style.display = "block";
		is_valid = false;
	}

	if(document.querySelector("#r-password").value.length != 0 && 
		document.querySelector("#r-confirm-password").value.length != 0 && 
		document.querySelector("#r-password").value.length != 
		document.querySelector("#r-confirm-password").value.length){
		r_confirm_password_error.innerHTML = "Passwords must match";
		r_confirm_password_error.style.display = "block";
		is_valid = false;
	}

	if(is_valid){
		r_email_error.style.display = "none";
		r_username_error.style.display = "none";
		r_password_error.style.display = "none";
		r_confirm_password_error.style.display = "none";
		this.submit();
	}
}