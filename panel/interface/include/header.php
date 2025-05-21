<?php
$dashboardApp = file_get_contents(__root__.'/panel/interface/config/apps.json');
$dashboardApp = json_decode($dashboardApp, true);
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Omsy Dashbaord</title>

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Sora:wght@100..800&display=swap" rel="stylesheet">

	<script>document.write('<script src="http://' + (location.host || 'localhost').split(':')[0] + ':35729/livereload.js?snipver=1"></' + 'script>')</script>

	<link rel="stylesheet" type="text/css" href="/panel/interface/css/app.kit.css">
	<link rel="stylesheet" type="text/css" href="/panel/interface/css/appUiKit.main.css">
	<link rel="stylesheet" type="text/css" href="/panel/interface/css/app.main.css">
	<link rel="stylesheet" type="text/css" href="/panel/interface/css/app.media.css">

	<link rel="stylesheet" type="text/css" href="/panel/interface/css/panelColorDef.css">
</head>
<body>
<header class="top-header">
	<ul>
		<li>
			<a onclick="document.querySelector('body').classList.toggle('left-header-open')">
				<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M140-254.62v-59.99h680v59.99H140ZM140-450v-60h680v60H140Zm0-195.39v-59.99h680v59.99H140Z"/></svg>
			</a>
		</li>
		<li>
			<a href="/" class="logo">
				<img src="/share/omsy-product/white_logo_lite.png" height="16px">
			</a>
		</li>
		<li>
			<a href="/">
				<span class="icon">
					<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm-40-82v-78q-33 0-56.5-23.5T360-320v-40L168-552q-3 18-5.5 36t-2.5 36q0 121 79.5 212T440-162Zm276-102q41-45 62.5-100.5T800-480q0-98-54.5-179T600-776v16q0 33-23.5 56.5T520-680h-80v80q0 17-11.5 28.5T400-560h-80v80h240q17 0 28.5 11.5T600-440v120h40q26 0 47 15.5t29 40.5Z"/></svg>
				</span>
				<span class="text">My website</span>
			</a>
		</li>
		<li>
			<a href="/stat">
				<span class="icon">
					<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="m798-322-62-62q44-41 69-97t25-119q0-63-25-118t-69-96l62-64q56 53 89 125t33 153q0 81-33 153t-89 125ZM670-450l-64-64q18-17 29-38.5t11-47.5q0-26-11-47.5T606-686l64-64q32 29 50 67.5t18 82.5q0 44-18 82.5T670-450Zm-310 10q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM40-120v-112q0-33 17-62t47-44q51-26 115-44t141-18q77 0 141 18t115 44q30 15 47 44t17 62v112H40Zm80-80h480v-32q0-11-5.5-20T580-266q-36-18-92.5-36T360-320q-71 0-127.5 18T140-266q-9 5-14.5 14t-5.5 20v32Zm240-320q33 0 56.5-23.5T440-600q0-33-23.5-56.5T360-680q-33 0-56.5 23.5T280-600q0 33 23.5 56.5T360-520Zm0-80Zm0 400Z"/></svg>
				</span>
				<span class="text" id="get-session">1</span>
			</a>
		</li>
	</ul>
	<ul>
		<li>
			<a href="/stat" class="fetching">
				<span class="icon">
					<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M160-160v-80h110l-16-14q-52-46-73-105t-21-119q0-111 66.5-197.5T400-790v84q-72 26-116 88.5T240-478q0 45 17 87.5t53 78.5l10 10v-98h80v240H160Zm400-10v-84q72-26 116-88.5T720-482q0-45-17-87.5T650-648l-10-10v98h-80v-240h240v80H690l16 14q49 49 71.5 106.5T800-482q0 111-66.5 197.5T560-170Z"/></svg>
				</span>
				<span class="text" id="get-session">Fetching</span>
			</a>
		</li>
		<li>
			<a href="#" class="avatar">
				<div class="text">
					<span class="username">Hey, <?= $_SESSION['username'] ?></span>
				</div>
				<img src="/panel/interface/image/avatar.jpg" height="40px" width="40px">
			</a>
		</li>
	</ul>
</header>

<header class="left-header">
	<section class="contenaire-menu-choice">
		<div class="menu-app">
			<ul>
				<?php foreach($dashboardApp as $value): ?>
				<li
					class="<?php if(isset($_GET['plugin']) && $_GET['plugin'] == $value['id']) echo 'open'; ?>"
				>
					<a
					  data-name="<?= $value['name'] ?>"
					  data-item-id="<?= $value['id'] ?>"
					  onclick="this.parentNode.classList.toggle('open')"
					>
						<div class="left">
							<span class="icon"><?= $value['icon'] ?></span>
							<span class="text"><?= $value['name'] ?></span>
						</div>
						<div class="icon-open">
							<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M480-373.85 303.85-550h352.3L480-373.85Z"/></svg>
						</div>
					</a>
					<ol class="sub_menu">
						<?php
						$getAppMenu = json_decode(@file_get_contents(__root__."/usr/plugins/{$value['id']}/panel-ui/navico.json") ?? '[]', true);
						foreach($getAppMenu ?? [] as $v):
						?>
						<li>
							<a href="getter?plugin=<?= $value['id'] ?>&page=<?= $v['url'] ?>">
								<span class="text"><?= $v['name'] ?></span>
							</a>
						</li>
						<?php endforeach; ?>
					</ol>
				</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</section>
	<div id="publishChangeButton-contenaire">
		<button id="publishChangeButton">Sauvegarder</button>
	</div>
	<section class="userinfo-admin">
		<div class="info">
			<div class="avatar">
				<img src="/panel/interface/image/avatar.jpg" width="40px" height="40px">
			</div>
			<div class="text">
				<span class="username"><?= $_SESSION['username'] ?></span>
				<span class="sub_username"><?= $_SESSION['username'] ?></span>
			</div>
		</div>
		<div class="option-user">
			<ul>
				<li>
					<a href="#">
						<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="m387.69-100-15.23-121.85q-16.07-5.38-32.96-15.07-16.88-9.7-30.19-20.77L196.46-210l-92.3-160 97.61-73.77q-1.38-8.92-1.96-17.92-.58-9-.58-17.93 0-8.53.58-17.34t1.96-19.27L104.16-590l92.3-159.23 112.46 47.31q14.47-11.46 30.89-20.96t32.27-15.27L387.69-860h184.62l15.23 122.23q18 6.54 32.57 15.27 14.58 8.73 29.43 20.58l114-47.31L855.84-590l-99.15 74.92q2.15 9.69 2.35 18.12.19 8.42.19 16.96 0 8.15-.39 16.58-.38 8.42-2.76 19.27L854.46-370l-92.31 160-112.61-48.08q-14.85 11.85-30.31 20.96-15.46 9.12-31.69 14.89L572.31-100H387.69ZM440-160h78.62L533-267.15q30.62-8 55.96-22.73 25.35-14.74 48.89-37.89L737.23-286l39.39-68-86.77-65.38q5-15.54 6.8-30.47 1.81-14.92 1.81-30.15 0-15.62-1.81-30.15-1.8-14.54-6.8-29.7L777.38-606 738-674l-100.54 42.38q-20.08-21.46-48.11-37.92-28.04-16.46-56.73-23.31L520-800h-79.38l-13.24 106.77q-30.61 7.23-56.53 22.15-25.93 14.93-49.47 38.46L222-674l-39.38 68L269-541.62q-5 14.24-7 29.62t-2 32.38q0 15.62 2 30.62 2 15 6.62 29.62l-86 65.38L222-286l99-42q22.77 23.38 48.69 38.31 25.93 14.92 57.31 22.92L440-160Zm40.46-200q49.92 0 84.96-35.04 35.04-35.04 35.04-84.96 0-49.92-35.04-84.96Q530.38-600 480.46-600q-50.54 0-85.27 35.04T360.46-480q0 49.92 34.73 84.96Q429.92-360 480.46-360ZM480-480Z"/></svg>
					</a>
				</li>
			</ul>
		</div>
	</section>
</header>
<main class="window-app">