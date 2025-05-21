const subButtonMenu = document.querySelector('#bubul-button')

let activeSubmenu = ''
const loadSubmenu = (button) => {
	document.querySelector('.app-contenaire').classList.add('open-submenu')
	if(activeSubmenu === button.dataset.itemId) {
		document.querySelector('.app-contenaire').classList.remove('open-submenu')
		activeSubmenu = ''
		subButtonMenu.style.top = -100+'px'
		return
	}

	if(activeSubmenu !== button.dataset.itemId) activeSubmenu = button.dataset.itemId
 
	request(`${button.href}`, {}, true)
	.then((res) => {
		console.log(res)
		let html = ''
		document.getElementById('app-contenaire-all-plugin-buttons').innerHTML = ''
		res.forEach((i) => {
			html += `<li>
			  <a href="${i.url}" data-update="embed">
			    <span class="icon">${i.icon ?? ''}</span>
			    <span class="text">${i.name}</span>
			  </a>
			</li>`

			//document.getElementById('app-contenaire-all-plugin-buttons').appendChild(li)
		})
		document.getElementById('app-contenaire-all-plugin-buttons').innerHTML = html
		loadButtonNotLocation()
	})
	//console.log(cal, button.offsetTop, subButtonMenu.offsetHeight, subButtonMenu.offsetHeight)
}
let oldButton = null
const fetchedContentParser = (event, button) => {
	document.querySelector('.app-contenaire').classList.remove('open-submenu')
	activeSubmenu = ''

	if(button.dataset.update === 'submenu') {
		event.preventDefault()
		loadSubmenu(button)
		return
	}
}

const loadButtonNotLocation = () => {
	//return null;

	document.querySelectorAll('a').forEach((button) => {
		if(button.dataset.rooter == 'not') return
		if(button.classList.contains('event')) return
		button.classList.add('event')

		button.addEventListener('click', (event) => {fetchedContentParser(event, button)})
		if(button.classList.contains('button-header-menu')) {
			button.addEventListener('mouseover', () => {
				subButtonMenu.style.display = 'block'
				let cal = ((button.offsetHeight-subButtonMenu.offsetHeight)/2)+button.offsetTop

				subButtonMenu.style.top = cal+'px'
				subButtonMenu.textContent = button.dataset.name
			})
			button.addEventListener('mouseleave', () => {
				subButtonMenu.style.display = 'none'
			})
		}
	})
}

const loadPage = () => {
	loadButtonNotLocation()
}

const loadFormButton = () => {
	const btnFormSubmit_Contenaire = document.getElementById('publishChangeButton-contenaire')
	if(document.querySelector('form')) {
		btnFormSubmit_Contenaire.style.display = 'block'
		const btnFormSubmit = document.getElementById('publishChangeButton')
		btnFormSubmit.addEventListener('click', () => {
			document.querySelector('form').submit()
			console.log('load')
		})
	} else { btnFormSubmit_Contenaire.style.display = 'none' }

	console.log(document.querySelector('form'))
}

window.onload = () => {
	loadFormButton()
	loadButtonNotLocation()
}