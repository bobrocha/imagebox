<?php
// get tags for tag section
$dbh->prepare("");
?>
<!doctype html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Photo</title>
	<link href="css/global.css" rel="stylesheet" type="text/css">
	<link href="css/view_photo.css" rel="stylesheet" type="text/css">
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
			<div class='photo-section'>
				<div class='photo-head'>
					<span><a href='#' class='backBtn'>back</a></span>
					<div class='photo-info editContainer'>
						Photo Name : <span class='editValue title-value'><?php echo (!$title) ? 'Untitled' : $title ; ?></span>
						<span class='btn'><a href='#' class='editBtn'>edit</a></span>
					</div>
					<span><a href='large_images/<?php echo $photo; ?>' class='ogFile'>view original</a></span><br />
					<span><a href='#' class='delBtn'>delete</a></span>
				</div>
				<div class='photo'>
					<img src='medium_images/<?php echo $photo; ?>' alt='<?php echo $title; ?>' title='<?php echo $title; ?>'>
				</div>
				<div class='tag-section'>
					<div class='tags editContainer'>Photo Tags:
						<?php
						if ($tags) {
							echo "<span class='editValue tags-value'>";

							foreach ($tags as $tag) {
								echo $tag['tag_name'];
							}

							echo "</span>&nbsp;<span class='btn'><a href='#' class='editBtn'>edit</a></span>";
						} else {
							echo "<span class='editValue tags-value'>No tags for photo</span>
								  <span class='btn'><a href='#' class='editBtn'>edit</a></span>";
						}

						?>
					</div>
				</div>
				<div class='ajax-loading'>
					<img src='images/ajax-loader.gif'>
				</div>
				<div class='response'>
				</div>
			</div>
			<div class='commit-section'>
				<a href='#' class='save'>save</a>
				<a href='#' class='cancel'>cancel</a>
			</div>
	</div>
	<!-- end content -->

</div>
<!-- end container-->
	<script src='js/jquery.js'></script>
	<script src='js/edit_photo.js'></script>
</body>
</html>