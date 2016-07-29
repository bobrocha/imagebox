<?php
session_start();
require_once '../includes/bootstrap.php';
require_once INC_PATH . 'Authenticate.php';
$auth = new Authenticate($dbh);
$auth->logout();
header('Location: index.php');
?>