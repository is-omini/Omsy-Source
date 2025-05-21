<?php
define('__domaine__', 'https://127.0.0.1/');
//if(empty($_GET['plugin'])) die();
//if(empty($_GET['page'])) die();


$plugin = $_GET['plugin'] ?? '';
$page = $_GET['page'] ?? '';

$path = "/usr/plugins/$plugin/panel-ui/$page.php";
//if(!file_exists(__root__ . $path)) die();

$Path = $page;
$EmbedPanel = true;

function omsp_header() {
	if(!isset($_GET['notui'])) include "interface/include/header.php";
}

function omsp_footer() {
	if(!isset($_GET['notui'])) include "interface/include/footer.php";
}


include __root__ . $path;