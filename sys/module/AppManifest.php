<?php
class AppManifest {
	private $fileAccess = __root__ . 'manifest.json';

	private $application = array(
		"name" => "",
		"short_name" => "",
		"start_url" => "",
		"display" => "",
		"theme_color" => "",
		"background_color" => "",
		"icons" => array(
			array(
				"src" => "", // Url
				"sizes" => "", // 64x64
				"type" => "" // image/png
			)
		)
	);

	public function get_application() { return $this->application; }

	public function reand_file() {
		// Ouverture du fichier popur lire sont contenus
		$getFileContent = file_get_contents($this->fileAccess);
		$getFileContent = json_decode($getFileContent);

		$this->application = array(
			"name" => $getFileContent->name,
			"short_name" => $getFileContent->short_name,
			"start_url" => $getFileContent->start_url,
			"display" => $getFileContent->display,
			"theme_color" => $getFileContent->theme_color,
			"background_color" => $getFileContent->background_color,
			"icons" => array(
				array(
					"src" => $getFileContent->icons[0]->src, // Url
					"sizes" => $getFileContent->icons[0]->sizes, // 64x64
					"type" => $getFileContent->icons[0]->type // image/png
				)
			)
		);
	}

	public function update($content) {
		$this->application = array(
			"name" => $content->name,
			"short_name" => $content->short_name,
			"start_url" => $content->start_url,
			"display" => $content->display,
			"theme_color" => $content->theme_color,
			"background_color" => $content->background_color,
			"icons" => array(
				array(
					"src" => $content->icons->src, // Url
					"sizes" => $content->icons->sizes, // 64x64
					"type" => $content->icons->type // image/png
				)
			)
		);
	}

	public function print_file() {
		file_put_contents($this->fileAccess, json_encode($this->application));
	}
}