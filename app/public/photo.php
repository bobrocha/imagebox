<?php
session_start();

if (!$_SESSION['islogedin']) {
	header('Location: index.php');
}

require_once '../includes/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	// check $_GET variables and set appropriately
	$title = (!empty($_GET['title'])) ? $_GET['title'] : false;
	$photo = (!empty($_GET['photo'])) ? $_GET['photo'] : false ;

	// fetch tags of photo
	$tagsSql = 'SELECT t.tag `tag_name`
				FROM tags t INNER JOIN tagged_photos
					ON t.id = tagged_photos.tag_id
				WHERE tagged_photos.photo_id = (
					SELECT `id`
					FROM `photos`
					WHERE `filename` = :filename)';
	
	$stmt = $dbh->prepare($tagsSql);

	if ($stmt->execute(array(':filename' => $photo))) {
		$tags = $stmt->fetchAll(PDO::FETCH_ASSOC);
		require_once INC_PATH . 'view_photo.inc';
	}
}
?>