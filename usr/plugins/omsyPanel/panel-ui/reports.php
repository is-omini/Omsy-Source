<?php
if(isset($_GET['rm'])) {
	CMS->DataBase->execute('DELETE FROM omsyreport_report WHERE ID = ?', [$_GET['rm']]);

	header("Location: /panel/getter?plugin=omsyPanel&page=/reports");
}
?>
<?= omsp_header() ?>
<section class="appUiKit-table app-kit-main-padding">
	<table>
		<tr class="">
			<td></td>
			<td><b>User</b></td>
			<td><b>Page</b></td>
			<td><b>Tags</b></td>
			<td><b>Date du signalement</b></td>
		</tr>
		<?php foreach (CMS->DataBase->execute('SELECT * FROM  	omsyreport_report ORDER BY ID DESC')->fetchAll() as $key => $value): ?>
		<tr>
            <td>
                <input type="checkbox" name="">
            </td>
			<td><?= $value['user'] ?></td>
			<td><?= $value['page'] ?></td>
			<td><?= $value['tag'] ?></td>
			<td><?= CMS->GFunction->convertirDate($value['date_']) ?></td>
			<td>
				<a href="/panel/getter?plugin=omsyPanel&page=/reports&rm=<?= $value['ID'] ?>">
					remove
				</a>
			</td>
		</tr>
		<?php endforeach; ?>
	</table>
</section>
<?= omsp_footer() ?>