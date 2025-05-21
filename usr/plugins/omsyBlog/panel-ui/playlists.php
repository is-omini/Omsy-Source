<?php
$itemPage = 10;

$getAllPosts = CMS->DataBase->execute('SELECT * FROM blog_playlist ORDER BY ID DESC')->fetchAll();

$countChannelVideo = count($getAllPosts);
$pagesTotales = ceil($countChannelVideo/$itemPage);
if(isset($_GET['pp']) AND !empty($_GET['pp']) AND $_GET['pp'] > 0 AND $_GET['pp'] <= $pagesTotales) {
	$_GET['pp'] = intval($_GET['pp']);
	$pageCourante = $_GET['pp'];
} else {
	$pageCourante = 1;
}
$depart = ($pageCourante-1)*$itemPage;
$getAllPosts = CMS->DataBase->execute('SELECT * FROM blog_playlist ORDER BY ID DESC LIMIT '.$depart.','.$itemPage)->fetchAll();

include "interface/playlists.php";
?>