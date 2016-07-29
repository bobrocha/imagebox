<!doctype html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Upload</title>
	<link href="css/global.css" rel="stylesheet" type="text/css">
	<link href="css/photo_setup.css" rel="stylesheet" type="text/css">
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

		<!-- start setup section -->
			<div class='photo-setup'>

				<div class='setup-head'>
					<div class='photo-name'>Photo Name : <input type='text' placeholder='Photo Name' name='photo-name' title='Photo Name' value='Untitled'></div>
				</div>

				<div class='photo-section'>
					<img src="<?php echo 'tmp/' . $imgUpload->getFileName() . $imgUpload->getType() ?>" alt='photo'>
				</div>

				<div class='tag-section'>
					Photo Tags : <input type='text' placeholder='Tags e.g. (#beach #park #dog)'>
				</div>

				<div class='ajax-loading'>
					<img src='images/ajax-loader.gif'>
				</div>

				<div class='response'>
				</div>

				<div class='commit-section'>
					<a class='save' href='#'>save</a><a class='cancel' href='upload.php'>cancel</a>
				</div>

			</div>
		<!-- end setup section-->

	</div>
	<!-- end content -->

</div>
<!-- end container -->

	<script src='js/jquery.js'></script>
	<script src='js/photo_setup.js'></script>
</body>
</html>