<?php
/**
 * FunctionOmsy Class
 * 28/07/25 - Create file
 */
class FunctionOmsy
{
	function __construct($CMS)
	{
		$allFiles = scandir(__root__.$CMS->Path->SysFunction);
		foreach ($allFiles as $key => $value) {
			if(in_array($value, ['.', '..'])) continue;
			$basename = pathinfo(__root__.$CMS->Path->SysFunction.$value, PATHINFO_FILENAME);
			$CMS->addFunction($basename);
		}
	}

	function afterCMSLoad() {}
}