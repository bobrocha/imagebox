<!doctype html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Signup</title>
	<link href="css/global.css" rel="stylesheet" type="text/css">
	<link href="css/signup.css" rel="stylesheet" type="text/css">
</head>
<body>
<!-- start container -->
<div class='container'>

	<!-- start content -->
	<div class='content'>

		<form method='post' id='signUpForm' action='validate_signup.php'>
			<div class='input-container'>
				<label for='fname'>First Name</label>
				<input type='text' name='fname' id='fname' placeholder='First Name'><span class='error fname'>First Name not entered</span>
				<label for='lname'>Last Name</label>
				<input type='text' name='lname' id='lname' placeholder='Last Name'><span class='error lname'>Last Name not entered</span>
				<label for='email'>Email</label>
				<input type='email' name='email' id='email' placeholder='Email'><span class='error email1'>Invalid Email</span>
				<label for='pword'>Password</label>
				<input type='password' name='pword' id='pword' placeholder='Password'><span class='error pword'>Password not entered</span>
				<button type='submit' name='signup' value='submit' id='signup'>Sign Up</button>
				<div class='repsonse'></div>
			</div>
		</form>

	</div>
	<!-- end content -->

</div>
<!-- end container -->
	<script src='js/jquery.js'></script>
 	<script src='js/signup.js'></script>

</body>
</html>