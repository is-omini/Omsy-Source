const omsyDate = new class omsPanel_Date {
	convertNumberToDayString(dateString) {
	    // Création d'un objet Date à partir de la chaîne de date
	    let date = new Date(dateString);
	    
	    // Tableau des jours de la semaine en français
	    let jours = ["Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi"];
	    
	    // Récupération du jour de la semaine
	    return jours[date.getDay()];
	}
}

const omsyStat = new class omsPanel_statistique {
	maxView = {number: 0, link: {}, date: ''}
	viewTotalWeek = 0
	maxViewLastDay = 0

	requestStat() {
		request('/api/stat', {}, true)
		.then((res) => {
			const statFromDays = res.days
			document.getElementById('total-count').textContent = '0'
			document.getElementById('stat-li').innerHTML = ''

			this.viewTotalWeek = 0

			Object.keys(statFromDays).forEach((value) => {
				if(this.maxView.number < statFromDays[value].number) this.maxView = statFromDays[value];
				this.viewTotalWeek+=statFromDays[value].number;
			})
		
			Object.keys(statFromDays).forEach((value) => {
				const valueDays = statFromDays[value]

				if(this.maxView.number < valueDays.number) this.maxView = valueDays
				this.interfaceBar(value, valueDays)
				this.maxViewLastDay = valueDays.number
			})
		})
	}

	request(callback) {
		omsyInterval.setRequestInterval('/api/session', 'session', (res) => {
			document.getElementById('total-session').textContent = res.total_session
			document.getElementById('get-session').textContent = res.total_session+' Session'

			document.getElementById('first-watch-page').innerHTML = ''
			res.list.forEach((e) => {
				document.getElementById('first-watch-page').insertAdjacentHTML('afterbegin',`<li class="list-stat-flex-space">
					<span>${e.page}</span>
					<span>${e.appareil}</span>
				</li>`)
			})
		})

		omsyInterval.setRequestInterval('/api/stat', 'stat', (res) => {
			const statFromDays = res.days
			document.getElementById('total-count').textContent = '0'
			document.getElementById('stat-li').innerHTML = ''

			this.viewTotalWeek = 0

			Object.keys(statFromDays).forEach((value) => {
				if(this.maxView.number < statFromDays[value].number) this.maxView = statFromDays[value];
				this.viewTotalWeek+=statFromDays[value].number;
			})
		
			Object.keys(statFromDays).forEach((value) => {
				const valueDays = statFromDays[value]

				if(this.maxView.number < valueDays.number) this.maxView = valueDays
				this.interfaceBar(value, valueDays)
				this.maxViewLastDay = valueDays.number
			})
		})

		/* omsyInterval.setInterval('sessionCounting', () => {
			request('/api/session', {}, true)
			.then((res) => {
				callback(res)
				document.getElementById('get-session').textContent = res.total_session+' Session'

				document.getElementById('first-watch-page').innerHTML = ''
				res.list.forEach((e) => {
					document.getElementById('first-watch-page').insertAdjacentHTML('afterbegin',`<li class="list-stat-flex-space">
						<span>${e.page}</span>
						<span>${e.appareil}</span>
					</li>`)
				})
			})
		})

		omsyInterval.setInterval('statLoad', () => {
			this.requestStat()
		}) */
	}

	interfaceBar(key, valueDays) {
		document.getElementById('total-count').textContent = `${this.viewTotalWeek}`

		const barSizeWidth = ((valueDays.number / this.viewTotalWeek) * 100).toFixed(2);

		// Class Gestion
		let classs = ''
		if(this.maxView.number === valueDays.number) classs = 'focus'
		document.getElementById('stat-li').innerHTML += `<li class="item-anatyique-data">
			<span class="name">
				${omsyDate.convertNumberToDayString(key)}
				<span style="color: #747474;">(${valueDays.number})</span>
			</span>
			<span class="progress-bar">
				<span class="content" style="width: ${barSizeWidth}%;"></span>
			</span>
		</li>`
	}

	getBestLink(max) {
		//if(maxRequest >= 3) return;

		let m = {link: '', number: 0};
		const liks = max.link
		Object.keys(liks).forEach((e) => {
			if(m.number < liks[e] && liks[e] > 1) {
				m = {number: liks[e], link: e}
			}
		})

		//maxRequest++;

		return m.link;
	}

	getSession(res) {
	  /* console.log(res)

	  const total = res.total_session
	  const barHeight = ((total / maxViewLastDay) * 100).toFixed(2);

	  document.getElementById('session-li').innerHTML = `<li class="item-anatyique-data">
	    <span class="name">Session Active sur ${maxViewLastDay} view <span style="color: #747474;">(${total})</span></span>
	    <span class="progress-bar">
	      <span class="content" style="width: ${barHeight}%;"></span>
	    </span>
	  </li>` */
	}
}