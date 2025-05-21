<?php
class ModuleOmsy {
	private $folderModule = "./sys/module/";

	public function __construct($CMS) {
		$allFiles = scandir(__root__.$this->folderModule);

		foreach ($allFiles as $key => $value) {
			if(in_array($value, ['.', '..'])) continue;

			$basename = pathinfo(__root__.$this->folderModule.$value, PATHINFO_FILENAME);
			//var_dump($basename);

        	$CMS->addModule($basename);
		}
	}
}