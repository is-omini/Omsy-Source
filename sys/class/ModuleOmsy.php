<?php
/**
 * ModuleOmsy Class
 * 29/07/25 - Create file
 */
class ModuleOmsy
{
	function __construct($CMS)
	{
		$allFiles = scandir(__root__.$CMS->Path->SysModule);

		foreach ($allFiles as $key => $value) {
			if(in_array($value, ['.', '..'])) continue;

			$basename = pathinfo(__root__.'/'.$CMS->Path->SysModule.'/'.$value, PATHINFO_FILENAME);
			if(empty($basename)) continue;

			//$CMS->addModule($basename);
		}
	}
}