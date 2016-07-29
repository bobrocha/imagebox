<?php
/* Class for processing uploaded files.
   Handles only 1 file for now. Future
   design should include multiple file uploads
 */
class ImageFileUpload
{
	// max size of filesize, must be set in bytes
	private $max;

	// directory where file will finally be stored
	private $dir;

	// value indicates if file is valid
	private $isValid;

	// errors from uploaded file
	private $error;

	// randomly generated filename
	private $fileName;

	// current image type i.e. GIF, JPG, PNG
	private $type;

	// constructur will set up options
	public function __construct($max, $dir)
	{
		$this->max = (int)$max;
		$this->dir = $dir;
	}

	// function to process the uploaded file
	public function processFile(array $file)
	{
		foreach ($file as $f) {

			// make sure file uploaded with success
			if ($f['error'] === 0) {

				// make sure file is an image
				if (getimagesize($f['tmp_name']) == true) {

					// make sure file is correct size
					if ($f['size'] <= $this->max) {

						// generate random filename for later use
						$this->fileName = $this->randFileName();
						$this->isValid = true;

						//store file in provided path
						move_uploaded_file($f['tmp_name'], $this->dir . DIRECTORY_SEPARATOR . $this->getFileName());

					} else {
						$this->isValid = false;
						$this->error = 'File exceeds the max allowed.';
					}
				} else {
					$this->isValid = false;
					$this->error = 'File is not an image.';
				}

			} else {
				$this->isValid = false;
				$this->error = 'File failed to upload.';
			}
		}
	}


	// checks to see if file is valid
	public function validFile()
	{
		if ($this->isValid === true) {
			return true;
		}
		return false;
	}

	// gets error message
	public function getError()
	{
		return $this->error;
	}

	// generate a unique/random name for file (24 chars in length)
	private function randFileName()
	{
		return bin2hex(openssl_random_pseudo_bytes(12));
	}

	// gets randomly generated filename
	public function getFileName()
	{
		return $this->fileName;
	}

	// gets image/file type
	public function getType() {
		return $this->type;
	}

	// resize function
	/*
	  * @file the source file/image
	  * @w the width you wish to set
	  * @h the height you wish to set
	  * @path where to save the file
	  * @name the name you wish to save it as
	 */
	public function resize_image($file, $w, $h, $path, $name)
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
				imagegif($dst, basename($path) . DIRECTORY_SEPARATOR . basename($name) . '.gif');
				$this->type = '.gif';
				imagedestroy($dst);
				break;
			case 'image/jpeg':
				$src = imagecreatefromjpeg($file);
				$dst = imagecreatetruecolor($newwidth, $newheight);
				imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
				imagejpeg($dst, basename($path) . DIRECTORY_SEPARATOR . basename($name) . '.jpg', 100);
				$this->type = '.jpg';
				imagedestroy($dst);
				break;
			case 'image/png':
				$src = imagecreatefrompng($file);
				$dst = imagecreatetruecolor($newwidth, $newheight);
				imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
				imagepng($dst, basename($path) . DIRECTORY_SEPARATOR . basename($name) . '.png', 9, PNG_ALL_FILTERS);
				$this->type = '.png';
				imagedestroy($dst);
				break;
			default:
				exit('Not an accepted image format');
		}
	
	}
}