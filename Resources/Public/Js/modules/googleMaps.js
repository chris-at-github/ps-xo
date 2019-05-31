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
		var _ = this;

		_.selector = selector;

		_.defaults = {
			zoom: 10,
			center: {
				latitude: 0.0,
				longitude: 0.0
			},
			controls: {
				zoom: true,
				mapType: false,
				scale: true,
				streetView: false,
				rotate: false,
				fullscreen: false
			}
		};

		_.options = Object.assign(_.defaults, options);

		_.initialize();
	};

	GoogleMaps.prototype.initialize = function() {
		var _ = this;
		var nodes = document.querySelectorAll(this.selector);

		nodes.forEach(function(node) {
			var map = new google.maps.Map(node, {
				center: {
					lat: _.options.center.latitude,
					lng: _.options.center.longitude
				},
				zoom: _.options.zoom,
				zoomControl: _.options.controls.zoom,
				mapTypeControl: _.options.controls.mapType,
				scaleControl: _.options.controls.scale,
				streetViewControl: _.options.controls.streetView,
				rotateControl: _.options.controls.rotate,
				fullscreenControl: _.options.controls.fullscreen
			});
		});
	}

	return GoogleMaps;
}));