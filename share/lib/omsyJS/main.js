var request = (url, data, json = false) => {
  let fo = null
  if(data)
    fo = {method: "POST", body: serverQuery(data)}
  
  //document.querySelector('body').classList.add('load-fetch')

  const f = fetch(url, fo)
  .then((res) => {
    if(json) return res.json()
    else return res.text()
  })
  .then((res) => {
    console.log('termined')
    setTimeout(() => {
      //document.querySelector('body').classList.remove('load-fetch')
      //console.log('termined')
    }, 1000)
    return res
  })
  .catch((error) => {
    console.error(error)
  })
  return f

  function serverQuery(obj) {
    const form = new FormData();
    Object.entries(obj).forEach(entry => {
      const [key, value] = entry;
      form.append(key, value)
    })
    return form
  }
}

function deleteCookie(name) {
  setCookie(name, "", {
    'max-age': -1
  })
}

function setCookie(name, value, options = {}) {

  options = {
    path: '/',
    // Ajoute d'autres valeurs par défaut si nécessaire
    ...options
  };

  if (options.expires instanceof Date) {
    options.expires = options.expires.toUTCString();
  }

  let updatedCookie = encodeURIComponent(name) + "=" + encodeURIComponent(value);

  for (let optionKey in options) {
    updatedCookie += "; " + optionKey;
    let optionValue = options[optionKey];
    if (optionValue !== true) {
      updatedCookie += "=" + optionValue;
    }
  }

  document.cookie = updatedCookie;
}

// Retourne le cookie correspondant au nom donné,
// ou undefined si non trouvé
function getCookie(name) {
  let matches = document.cookie.match(new RegExp(
    "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
  ));
  return matches ? decodeURIComponent(matches[1]) : undefined;
}