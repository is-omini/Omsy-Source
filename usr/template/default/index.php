<link href="https://fonts.googleapis.com/css2?family=Mulish:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
<style>
body {
	font-family: "Mulish", "Arial", sans-serif;
	font-weight: 900;

	background: #000;
	color: #fff;
}

body span.img {
	opacity: 0.0;

	background-image: url('<?= CMS->Plugins->defaults->getWallpapers() ?>');
	background-repeat: no-repeat;
	background-size: cover;
	display: block;

	width: 100%;
	height: 100%;

	position: fixed;
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;
}

body.loaded span.img {
	opacity: 1.0;
	background-position: center;
	transition: 10s;
}

@keyframes welcome {
	from { transform: scale(0.9); opacity: 1.0; }
	60% { transform: scale(1.0); opacity: 1.0; }
	to { transform: scale(0.9); opacity: 0.0; }
}

@keyframes loos {
	from { transform: rotate(0); }
	to { transform: rotate(360deg); }
}
.home {
	display: flex;
	align-items: center;
	justify-content: center;

	position: fixed;
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;
}

.home h1 {
	animation: welcome 1.5s infinite;

	font-size: 64px;

	text-shadow: 0px 0px 8px rgba(0, 0, 0, .2);
}

.home span.loading {
	display: block;

	width: 32px;
	height: 32px;
	border-radius: 100%;
	border-top: solid 4px #fff;
	border-left: solid 4px #fff;
	border-right: solid 4px transparent;
	border-bottom: solid 4px #fff;

	margin: 0 auto;
	margin-top: 32px;

	animation: loos 2s linear infinite;
}
</style>
<span class="img"></span>
<div class="home">
	<div>
		<h1 id="welcome"></h1>
		<span class="loading"></span>
	</div>
</div>
<script>
const welcomeFun = () => {
	if(i > (welcome.length-1)) i = 0
	if(i > 1) document.body.classList.add('loaded') 
	document.getElementById('welcome').textContent = welcomes[i]
	i++
}

const welcomes = <?= json_encode(CMS->Plugins->defaults->getWelcomes()) ?>;
let i = 0;
setInterval(() => {
	welcomeFun()
}, 1500)
welcomeFun()
</script>