<?php
session_start();
require_once '../includes/bootstrap.php';
require_once INC_PATH . 'Photo.php';
$myPhoto = new Photo($dbh);

if (!$_SESSION['islogedin']) {
	header('Location: index.php');
}


// check request method
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	// check post variables and set appropriately
	$title = (!empty($_POST['title'])) ? $_POST['title'] : false;
	$tags = (!empty($_POST['hash_tags'])) ? $_POST['hash_tags'] : false ;
	$filename = (!empty($_POST['filename'])) ? $_POST['filename'] : false ;

	// check for title and tags and save accordingly
	if ($title && $tags && $filename) {
		if (file_exists($filename)) {
			$myPhoto->save($filename, $title, $_SESSION['id'], $tags);

			// check to see if photo was saved
			if ($myPhoto->photoSaved()) {
				echo 'Photo saved successfully!';
				delete_temp();
				return true;
			}
			echo 'Photo not saved!';
			return false;
		}
	} else if ($title && $filename) {
		if (file_exists($filename)) {
			$myPhoto->save($filename, $title, $_SESSION['id']);

			// check to see if photo was saved
			if ($myPhoto->photoSaved()) {
				echo 'Photo saved successfully!';
				delete_temp();
				return true;
			}
			echo 'Photo not saved!';
			return false;
		}
	} else if ($filename) {
		if (file_exists($filename)) {
			$myPhoto->save($filename, $title, $_SESSION['id']);

			// check to see if photo was saved
			if ($myPhoto->photoSaved()) {
				echo 'Photo saved successfully!';
				delete_temp();
				return true;
			}
			echo 'Photo not saved!';
			return false;
		}
	}
}

function delete_temp() {
	$files = glob('tmp/*');

	foreach($files as $file)
		$sucess = unlink($file);

	if ($sucess)
		rmdir('tmp');
}