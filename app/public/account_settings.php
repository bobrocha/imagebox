<?php
session_start();

if (!$_SESSION['islogedin']) {
	header('Location: index.php');
}

require_once '../includes/bootstrap.php';
require_once INC_PATH . 'Authenticate.php';
require_once INC_PATH . 'Photo.php';

$auth = new Authenticate($dbh);
$photo = new Photo($dbh);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$fname = (!empty($_POST['fname'])) ? $_POST['fname'] : false ;
	$lname = (!empty($_POST['lname'])) ? $_POST['lname'] : false ;
	$pword 	= (!empty($_POST['pword'])) ? $_POST['pword'] : false ;
	$deleteAll = (!empty($_POST['deleteAll'])) ? $_POST['deleteAll'] : false ;
	$deleteAcc = (!empty($_POST['deleteAcc'])) ? $_POST['deleteAcc'] : false;


	if ($fname && $lname) {
		$result = $auth->changeName($fname, $lname, $_SESSION['id']);

		if ($result) {
			header('Content-Type: application/json');
			echo json_encode(array('status' => 'success', 'name' => $result));
			return;
		}
		header('Content-Type: application/json');
		echo json_encode(array('status' => 'failure', 'message' => 'Something went wrong!'));
	} else if ($pword) {
		if ($auth->changePassword($pword)) {
			header('Content-Type: application/json');
			echo json_encode(array('status' => 'success', 'message' => 'Password updated!'));
			return;
		}
		header('Content-Type: application/json');
		echo json_encode(array('status' => 'failure', 'message' => 'Something went wrong!'));
	} else if ($deleteAll) {
		if ($photo->deleteAllPhotos($_SESSION['id']) === true) {
			header('Content-Type: application/json');
			echo json_encode(array('status' => 'success', 'message' => 'All photos were deleted!'));
			return;
		} else if (!$photo->deleteAllPhotos($_SESSION['id'])) {
			header('Content-Type: application/json');
			echo json_encode(array('status' => 'failure', 'message' => $photo->getError()));
			return;
		}
		header('Content-Type: application/json');
		echo json_encode(array('status' => 'failure', 'message' => 'Something went wrong!'));
	} else if ($deleteAcc) {
		if ($photo->deleteAccount($_SESSION['id'], $auth)) {
			header('Content-Type: application/json');
			echo json_encode(array('status' => 'success', 'message' => 'Account Deleted!'));
			return;
		}
		header('Content-Type: application/json');
		echo json_encode(array('status' => 'failure', 'message' => 'Unable to delete account.'));
	}
}
?>