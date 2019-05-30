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

	var GoogleMaps = function(selector, options) {
		this.initialize();

		console.log(selector);
	};

	GoogleMaps.prototype.initialize = function() {
		console.log('GoogleMaps::initialize');
	}

	return GoogleMaps;
}));