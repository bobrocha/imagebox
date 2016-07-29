<?php
// class represents a photo and possible photo controls/commands
class Photo
{
	// databse handle
	private $dbh;

	// error message
	private $err;

	// was photo saved successfully?
	private $saved = false;

	// Authentication object to delete account
	private $auth;

	// constructor assigns connection
	public function __construct(PDO $dbh)
	{
		$this->dbh = $dbh;
	}

	// save a photo to file system and photo info to database
	public function save($filename, $title, $user, array $tags = array())
	{
		if (file_exists($filename)) {

			// where file will be saved
			$locations = array('large_images', 'medium_images', 'thumbnail_images');

			// original non resize file
			$data = file_get_contents('tmp/' . basename($filename, substr($filename, -4)));

			foreach ($locations as $location) {
				if ($location == 'thumbnail_images') {
					$success = $this->resize_image($filename, 300, 300, $location , basename($filename));
				} else if ($location == 'medium_images') {
					$success = $this->resize_image($filename, 600, 600, $location , basename($filename));
				} else {
					$success = file_put_contents($location . DIRECTORY_SEPARATOR . basename($filename), $data);
				}
			}
			
			if ($success) {
				// save photo info to databse
				$photoSql = 'INSERT INTO `photos` (`title`, `filename`, `user`) VALUES (:title, :filename, :user)';
				$vals = array(':title' => $title, ':filename' => basename($filename), ':user' => $user);
				$stmt = $this->dbh->prepare($photoSql);

				// tag photo if tags were provided
				if ($stmt->execute($vals)) {
					$this->saved = true;

					if (!empty($tags)) {
						$photo_id = $this->dbh->lastInsertId();
						$this->insert_tags($tags);
						$this->tag_photo($photo_id, $tags);
					}
				} else {
					// delete photo from disk if query unsuccessful
					foreach ($locations as $location) {
						unlink($location . DIRECTORY_SEPARATOR . basename($filename));
					}
					$this->err = 'Photo not saved';
					$this->saved = false;
				}
			}
		}
	}

	// get possible error
	public function getError() {
		return $this->err;
	}

	// check to see if photo saved successfully
	public function photoSaved() {
		return $this->saved;
	}

	// inserts photo tags into database with the exception of already existing tags
	public function insert_tags(array $tags)
	{
		foreach ($tags as $tag) {
			$tags_sql = 'INSERT IGNORE INTO `tags` (tag) VALUES (:tag)';
			$stmt = $this->dbh->prepare($tags_sql);
			$stmt->execute(array(':tag' => $tag));
		}
	}

	// tags a photo
	public function tag_photo($photo_id, array $tags)
	{
		// get id's of tags
		$placeholder = str_repeat('?,', count($tags) -1) . '?';
		$idSql = "SELECT `id` FROM `tags` WHERE tag IN ($placeholder)";
		$stmt = $this->dbh->prepare($idSql);
		$stmt->execute($tags);
		$ids = $stmt->fetchAll(PDO::FETCH_COLUMN);
		
		// tag photo
		$tagged_photos_sql = 'INSERT INTO `tagged_photos` (photo_id, tag_id) VALUES (:photo_id, :tag_id)';

		foreach ($ids as $id) {
			$stmt = $this->dbh->prepare($tagged_photos_sql);
			$stmt->execute(array(':photo_id' => $photo_id, ':tag_id' => $id));
		}
		
	}

	// edits title for photo
	public function editTitle($filename, $title)
	{
		$sql = "UPDATE `photos` SET `title` = :title WHERE `filename` = :filename";
		$stmt = $this->dbh->prepare($sql);
		$result = $stmt->execute(array(':title' => $title, ':filename' => $filename));

		if ($result) {
			return true;
		}
		return false;
	}

	// edits tags for photo
	public function editTags($filename, array $tags)
	{
		// delete from tagged_photos
		$photo_id = $this->getPhotoId($filename);
		$sql = "DELETE FROM `tagged_photos` WHERE `photo_id` = $photo_id";

		// tag photo
		if ($this->dbh->query($sql)) {

			// if user decides to un-tag photo all together
			if (empty($tags)) {
				return true;
			}
			$this->insert_tags($tags);
			$this->tag_photo($photo_id, $tags);

			return true;
		}
		return false;
	}

	// get photo id by filename
	private function getPhotoId($filename)
	{
		$sql = 'SELECT `id` FROM `photos` WHERE `filename` = :filename';
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute(array(':filename' => $filename));
		$result = $stmt->fetchColumn();

		if ($result) {
			return $result;
		}
		return false;
	}

	// deletes photo
	public function delete($filename)
	{
		// remove from disk
		foreach (array('large_images', 'medium_images', 'thumbnail_images') as $location) {
			$success = unlink($location . DIRECTORY_SEPARATOR . $filename);
		}

		// delete from database
		if ($success) {
			$delSql = 'DELETE FROM `photos` WHERE `filename` = :filename';
			$stmt = $this->dbh->prepare($delSql);
			return $stmt->execute(array(':filename' => $filename));
		}
	}

	// delete all photos for user
	public function deleteAllPhotos($user_id)
	{
		// make sure user actually has photos
		$stmt = $this->dbh->prepare('SELECT COUNT(*) `qty` FROM `photos` WHERE `user` = :user');
		$stmt->execute(array(':user' => $user_id));

		if ($stmt->fetchColumn()) {
			
			// get all photos from user and delete them
			$stmt = $this->dbh->prepare('SELECT `filename` FROM `photos` WHERE `user` = :user');

			if ($stmt->execute(array(':user' => $user_id))) {
				$locations = array('large_images', 'medium_images', 'thumbnail_images');

				while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
					foreach ($locations as $location) {
						$success = unlink($location . DIRECTORY_SEPARATOR . $row['filename']);
					}
				}

				// delete records from database
				if ($success) {
					$stmt = $this->dbh->prepare('DELETE FROM `photos` WHERE `user` = :user');

					if ($stmt->execute(array(':user' => $user_id))){
						return true;
					}
					$this->err = 'Unable to delete records from database.';
					return false;
				}
				$this->err = 'Unable to delete photos from disk.';
				return false;
			}
			$this->err = 'Something went wrong!';
			return false;
		} else {
			$this->err = 'No photos uploaded.';
			return false;
		}
	}

	// delete account
	public function deleteAccount($user_id, $auth)
	{
		$this->auth = $auth;

		// delete with photos uploaded
		if ($this->deleteAllPhotos($user_id)) {
			return $this->auth->deleteAccount($user_id);
		}

		// delete with no photos uploaded
		return $this->auth->deleteAccount($user_id);
	}

	// resize function
	/*
	  * @file the source file/image
	  * @w the width you wish to set
	  * @h the height you wish to set
	  * @path where to save the file
	  * @name the name you wish to save it as
	 */
	private function resize_image($file, $w, $h, $path, $name)
	{

		// get needed information from image
		$image = getimagesize($file);
		$width = $image[0];
		$height = $image[1];
		$type = $image['mime'];

		// ratio/proportion
		$r = $width/$height;

		// calculate proportions/ratios
		if ($w/$h > $r) {
			$newwidth = $h * $r;
			$newheight = $h;
		} else {
			$newheight = $w/$r;
			$newwidth = $w;
		}

		// check type of image and process accordingly
		switch($type) {
			case 'image/gif':
				$src = imagecreatefromgif($file);
				$dst = imagecreatetruecolor($newwidth, $newheight);
				imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
				$sucess = imagegif($dst, basename($path) . DIRECTORY_SEPARATOR . basename($name, '.gif') . '.gif');
				imagedestroy($dst);
				return $success;
				break;
			case 'image/jpeg':
				$src = imagecreatefromjpeg($file);
				$dst = imagecreatetruecolor($newwidth, $newheight);
				imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
				$success = imagejpeg($dst, basename($path) . DIRECTORY_SEPARATOR . basename($name, '.jpg') . '.jpg', 100);
				imagedestroy($dst);
				return $success;
				break;
			case 'image/png':
				$src = imagecreatefrompng($file);
				$dst = imagecreatetruecolor($newwidth, $newheight);
				imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
				$success = imagepng($dst, basename($path) . DIRECTORY_SEPARATOR . basename($name, '.png') . '.png', 9, PNG_ALL_FILTERS);
				imagedestroy($dst);
				return $success;
				break;
			default:
				exit('Not an accepted image format');
		}
	
	}
}