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

		_.defined = false;

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

	};

	GoogleMaps.prototype.initialize = function() {
		var _ = this;
		var nodes = document.querySelectorAll(this.selector);

		if(typeof (google) === 'undefined' || typeof (google.maps) === 'undefined') {
			return;
		}
		_.defined = true;

		nodes.forEach(function(node, index) {
			if(typeof(google) !== 'undefined' && typeof(google.maps) !== 'undefined') {
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
			}
		});
	};

	/**
	 * Fuegt einen neuen Marker-Typ hinzu, der spaeter durch addMarker genutzt werden kann
	 *
	 * @see: https://stackoverflow.com/questions/20414387/google-maps-svg-marker-doesnt-display-on-ie-11#answer-40770331
	 * @param {string} name
	 * @param {string} type
	 * @param {object} options
	 */
	GoogleMaps.prototype.addMarkerType = function(name, type, options) {
		if(this.defined === true) {
			if(type === this.MARKER_TYPE_SIMPLE) {
				this.markerTypes[name] = {
					type: this.MARKER_TYPE_SIMPLE,
					url: options.url
				};

				if(typeof(options.scaledSize) !== 'undefined') {
					this.markerTypes[name].scaledSize = new google.maps.Size(options.scaledSize[0], options.scaledSize[1]);
				}
			}
		}
	};

	/**
	 * Fuegt einen Marker an angebener Position auf den Karten ein. Der Marker-Typ muss zuvor ueber addMarkerType
	 * hinzugefuegt worden sein
	 *
	 * @see: https://developers.google.com/maps/documentation/javascript/examples/icon-simple
	 * @see: https://developers.google.com/maps/documentation/javascript/examples/icon-complex
	 * @see: https://stackoverflow.com/questions/20414387/google-maps-svg-marker-doesnt-display-on-ie-11#answer-40770331
	 * @param {string} type
	 * @param {object} position
	 */
	GoogleMaps.prototype.addMarker = function(type, position) {
		var _ = this;

		if(this.defined === true) {
			_.maps.forEach(function(map) {
				if(_.markerTypes[type].type === _.MARKER_TYPE_SIMPLE) {
					var options = {
						position: {
							lat: position.latitude,
							lng: position.longitude
						},
						map: map,
						optimized: false,
						icon: _.markerTypes[type]
					};

					var marker = new google.maps.Marker(options);
				}
			});
		}
	};

	return GoogleMaps;
}));