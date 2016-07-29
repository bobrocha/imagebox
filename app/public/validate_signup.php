<?php
require_once '../includes/bootstrap.php';
require_once INC_PATH . 'Authenticate.php';
$auth = new Authenticate($dbh);

// errors for empty fields
$errors = array();

// field mapping
$form_fields = array(
	'fname' => 'first name',
	'lname' => 'last name',
	'email' => 'email',
	'pword' => 'password'
);

// make sure form fields were entered
if (isset($_POST['signup'])) {

	foreach ($_POST as $field => $val) {
		if (empty($val)) {
			$errors[$field] = $form_fields[$field];
		}
	}

	// if everything is entered create account
	if (count($errors) === 0) {
		$auth->createAccount($_POST['email'], $_POST['pword'], $_POST['fname'], $_POST['lname']);
	} else {
		echo "<b style='display: block; text-align:center'>Please Enter the Following:<br />" . implode('<br />', $errors) . "<br/><a href='signup.php'>Sign Up</a></b>";
	}
}
?>