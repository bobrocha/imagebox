<?php
session_start();

if (!$_SESSION['islogedin']) {
	header('Location: index.php');
}

require_once '../includes/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

	if (!empty($_GET['tag'])) {
		$tag = "%$_GET[tag]%";
		$tagStr = $_GET['tag'];
		$stmt = $dbh->prepare('SELECT `tag` FROM `tags` WHERE `tag` LIKE ? LIMIT 8');
		$result = array();

		$stmt->bindParam(1, $tag, PDO::PARAM_STR);
		$stmt->execute();

		// store result
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$result[] = $row['tag'];
		}

		$tags = '';

		// create links for results
		foreach ($result as $value) {
			$row = "<li><a class='tag' href='#'>" . str_replace($tagStr, "<span style='font-weight: bold'>" . $tagStr . "</span>", $value) . '</a></li>';
			$tags .= $row;
		}
		
		if ($tags) {
			echo $tags;
		} else {
			echo '<li>No match found</li>';
		}
	}
}
?>