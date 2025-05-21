<?php
if(isset($_POST['submit_form'])) {
	if(
		!empty($_POST['name']) &&
		!empty($_POST['group']) &&
		!empty($_POST['tags']) &&
		!empty($_POST['content'])
	) {
		$name = $_POST['name'];
		$group = $_POST['group'];
		$tags = $_POST['tags'];
		$content = $_POST['content'];
		$min = $_POST['min'];

		$slugName = CMS->GFunction->slugify($name);

		CMS->DataBase->execute(
			'INSERT INTO blog_single(blog_author, blog_group, blog_min, blog_title, blog_slug, blog_content, blog_tags, date_time) VALUES(?, ?, ?, ?, ?, ?, ?, now())',
			[$_SESSION['uniqid'], $group, $min, $name, $slugName, $content, $tags]
		);

		echo 'dddd';
	}

		echo 'dddd';
}

if(isset($_GET['ID'])) {
	$getBlog = CMS->DataBase->execute('SELECT * FROM blog_single WHERE ID = ?', [$_GET['ID']])->fetchAll();
	if(isset($getBlog[0])) $getBlog = $getBlog[0];
}

include "interface/post.php";