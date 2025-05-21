const OmsyEvent = {
	resetEvent() {
		for(const [key, value] of Object.entries(this.event)) {
			if(inArray(key, ['resize', 'scroll'])) {
				window.removeEventListener(key, (event) => {
					for(const [keys, values] of Object.entries(value)) {
						this.event[key][keys](event)
					}
				})
			}
		}
		for(const [key, value] of Object.entries(this.var)) {
			if(inArray(key, ['timeout'])) {
				for(const [keys, values] of Object.entries(value)) {
					clearTimeout(this.var[key][keys]);
				}
			}

			if(inArray(key, ['interval'])) {
				for(const [keys, values] of Object.entries(value)) {
					clearInterval(this.var[key][keys]);
				}
			}
		}
	},
	loadEvent() {
		this.resetEvent()

		for(const [key, value] of Object.entries(this.event)) {
			if(inArray(key, ['resize', 'load'])) {
				window.addEventListener(key, (event) => {
					for(const [keys, values] of Object.entries(value)) {
						this.event[key][keys](event)
					}
				})
			}
		}
	},
	event: {
		resize: [],
		load: [],
		scroll: []
	},
	var: {
		timeout: [],
		interval: []
	},
	function: {}
}