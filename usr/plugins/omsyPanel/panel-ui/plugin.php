<?php
$buff = [];
$ri = 'usr/plugins/';
$folderTemplate = scandir(__root__ . $ri);
foreach($folderTemplate as $value) {
	if(in_array($value, ['.', '..'])) continue;
	if(file_exists(__root__ . $ri . 'template.json')) continue;

	$config = file_get_contents(__root__ . $ri . $value . '/' . 'template.json');
	$config = json_decode($config);

	if(isset($config->disabled)) continue;

	$config->id = $value;
	$buff[] = $config;
}
include "interface/plugin.php";