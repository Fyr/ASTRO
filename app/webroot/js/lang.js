function setLang(lang) {
	if (typeof(lang) == 'undefined') {
		lang = 'rus';
	}

	$.cookie('lang', lang, {expires: 365, path: '/'});
	window.location.reload();
}

function getLang() {
	return $.cookie('lang');
}