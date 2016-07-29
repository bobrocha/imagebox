<?php
session_start();

if (!$_SESSION['islogedin']) {
	header('Location: index.php');
}

?>
<!doctype html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Settings</title>
	<link href="css/global.css" rel="stylesheet" type="text/css">
	<link href="css/settings.css" rel="stylesheet" type="text/css">
</head>
<body>

<!-- start container -->
<div class='container'>

	<!-- start header-->
	<div class='header'>
		<div class='nav'>
			<ul>
				<li><a href='account.php'><?php echo "<span class='fname'>$_SESSION[fname]</span>" . " " . "<span class='lname'>$_SESSION[lname]</span>"; ?></a></li>
				<li><a href='upload.php'>Upload</a></li>
				<li><a href='settings.php'>Settings</a></li>
				<li><a href='logout.php'>Logout</a></li>
			</ul>
		</div>
	</div>
	<!-- end header -->

	<!-- start content -->
	<div class='content'>

		<!-- start settings -->
		<div class='settings'>
			<h4>Account Settings</h4>
			<div class='row name-setting'>
				<span class='col name'>Name</span>
				<span class='col value'><?php echo "<span class='fname'>$_SESSION[fname]</span>" . " " . "<span class='lname'>$_SESSION[lname]</span>"; ?></span>
				<span class='col btn'><a href='#' class='editBtn'>edit</a></span>
			</div>
			<div class='row name-editContainer hidden '>
				<div class='first-name'>
					<label for='fname'>First</label>
					<input type='text' name='fname' class='fname' id='fname'>
				</div>
				<div class='last-name'>
					<label for='lname'>Last</label>
					<input type='text' name='lname' class='lname' id='lname'>
				</div>
				<div class='name-commit-section'>
					<a href='#' class='save'>save</a>
					<a href='#' class='cancel'>cancel</a>
				</div>
				<div class='name-response hidden'>
				</div>
			</div>
			<div class='row password-setting'>
				<span class='col name'>Password</span>
				<span class='col value'>************</span>
				<span class='col btn'><a href='#' class='editBtn'>edit</a></span>
			</div>
			<div class='row password-editContainer hidden'>
				<span class='col'><label for='pword'>Password</label></span>
				<span class='col'><input type='password' name='pword' class='pword' id='pword' autocomplete="new-password"></span>
				<span class='col'><a href='#' class='save'>save</a></span>
				<span class='col'><a href='#' class='cancel'>cancel</a></span>
			</div>
			<div class='row pword-response hidden'>
			</div>
			<div class='row delete-all-photos'>
				<span class='col'><a href='#'>Delete All Photos</a></span>
			</div>
			<div class='row delete-account'>
				<span class='col'><a href='#'>Delete Account</a></span>
			</div>
			<div class='row general-response hidden'>
			</div>
		</div>
		<!-- end settings -->

	</div>
	<!-- end content -->

</div>
<!-- end container -->

	<script src='js/jquery.js'></script>
 	<script src='js/settings.js'></script>

</body>
</html>