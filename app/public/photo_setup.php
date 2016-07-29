<?php
session_start();

if (!$_SESSION['islogedin']) {
	header('Location: index.php');
}

// make temp dir for photo
if (!file_exists('tmp')) {
	mkdir('tmp');
}

require_once '../includes/bootstrap.php';
require_once INC_PATH . 'ImageFileUpload.php';

$imgUpload = new ImageFileUpload(5242880, 'tmp'); // 5MB
$imgUpload->processFile($_FILES);

// check for valid image
if ($imgUpload->validFile()) {
	
	// resize image for photo setup
	$imgUpload->resize_image('tmp' . DIRECTORY_SEPARATOR . $imgUpload->getFileName(), 600, 600, 'tmp', $imgUpload->getFileName());
	
	//include setup controls
	require_once INC_PATH . 'photo_setup.inc';

} else {
	echo "<b>" . $imgUpload->getError() . "</b> Please <a href='upload.php'>upload</a> a valid photo.";
}
?>