;(function(factory) {
	'use strict';
	if(typeof define === 'function' && define.amd) {
		define([], factory);
	} else if(typeof exports !== 'undefined') {
		module.exports = factory();
	} else {
		factory();
	}
}(function() {
	'use strict';

	var field = document.getElementById('form-validation-phone');
	var regex = new RegExp('[0-9]+', 'g');

	// @see: https://stackoverflow.com/a/10911454
	field.addEventListener('keypress', function(event) {
		console.log(event.srcElement.value);
		console.log(String.fromCharCode(event.charCode || event.keyCode));
		//event.preventDefault();
	});
}));