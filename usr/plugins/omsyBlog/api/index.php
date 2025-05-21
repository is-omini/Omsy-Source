<?php
$allBlog = CMS->DataBase->execute(
	"SELECT * FROM omsyblog_single ORDER BY ID DESC LIMIT 4"
)->fetchAll();

$buff = [];
foreach ($allBlog as $key => $value) {
	$buff[] = [
		"slug" => $value['blog_slug'],
		"group" => $value['blog_group'],
		"title" => CMS->GFunction->htmlDecode($value['blog_title']),
		"sub_title" => CMS->GFunction->convertirDate($value['date_time']),
		"thumbnail" => $value['blog_min'],
		"publish_date" => $value['date_time']
	];
}

header('Content-Type: application/json');
die(json_encode($buff));