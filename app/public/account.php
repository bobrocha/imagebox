<?php
session_start();

if (!$_SESSION['islogedin']) {
	header('Location: index.php');
}

require_once '../includes/bootstrap.php';
require_once INC_PATH . 'Account.php';
require_once INC_PATH . 'Pagination.php';

$account = new Account($dbh);
$account->setLimit(12);

$search = (!empty($_GET['search'])) ? $_GET['search'] : false ;

if ($search) {
	if ($result = $account->getPhotosByTag($search)) {
		// set up variables
		$fistBack = '';
		$nextLast = '';
		$where = '';
		$photos = $result[3];
	} else {
		$fistBack = '';
		$nextLast = '';
		$where = '';
	}
} else {
	if ($account->getPhotos()) {
		// set up variables
		$fistBack = $account->getPhotos()[0];
		$nextLast = $account->getPhotos()[1];
		$where = $account->getPhotos()[2];
		$photos = $account->getPhotos()[3];
	}
}
?>
<!doctype html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Account</title>
	<link href="css/global.css" rel="stylesheet" type="text/css">
	<link href="css/account.css" rel="stylesheet" type="text/css">
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
		
		<?php
			if (!$account->getPhotos()) {
				exit(
					"<div style='text-align: center'>No photos uploaded yet!</div>
					  <div style='text-align: center'>Click <a href='upload.php'>here</a> to upload photos.</div>"
					);
			} else if (!empty($photos)) {
				echo "<div class='pagination'>$fistBack $where $nextLast</div>
					  <div class='search'>
					  <form id='searchForm' method='get'>
						<label for='search-field'>Search by tag</label>
						<input type='text' id='search-field' name='search' autocomplete='off' placeholder='search by tag #beach #park #dog'><button class='search-button'>Search</button>
						</form>
						<ul class='datalistPlaceholder hidden'>
					  	</ul>
					  </div>";
				foreach ($photos as $photo) {
					echo "
						<div class='photo'>
							<div class='image'>
								<a href='photo.php?photo=".urlencode($photo['filename'])."&title=".urlencode($photo['title'])."'>
								<img src='thumbnail_images/$photo[filename]' alt='$photo[title]'>
								</a></div>
							<div class='photo-name'><span>$photo[title]</span></div>
						</div>
						";
				}
			} else {
				echo "<div style='text-align: center;'>
						<p>No results found for <strong>\"$search\"</strong>.<br />
						Please <a href='account.php'>try again.</a><br /></p></div>";

				if (strpos($search, '#') === false) {
					echo "<div style='text-align: center;'>Please make sure your search query<br /> was typed in correctly i.e. #beach, #park, #dog</div>";
				}		
			}
		?>
	</div>
	<!-- end content -->

	<?php echo "<div class='pagination'>$fistBack $where $nextLast</div>"; ?>
</div>
<!-- end container -->
<script src='js/jquery.js'></script>
<script src='js/autocomplete.js'></script>
</body>