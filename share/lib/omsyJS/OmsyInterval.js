const omsyInterval = new class OmsyTimeInterval {
	intervalArrayCurrent = {}
	intervalRequestArrayCurrent = {}

	setRequestInterval(url, name, callback, object = {}) {
		this.intervalRequestArrayCurrent[name] = {
			url: url,
			function: callback,
			object: object
		}
	}

	loadFunction() {
		Object.keys(this.intervalArrayCurrent).forEach((name) => {
			this.intervalArrayCurrent[name].function()
		})
	}

	loadInterval() {
		/* Object.keys(this.intervalArrayCurrent).forEach((name) => {
			this.intervalArrayCurrent[name].function()
		}) */
		let i = 0
		const max = Object.keys(this.intervalRequestArrayCurrent).length
		if(max > 0)
			document.querySelector('body').classList.add('load-fetch')
			
		Object.keys(this.intervalRequestArrayCurrent).forEach((name) => {
		    console.log(this.intervalRequestArrayCurrent)
		    
			console.log(this.intervalRequestArrayCurrent[name].url, name)
			request(
				this.intervalRequestArrayCurrent[name].url,
				this.intervalRequestArrayCurrent[name].object,
				true
			)
			.then((res) => {
				if(document.getElementById('lastRequestAction'))
					document.getElementById('lastRequestAction').textContent = 'Requested : '+this.intervalRequestArrayCurrent[name].url
				i++
				
				if(max == i) {
					document.querySelector('body').classList.remove('load-fetch')
					setTimeout(() => { this.loadInterval() }, 10000)
				}

				this.intervalRequestArrayCurrent[name].function(res)
			})	
			console.log(max, i)	
		})
	}

	reloadInterval() {
		Object.keys(this.intervalArrayCurrent).forEach((name) => {
			console.log(`Reloading interval: ${name}`)
			// Example logic: this.intervalArrayCurrent[name].function()
		})
	}

	setInterval(name, callback,windowLoad = false) {
		this.intervalArrayCurrent[name] = {
			function: callback,
			isLoad: windowLoad
		}
	}

	removeInterval(name) {
		delete this.intervalArrayCurrent[name]
	}
}
