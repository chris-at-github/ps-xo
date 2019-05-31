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

		_.maps = [];

		_.selector = selector;

		_.markerTypes = {};

		_.defaults = {
			zoom: 10,
			coordinates: {
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

	GoogleMaps.prototype.MARKER_TYPE_SIMPLE = 'simple';

	GoogleMaps.prototype.initialize = function() {
		var _ = this;
		var nodes = document.querySelectorAll(this.selector);

		nodes.forEach(function(node, index) {
			var map = new google.maps.Map(node, {
				center: {
					lat: _.options.coordinates.latitude,
					lng: _.options.coordinates.longitude
				},
				zoom: _.options.zoom,
				zoomControl: _.options.controls.zoom,
				mapTypeControl: _.options.controls.mapType,
				scaleControl: _.options.controls.scale,
				streetViewControl: _.options.controls.streetView,
				rotateControl: _.options.controls.rotate,
				fullscreenControl: _.options.controls.fullscreen
			});

			_.maps[index] = map;
		});
	};

	GoogleMaps.prototype.addMarkerType = function(name, type, options) {
		if(type === this.MARKER_TYPE_SIMPLE) {
			this.markerTypes[name] = {
				type: this.MARKER_TYPE_SIMPLE,
				url: options.url
			};
		}
	};

	/**
	 * Fuegt einen Marker an angebener Position auf den Karten ein. Der Marker-Typ muss zuvor ueber addMarkerType
	 * hinzugefuegt worden sein
	 *
	 * @see: https://developers.google.com/maps/documentation/javascript/examples/icon-simple
	 * @see: https://developers.google.com/maps/documentation/javascript/examples/icon-complex
	 * @param {string} type
	 * @param {object} position
	 */
	GoogleMaps.prototype.addMarker = function(type, position) {
		var _ = this;

		_.maps.forEach(function(map) {
			if(_.markerTypes[type].type === _.MARKER_TYPE_SIMPLE) {
				var marker = new google.maps.Marker({
					position: {
						lat: position.latitude,
						lng: position.longitude
					},
					map: map,
					icon: _.markerTypes[type].url
				});
			}
		});
	};

	return GoogleMaps;
}));