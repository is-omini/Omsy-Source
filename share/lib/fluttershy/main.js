class Fluttershy {
	constructor(objectData) {
		this.playerContenaire = objectData.playerContenaire;

		this.icon.play = objectData.icon.play;
		this.icon.pause = objectData.icon.pause;

		this.player = this.playerContenaire.querySelector('#player');
		if(this.player.nodeName !== 'VIDEO') return

		this.progressBar = this.playerContenaire.querySelector('.progress-bar');
		this.currentTimeEl = this.progressBar.querySelector('.currenttime');
		this.buffTimeEl = this.progressBar.querySelector('.bufftime');

		this.stringTimeContenaire = this.playerContenaire.querySelector('.item-string');
		this.stringTimeCurrent = this.stringTimeContenaire.querySelector('.currentTime');
		this.stringTimeDuration = this.stringTimeContenaire.querySelector('.durationTime');

		this.controlsButtoPlay = this.playerContenaire.querySelector('#toggle-play');
		this.controlsButtoFullscreen = this.playerContenaire.querySelector('#toggle-fullscreen');

		// 🔧 Corrige le binding de contexte
		this.updateProgress = this.updateProgress.bind(this);
		this.updateBuffer = this.updateBuffer.bind(this);
		this.playPause = this.playPause.bind(this);

		this.load();
	}
	
	updateProgress() {
		const percent = (this.player.currentTime / this.player.duration) * 100;
		this.currentTimeEl.style.width = `${percent}%`;

		this.stringTimeCurrent.textContent = this.formatTime(this.player.currentTime)
		this.stringTimeDuration.textContent = this.formatTime(this.player.duration)
	}
	updateBuffer() {
		if (this.player.buffered.length) {
			const bufferedEnd = this.player.buffered.end(this.player.buffered.length - 1);
			const percent = (bufferedEnd / this.player.duration) * 100;
			this.buffTimeEl.style.width = `${percent}%`;
		}
	}

	formatTime(seconds) {
		seconds = Math.floor(seconds);
		const h = Math.floor(seconds / 3600);
		const m = Math.floor((seconds % 3600) / 60);
		const s = seconds % 60;

		const pad = (n) => n.toString().padStart(2, '0');

		if (h > 0) {
			return `${h}:${pad(m)}:${pad(s)}`; // Format hh:mm:ss
		} else {
			return `${m}:${pad(s)}`; // Format mm:ss
		}
	}

	load() {
		console.log(this.player)
		if(this.player.nodeName == 'VIDEO') {
			this.loadEvent()
		}
	}

	loadEvent() {
		this.playerContenaire.addEventListener('click', (e) => {
			console.log(e.target.nodeName)
			if(
				e.target.nodeName == 'BUTTON'
				||
				e.target.nodeName == 'DIV'
			) return
			this.playPause()
		})

		this.player.addEventListener('timeupdate', this.updateProgress);
		this.player.addEventListener('progress', this.updateBuffer);

		// Gérer le clic sur la barre de progression
		this.progressBar.addEventListener('click', (e) => {
			const rect = this.progressBar.getBoundingClientRect();
			const clickX = e.clientX - rect.left;
			const percent = clickX / rect.width;
			this.player.currentTime = percent * this.player.duration;
		});
	}

	playPause() {
		if(this.player.paused) {
			this.player.play()
			this.controlsButtoPlay.innerHTML = this.icon.pause
		 } else {
		 	this.player.pause()
			this.controlsButtoPlay.innerHTML = this.icon.play
		 }
	}

	icon = {}
}