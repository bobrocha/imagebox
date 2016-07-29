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
	<title>Upload</title>
	<link href="css/global.css" rel="stylesheet" type="text/css">
	<link href="css/upload.css" rel="stylesheet" type="text/css">
</head>
<body>
	
<!-- start container -->
<div class='container'>

	<!-- start header-->
	<div class='header'>
		<div class='nav'>
			<ul>
				<li><a href='account.php'><?php echo $_SESSION['fname'] . " " . $_SESSION['lname']?></a></li>
				<li><a href='upload.php'>Upload</a></li>
				<li><a href='settings.php'>Settings</a></li>
				<li><a href='logout.php'>Logout</a></li>
			</ul>
		</div>
	</div>
	<!-- end header -->

	<!-- start content -->
	<div class='content'>

		<!-- start upload wrapper -->
		<div class='upload-wrapper'>
			<form enctype='multipart/form-data' action='photo_setup.php' method='POST'>
				<label for='file-input'>
				</label>
				<input type='file' name='photo' id='file-input' accept='image/*'>
			</form>
		</div>
		<!-- end upload wrapper -->

	</div>
	<!-- end content -->

</div>
<!-- end container -->

	<script src='js/jquery.js'></script>
 	<script>
 		<!-- submit file on select -->
 		$(document).ready(function() {
 			$("[type=file]").on('change', function(e) {
 				$('form').submit();
 			});
 		});
 	</script>

</body>
</html>