<?php
class FunctionOmsy {
	private $folderFunction = "./sys/function/";

	public function __construct($CMS) {
		$allFiles = scandir(__root__.$this->folderFunction);

		foreach ($allFiles as $key => $value) {
			if(in_array($value, ['.', '..'])) continue;

			$basename = pathinfo(__root__.$this->folderFunction.$value, PATHINFO_FILENAME);
			$CMS->addFunction($basename);
		}
	}
}