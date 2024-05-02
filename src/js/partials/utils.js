'use strict';

// Retrieve URL parameters by requested parameter name.
export function getParameterByName(name, url) {
	if (!url) url = window.location.href;
	name = name.replace(/[\[\]]/g, "\\$&");
	const regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
		results = regex.exec(url);
	if (!results) return null;
	if (!results[2]) return '';
	return decodeURIComponent(results[2].replace(/\+/g, " "));
}

// Split, translate cyrillic characters, and then re-join the final result.
export function transliterate(word) {
	return word.split('').map(function (char) {
		return cyrillic[char] || char;
	}).join("");
}

//Character encode special characters.
export function htmlEncode(str) {
	return String(str).replace(/[^-\w. ]/gi, function (c) {
		return '&#' + c.charCodeAt(0) + ';';
	});
}

// Constructs miniture versions of uploaded media for admnin menu icon usage,
// or displays the rendered dashicon.
export function composePreviewContent(value) {
	const re = /(http|https):\/\/[\w-]+(\.[\w-]+)+([\w.,@?^=%&:\/~+#-]*[\w@?^=%&\/~+#-])?/;
	const isURL = re.test(value);

	if (!value) {
		return '';
	} else if (0 === value.indexOf('dashicons-')) {
		const dashDiv = document.createElement('div');
		dashDiv.classList.add('dashicons-before');
		dashDiv.innerHTML = '<br/>';
		dashDiv.classList.add(htmlEncode(value));
		return dashDiv;
	} else if (isURL) {
		const imgsrc = encodeURI(value);
		const theimg = document.createElement('IMG');
		theimg.src = imgsrc;
		return theimg;
	}
}

// Replace diacritic characters with latin characters.
export function replaceDiacritics(s) {
	const diacritics = [
		/[\300-\306]/g, /[\340-\346]/g,  // A, a
		/[\310-\313]/g, /[\350-\353]/g,  // E, e
		/[\314-\317]/g, /[\354-\357]/g,  // I, i
		/[\322-\330]/g, /[\362-\370]/g,  // O, o
		/[\331-\334]/g, /[\371-\374]/g,  // U, u
		/[\321]/g, /[\361]/g, // N, n
		/[\307]/g, /[\347]/g  // C, c
	];

	let chars = ['A', 'a', 'E', 'e', 'I', 'i', 'O', 'o', 'U', 'u', 'N', 'n', 'C', 'c'];

	for (let i = 0; i < diacritics.length; i++) {
		s = s.replace(diacritics[i], chars[i]);
	}

	return s;
}

// Converts non-alphanumeric or space characters to an underscore. Should ignore dashes, to allow
// using dashes in slugs.
export function replaceSpecialCharacters(s) {
	s = s.replace(/[^a-z0-9\s-]/gi, '_');
	return s;
}

// List of available cyrillic characters and the value to translate to.
export const cyrillic = {
	"Ё": "YO",
	"Й": "I",
	"Ц": "TS",
	"У": "U",
	"К": "K",
	"Е": "E",
	"Н": "N",
	"Г": "G",
	"Ш": "SH",
	"Щ": "SCH",
	"З": "Z",
	"Х": "H",
	"Ъ": "'",
	"ё": "yo",
	"й": "i",
	"ц": "ts",
	"у": "u",
	"к": "k",
	"е": "e",
	"н": "n",
	"г": "g",
	"ш": "sh",
	"щ": "sch",
	"з": "z",
	"х": "h",
	"ъ": "'",
	"Ф": "F",
	"Ы": "I",
	"В": "V",
	"А": "a",
	"П": "P",
	"Р": "R",
	"О": "O",
	"Л": "L",
	"Д": "D",
	"Ж": "ZH",
	"Э": "E",
	"ф": "f",
	"ы": "i",
	"в": "v",
	"а": "a",
	"п": "p",
	"р": "r",
	"о": "o",
	"л": "l",
	"д": "d",
	"ж": "zh",
	"э": "e",
	"Я": "Ya",
	"Ч": "CH",
	"С": "S",
	"М": "M",
	"И": "I",
	"Т": "T",
	"Ь": "'",
	"Б": "B",
	"Ю": "YU",
	"я": "ya",
	"ч": "ch",
	"с": "s",
	"м": "m",
	"и": "i",
	"т": "t",
	"ь": "'",
	"б": "b",
	"ю": "yu"
};
