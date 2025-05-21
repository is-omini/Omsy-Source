<?= omsp_header() ?>
<section class="appUiKit-user-top-menu">
	<div class="head">
		<h1>Liste des comptes</h1>
	</div>
	<div class="content">
		<ul>
			<li><a class="active" href="/panel/getter?plugin=omsyPanel&page=/template" data-update="embed">Home</a></li>
		</ul>
	</div>
</section>
<section class="appUiKit-table app-kit-main-padding">
	<table>
		<tr class="">
			<td></td>
			<td><b>Name</b></td>
			<td><b>Role</b></td>
			<td><b>Date d'inscription</b></td>
			<td><b>Dernier connection</b></td>
		</tr>
		<?php foreach (CMS->DataBase->execute('SELECT * FROM omsy_account ORDER BY ID DESC')->fetchAll() as $key => $value): ?>
		<?php
		$getLogin = CMS->DataBase->execute('SELECT * FROM omsy_account_login WHERE user_id = ?', [$value['uniqid']])->fetchAll();
		?>
		<tr>
            <td>
                <input type="checkbox" name="">
            </td>
			<td><?= $value['username'] ?></td>
			<td><?= CMS->omsyRole->numberToString($value['role']) ?></td>
			<td><?= CMS->GFunction->convertirDate($value['register_date']) ?></td>
			<td><?= CMS->GFunction->convertirDate($getLogin[0]['reg_date'] ?? null) ?></td>
		</tr>
		<?php endforeach; ?>
	</table>
</section>
<?= omsp_footer() ?>