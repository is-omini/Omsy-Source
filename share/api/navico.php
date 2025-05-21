<?php
$plugin = $_GET['id'];

$dashboardMenusJSON = @file_get_contents(__root__."\usr\plugins\\$plugin\panel-ui\\navico.json") ?? '';
$dashboardMenusJSON = json_decode($dashboardMenusJSON) ?? [];

foreach ($dashboardMenusJSON as $key => $value) {
	$dashboardMenusJSON[$key]->url = "/panel/getter?plugin=$plugin&page=".$value->url;
}

header('Content-Type: application/json');
echo json_encode($dashboardMenusJSON);