<?php
session_start();
require_once '../includes/bootstrap.php';
require_once INC_PATH . 'Photo.php';
$photo = new Photo($dbh);

if (!$_SESSION['islogedin']) {
	header('Location: index.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	// check post variables and set appropriately
	$title = (!empty($_POST['title'])) ? $_POST['title'] : '' ;
	$tags = (!empty($_POST['hash_tags'])) ? $_POST['hash_tags'] : '' ;
	$filename = (!empty($_POST['filename'])) ? $_POST['filename'] : false ;

	if (isset($title) && !empty($tags) && $filename) {
		if (
		$photo->editTitle($filename, $title) &&
		$photo->editTags($filename, $tags) ) {
			echo 'Photo updated!';
			return;
		}
	} else if(isset($title) && isset($tags) && $filename) {
		if (
		$photo->editTitle($filename, $title) &&
		$photo->editTags($filename, array()) ) {
			echo 'Photo updated!';
			return;
		}
	} else if (isset($title) && $filename) {
		if ($photo->editTitle($filename, $title)) {
			echo 'Photo updated!';
			return;
		}
	} else if (!empty($tags) && $filename) {
		if ($photo->editTags($filename, $tags) ) {
			echo 'Photo updated!';
			return;
		}
	}
} else if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	$del_photo = (!empty($_GET['delete'])) ? $_GET['delete'] : false ;
	$filename = (!empty($_GET['filename'])) ? $_GET['filename'] : false ;

	if ($del_photo && $filename) {
		if ($photo->delete($filename)) {
			header('Content-Type: application/json');
			echo json_encode(array('status' => 'success', 'message' => 'Photo deleted!'));
			return;
		}
		header('Content-Type: application/json');
		echo json_encode(array('status' => 'failure', 'message' => 'Photo not deleted!'));
	}
}
?>