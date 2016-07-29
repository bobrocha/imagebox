<!doctype html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Image Box</title>
	<link href="css/global.css" rel="stylesheet" type="text/css">
	<link href="css/index.css" rel="stylesheet" type="text/css">
</head>
<body>
		<h1>The Repository For Your Photos</h1>
		<form method='post' id='loginForm' action='validate_login.php'>
			<div class='input-container'>
				<input type='email' placeholder='Email' id='email' name='email'>
				<input type='password' placeholder='Password' id='pword' name='pword'>
				<button type='submit' name='login' value='login' id='login'>Log In</button>
				<div class='signup'><a href='signup.php'>Sign Up</a></div>
			</div>
		</form>
		<div class='error'><span>Your email or password was incorrect</span></div>

	<script src='js/jquery.js'></script>
 	<script src='js/login.js'></script>
</body>