<?php
$getAllPosts = [];
foreach (CMS->DataBase->execute('SELECT * FROM blog_single')->fetchAll() as $value) {
	$getAllPosts[$value['ID']] = $value;
}

if(isset($_GET['ID'])) {
	$getPlaylistInfo = CMS->DataBase->execute('SELECT * FROM blog_playlist WHERE ID= ?', [$_GET['ID']])->fetchAll()[0] ?? [];

	foreach (CMS->DataBase->execute('SELECT * FROM blog_playlist_content WHERE playlist_id = ?', [$getPlaylistInfo['ID']])->fetchAll() as $value) {

	$getAllPosts[$value['post_id']]['checked'] = true;
	}
}

/*
*/

include "interface/playlist.php";