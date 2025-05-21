<section class="appUiKit-user-top-menu">
    <div class="head">
        <h1>Liste des chaine</h1>
    </div>
    <div class="content">
        <ul>
            <li><a class="active" href="/panel/getter?plugin=MinBlog&page=playlists" data-update="embed">Liste des playlist</a></li>
            <li><a href="/panel/getter?plugin=MinBlog&page=playlist" data-update="embed">Ajouter une playlist</a></li>
        </ul>
    </div>
</section>
<section class="appUiKit-table">
    <table>
        <tr class="">
            <td><b>Name</b></td>
            <td><b>Posts</b></td>
            <td></td>
        </tr>
		<?php foreach ($getAllPosts as $key => $value): ?>
        <tr>
            <td><b><a data-update="embed" href="/panel/getter?plugin=MinBlog&page=playlist&ID=<?= $value['ID'] ?>"><?= CMS->GFunction->htmlDecode($value['name']) ?></a></b></td>
            <td>
                <?= count(CMS->DataBase->execute('SELECT * FROM blog_playlist_content WHERE playlist_id = ?', [$value['ID']])->fetchAll()) ?>
            </td>
            <td>
                <ul>
                    <li>
						<a class="button-ui-svg" href="/panel/getter?plugin=MinBlog&page=playlist&ID=<?= $value['ID'] ?>" data-update="embed" class="title-stream">
							<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#5f6368"><path d="M255.38-175.38q-23.65 0-46.79-8.16-23.13-8.15-40.9-22.61 19.85-13.08 33.77-35.12 13.92-22.04 13.92-54.11 0-33.85 23.08-56.93 23.08-23.07 56.92-23.07 33.85 0 56.93 23.07 23.07 23.08 23.07 56.93 0 49.5-35.25 84.75t-84.75 35.25Zm0-40q33 0 56.5-23.5t23.5-56.5q0-17-11.5-28.5t-28.5-11.5q-17 0-28.5 11.5t-11.5 28.5q0 23-5.5 42t-14.5 36q5 2 10 2h10ZM450-380l-68.46-68.46 327.23-327.23q11-11 27.5-11.5t28.5 11.5l12.46 12.46q12 12 12 28t-12 28L450-380Zm-154.62 84.62Z"/></svg>
						</a>
					</li>
					<li>
						<a class="button-ui-svg" target="_blank" href="/panel/getter?plugin=ennNode&page=rm" class="title-stream">
							<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#5f6368"><path d="M304.62-160q-26.85 0-45.74-18.88Q240-197.77 240-224.62V-720h-40v-40h160v-30.77h240V-760h160v40h-40v495.38q0 27.62-18.5 46.12Q683-160 655.38-160H304.62ZM680-720H280v495.38q0 10.77 6.92 17.7 6.93 6.92 17.7 6.92h350.76q9.24 0 16.93-7.69 7.69-7.69 7.69-16.93V-720ZM392.31-280h40v-360h-40v360Zm135.38 0h40v-360h-40v360ZM280-720v520-520Z"/></svg>
						</a>
					</li>
                </ul>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</section>