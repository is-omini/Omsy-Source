<?= omsp_header() ?>
<?php
$cacheSize = CMS->DataBase->execute('SELECT * FROM cachor_page')->fetchAll();
$accounts = CMS->DataBase->execute('SELECT ID, username, register_date FROM omsy_account ORDER BY ID DESC')->fetchAll();
$accountData = CMS->DataBase->execute('SELECT ID FROM omsy_account_obj')->fetchAll();
$accountDataLogin = CMS->DataBase->execute('SELECT * FROM omsy_account_login ORDER BY ID DESC')->fetchAll();

$newBuff = [];
foreach ($accountDataLogin as $key => $value) {
	if(isset($newBuff[$value['user_id']])) continue;
	$value['last_date'] = $value['reg_date'];
	$newBuff[$value['user_id']] = $value;
}
?>
<section class="appUiKit-user-top-menu">
    <div class="head">
        <h1>Données analytiques</h1>
    </div>
    <div class="content">
        <ul>
            <li><a class="active" href="/panel/getter?plugin=omsyPanel&page=/template" data-update="embed">Home</a></li>
        </ul>
    </div>
</section>
<section class="grid-global-stat app-kit-main-padding">
	<div class="appUiKit-block item-global-stat">
		<div class="content">
			<span class="title">Session</span>
			<span class="count" id="total-session">0</span>
		</div>
	</div>
	<div class="appUiKit-block item-global-stat">
		<div class="content">
			<span class="title">Visiteurs 7jours</span>
			<span class="count" id="total-count">0</span>
		</div>
	</div>
	<div class="appUiKit-block item-global-stat">
		<div class="content">
			<span class="title">Accounts</span>
			<span class="count"><?= count($accounts) ?></span>
		</div>
	</div>
	<div class="appUiKit-block item-global-stat">
		<div class="content">
			<span class="title">Accounts data</span>
			<span class="count"><?= count($accountData)+count($accountDataLogin) ?></span>
		</div>
	</div>
	<div class="appUiKit-block item-global-stat">
		<div class="content">
			<span class="title">Cache</span>
			<span class="count"><?= round(strlen(serialize($cacheSize)) / 1024, 2) ?> Ko</span>
		</div>
	</div>
	<div class="appUiKit-block item-global-stat item-button">
		<div class="content">
			<a href="" class="button-icon">
				<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M160.48-155.22v-83.58h102.11l-12.89-11.61q-52.24-47.44-73.96-107.16-21.72-59.71-21.72-120.43 0-114.83 70.21-203.6 70.2-88.77 181.75-115.81v95q-72.24 23.84-116.6 85.87-44.36 62.02-44.36 138.54 0 43.57 16.64 84.99 16.64 41.42 51.69 76.94l8.08 8.09V-400h83.59v244.78H160.48Zm393.54-7.37v-95q72.24-23.84 116.6-85.87 44.36-62.02 44.36-138.54 0-43.57-16.64-84.99-16.64-41.42-51.69-76.94l-8.08-8.09V-560h-83.59v-244.78h244.54v83.58H697.41l12.89 11.61q49.72 49.96 72.7 108.42 22.98 58.45 22.98 119.17 0 114.83-70.21 203.6-70.2 88.77-181.75 115.81Z"/></svg>
			</a>
		</div>
	</div>
</section>
<section class="grid-analytique app-kit-main-padding">
	<section class="appUiKit-block block-analytiques">
		<div class="head">
			<div class="left">
				<h2>Données analytiques</h2>
				<p>Données de visiteurs sur 7jours</p>
			</div>
		</div>
		<div class="content">
			<div class="list-viewpage">
				<ul id="stat-li"></ul>
			</div>
		</div>
	</section>
	<section class="appUiKit-block block-analytiques">
		<div class="head">
			<div class="left">
				<h2>Page par session</h2>
			</div>
		</div>
		<div class="content">
			<div class="list-viewpage">
				<ul id="first-watch-page"></ul>
			</div>
		</div>
	</section>
</section>
<section class="grid-analytique app-kit-main-padding">
	<section class="appUiKit-block block-analytiques">
		<div class="head">
			<div class="left">
				<h2>Dernier inscription</h2>
			</div>
		</div>
		<div class="content">
			<div class="list-viewpage">
				<ul>
					<?php for($i = 0; $i < 5; $i++): ?>
					<?php
					$value = $accounts[$i];
					?>
					<li class="list-stat-flex-space">
						<span class="username"><?= $value['username'] ?></span>
						<span class="username"><?= CMS->GFunction->convertirDate($value['register_date']) ?></span>
					</li>
					<?php endfor; ?>
				</ul>
			</div>
		</div>
	</section>
	<section class="appUiKit-block block-analytiques">
		<div class="head">
			<div class="left">
				<h2>Dernier connection</h2>
			</div>
		</div>
		<div class="content">
			<div class="list-viewpage">
				<ul>
					<?php
					$i=0;
					foreach($newBuff as $value): ?>
					<?php
					if($i == 5) continue;
					$user = CMS->DataBase->execute('SELECT * FROM omsy_account WHERE uniqid = ?', [$value['user_id']])->fetchAll();
					if(!isset($user[0])) continue;
					$user = $user[0];

					$i++;
					?>
					<li class="list-stat-flex-space">
						<span class="username"><?= $user['username'] ?></span>
						<span class="username"><?= CMS->GFunction->convertirDate($value['last_date']) ?></span>
					</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
	</section>
</section>
<?= omsp_footer() ?>
<script type="text/javascript">
omsyStat.request(null)
/* omsyStat.request((res) => {
	document.getElementById('total-session').textContent = res.total_session
}) */
</script>