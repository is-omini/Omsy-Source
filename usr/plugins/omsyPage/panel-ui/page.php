<?php
$data = CMS->DataBase->execute('SELECT * FROM omsypage_page WHERE ID=?', [$_GET['id']])->fetchAll()[0] ?? [];

$allPage = CMS->DataBase->execute('SELECT * FROM omsypage_page')->fetchAll() ?? [];

if(isset($_POST['send-form'])) {
	echo 'dd';
	if(
		!empty($_POST['page-content']) &&
		!empty($_POST['title']) &&
		!empty($_POST['slug']) &&
		!empty($_POST['name']) &&
		!empty($_POST['parent'])
	) {
	echo 'dd';
		$content = $_POST['page-content'];
		$title = $_POST['title'];
		$slug = $_POST['slug'];
		$name = $_POST['name'];
		$parent = intval($_POST['parent']);
		echo count($data);
		if(count($data) > 0) {
			CMS->DataBase->execute('UPDATE omsypage_page SET page_parent = ?, page_name = ?, page_title = ?, page_slug = ?, page_content = ? WHERE ID = ?', [$parent, $name, $title, $slug, $content, $data['ID']]);
		} else {
			CMS->DataBase->execute('INSERT INTO omsypage_page(page_parent, page_name, page_title, page_slug, page_content, date_) VALUES(?, ?, ?, ?, ?, now())', [$parent, $name, $title, $slug, $content]);
		}
	}
}
?>
<form method="post">
	<section class="app-editor-code" id="app-contenaire">
		<section class="top-code">
			<textarea id="code-rendering" name="page-content"><?= CMS->GFunction->htmlDecode($data['page_content']) ?? '' ?></textarea>
		</section>
		<section class="head">
			<input type="text" name="send-form" value="ddd" hidden>
			<button id="refreshVisual">Visual</button>
			<div class="form-meta-page">
				<div class="appKitUi-input appUiKit-marginTop">
					<label>Titre</label>
					<input type="text" name="title" value="<?= $data['page_title'] ?? 'a' ?>">
				</div>
				<div class="appKitUi-input appUiKit-marginTop">
					<label>Slug</label>
					<input type="text" name="slug" value="<?= $data['page_slug'] ?? '' ?>">
				</div>
				<div class="appKitUi-input appUiKit-marginTop">
					<label>Nom</label>
					<input type="text" name="name" value="<?= $data['page_name'] ?? '' ?>">
				</div>
				<div class="appKitUi-input appUiKit-marginTop">
					<label>Parent</label>
					<select name="parent">
						<option value="0">Auccun</option>
						<?php foreach ($allPage as $key => $value): ?>
						<option value="<?= $value['ID'] ?>" <?php if($value['ID'] == $data['page_parent']) echo 'selected' ?> ><?= $value['page_name'] ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
		</section>
	</section>
</form>
<style type="text/css">
.app-editor-code {
	position: absolute;
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;
	margin-left: var(--header-width);

	height: 100%;

	display: grid;
	grid-template-columns: 1fr 360px;
}

.app-editor-code .head {
	padding: 16px;

	border-left: solid 1px #ccc;
}

.app-editor-code .head button {
	padding: 4px 8px;

	border: none;
}

.app-editor-code textarea {
	width: 100%;
	height: 100%;

	display: block;

	border: none;

	padding: 16px;

	resize: none;
}
</style>