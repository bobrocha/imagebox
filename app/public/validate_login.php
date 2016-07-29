<?php
session_start();
require_once '../includes/bootstrap.php';
require_once INC_PATH . 'Authenticate.php';
$auth = new Authenticate($dbh);

// errors for empty fields
$errors = array();

// field mappings
$form_fields = array(
	'email'	=>	'email',
	'pword'	=>	'password',
);

// make sure form fields
if (isset($_POST['login'])) {

	foreach ($_POST as $field => $val) {
		if (empty($val)) {
			$errors[$field] = $form_fields[$field];
		}
	}

	// if everything is entered log user in
	if (count($errors) === 0) {
		$auth->login($_POST['email'], $_POST['pword']);
	} else {
		echo "<b style='display: block; text-align: center'>Please Enter the Following:<br />"
				.implode('<br />', $errors).
			  "<br /><a href='index.php'>Login</a></b>";
	}
}
?>