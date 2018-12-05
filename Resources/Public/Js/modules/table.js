;(function(factory) {
	'use strict';
	if(typeof define === 'function' && define.amd) {
		define(['jquery'], factory);
	} else if(typeof exports !== 'undefined') {
		module.exports = factory(require('jquery'));
	} else {
		factory(jQuery);
	}
}(function($) {
	'use strict';

	let mobile = true;
	if($(window).width() >= 700) {
		mobile = false;
	}

	$('table').each(function() {
		let table = $(this);

		// Container erstellen
		table.wrap('<div class="table--container"></div>');
		let container = table.closest('.table--container');

		// Tabelle vorbereiten
		table.wrap('<div class="table--scroll-container"><div class="table--scroll"></div></div>');

		// Breiten auslesen und zwischenspeichern (fuer Wechsel zwischen Mobil und Desktop)
		let oTh = $('tbody th', table);
		oTh.each(function(i, v) {
			let th = $(this);

			// Original gesetzte Breite zwischenspeichern
			th.data('oWidth', null);
			if(v.style.width !== '') {
				th.data('oWidth', v.style.width);
			}
		});

		// Clone erstellen und aufbereiten (Wrappen, TDs entfernen, Breite setzen)
		let fixedWidth = 0;
		let fixed = $('<div>')
			.addClass('table--fixed')
			.append(table.clone());

		$('td', fixed).remove('td');

		// Hoehen der Zeilen angleichen
		$('tr', table).each(function(i, v) {
			let oTr = $(v);

			$('tr', fixed).eq(i).css('height', oTr.outerHeight());
		});

		// Clone hinzufuegen
		// (zur korrekten Breitenberechnung hier schon hinzufuegen)
		container.append(fixed);

		// Tabellenbreite auslesen und nach Abzug des Cellspacing (Rechts / Links) auf das umschließende Div setzen
		// -> damit Scrollbar nicht weiter geht als die Tabelle
		table.css('min-width', table.width());
		$('.table--scroll', container).css('width', table.width() - 40);

		// Desktop
		// Breite auslesen und setzen
		// Zum Berechnen reichen mir die THs aus der ersten Zeile (Position vom Letzten + Breite vom Letzten)
		if(mobile === false) {
			$('tbody tr:first-child th', table).each(function() {
				fixedWidth = $(this).position().left + $(this).outerWidth() + 20; // 20 = + Cellspacing 20
			});

			fixed.css('width', fixedWidth);

		// Mobile
		} else {
			// Nacheinander durchfuehren -> damit alle Breiten schon auf Auto stehen
			$('th', fixed).css('width', 'auto');
			$('th', fixed).each(function(i, th) {
				let cTh = $(th);

				//console.log(oTh.eq(i).data('oWidth'), i);

				// oTh.eq(i).css({
				// 	'width': 'auto',
				// 	'min-width': cTh.outerWidth()
				// });
				oTh.eq(i).css({
					'width': cTh.outerWidth(),
					'min-width': cTh.outerWidth()
				});
			});
		}
	});
}));