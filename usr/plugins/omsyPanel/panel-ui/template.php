<?php
$buff = [];
$ri = 'usr/template/';
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

if(isset($_GET['active'])) {
	$name = htmlentities($_GET['active']);

	$folderTemplateJSON = __root__ . $ri . $name . '/template.json';
	if(!file_exists($folderTemplateJSON)) die('Not existe !' . $folderTemplateJSON);

	$getConfig = json_decode(file_get_contents($folderTemplateJSON));

	$getConfig->id = $name;
	$name = $getConfig->name;

	$plugins = ['omsyPanel'];

	foreach ($getConfig->install->plugin as $value) {
		$plugins[] = $value->id;
	}

	//var_dump($getConfig, $name, $plugins);
	CMS->setTemplate($getConfig->id);
	CMS->setPlguins($plugins);

	CMS->Log->added('<@0> à mis à jour le thème, en '.$name);
}

CMS->setTitle('Mes thème');

include "interface/template.php";
?>