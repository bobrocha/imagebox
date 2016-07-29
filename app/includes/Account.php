<?php
// class represents a user's account
class Account
{
	// database handle
	private $dbh;

	// pagination class
	private $pagination;

	// limit of items per page
	private $limit;

	public function __construct($dbh)
	{
		$this->dbh = $dbh;
		$this->pagination = new Pagination($this->dbh);
	}

	// set how many photos to display per page
	public function setLimit($limit)
	{
		$this->limit = $limit;
	}

	// gets array of photos for display
	public function getPhotos()
	{
		// determine the total number of records
		$totalSql = 'SELECT COUNT(*) FROM `photos` WHERE `user` = :user';
		$params =  array(':user' => $_SESSION['id']);

		if (!$this->pagination->totalRecords($totalSql, $params)) {
			return false;
		}

		// set limit for pagination
		$this->pagination->setLimit($this->limit);
		
		// offset for progressive selection based on pageno
		$offset = ($this->pagination->page() - 1) * $this->limit;

		// get photos of user
		$sql = "SELECT `title`, `filename` FROM `photos` WHERE `user` = :user LIMIT $offset, $this->limit";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute(array(':user' => $_SESSION['id']));

		// array of photos to be returned
		return array(
					$this->pagination->firstBack(),
					$this->pagination->nextLast(),
					$this->pagination->where(),
					$stmt->fetchAll(PDO::FETCH_ASSOC)
					);
	}

	// get array of photos by tag
	public function getPhotosByTag($tag)
	{
		// get photos of user
		$sql = "SELECT `title`, `filename`
				FROM `photos` INNER JOIN `tagged_photos`
				ON photos.id = tagged_photos.photo_id
				INNER JOIN `tags`
				ON tagged_photos.tag_id = tags.id
				WHERE tags.tag = :tag AND photos.user = :user_id";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute(array(':tag' => $tag, ':user_id' => $_SESSION['id']));

		// array of photos to be returned
		return array(
					$this->pagination->firstBack(),
					$this->pagination->nextLast(),
					$this->pagination->where(),
					$stmt->fetchAll(PDO::FETCH_ASSOC)
					);
	}
}