<?php
// bootstrap code
define('INC_PATH', '../includes/');

$config = require_once 'config.php';
$dsn = "mysql:host=$config[host];dbname=$config[db]";

try {
	$dbh = new PDO($dsn, $config['username'], $config['password']);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
	echo 'Connection failed: ' . $e->getMessage();
}