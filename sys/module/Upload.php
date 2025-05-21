<?php
class Upload {
	private $CMS = null;
	private $tailleMax = 2097152;
	private $extensionsValides = array('jpg', 'jpeg', 'gif', 'png');

	function __construct($CMS) {
		$this->CMS = $CMS;
	}

	public function move($dataName, $folderPath = 'blog') {
		if(!isset($_FILES[$dataName]) AND empty($_FILES[$dataName]['name']))
			return ["Success" => false, 'Error' => "Not existe"];

		if($_FILES[$dataName]['size'] > $this->tailleMax)
			return ["Success" => false, 'Error' => "Error taille {$_FILES[$dataName]['size']} > {$this->tailleMax}"];

		$extensionUpload = strtolower(substr(strrchr($_FILES[$dataName]['name'], '.'), 1));
		if(!in_array($extensionUpload, $this->extensionsValides))
			return ["Success" => false, 'Error' => "Error ext"];

		$chemin = "share/upload/{$folderPath}/".uniqid().".".$extensionUpload;
		$resultat = move_uploaded_file($_FILES[$dataName]['tmp_name'], __root__.$chemin);
		//if(!$resultat)
		return ["Success" => true, "File" => "/{$chemin}"];

		return ["Success" => false, 'Error' => "Not moving", $resultat];
	}

	function resized($newWidth, $newHeight, $file) {
		$file = __root__.$file;
		if(!file_exists($file)) return null;

		list($width, $height) = getimagesize($file);

		// Ne redimensionne pas, si l'image fait la meme taille
		/*if(
			$height <= $newHeight
			&&
			$width <= $newWidth
		) return null; */

		$mime = mime_content_type($file);

		switch($mime) {
			case 'image/gif':
				$im = imagecreatefromgif($file);
				break;
			case 'image/png':
				$im = imagecreatefrompng($file);
				break;
			case 'image/jpeg':
				$im = imagecreatefromjpeg($file);
				break;
			case 'image/bmp':
				$im = imagecreatefromwbmp($file);
				break;
		}

		$thumb = imagecreatetruecolor($newWidth, $newHeight);
		imagecopyresized($thumb, $im, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

		imagewebp($thumb, $file.'.webp');

		return $mime;
	}
}