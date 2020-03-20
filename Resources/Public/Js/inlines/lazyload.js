document.addEventListener('DOMContentLoaded', function() {
	yall({
		observeChanges: true,
		lazyBackgroundClass: 'lazy-background',
		lazyBackgroundLoaded: 'lazy-background-loaded',
		events: {
			// The object key is sent as the first argument to `addEventListener`,
			// which is the event. The corresponding value can be the callback if you
			// don't want to send any options to `addEventListener`.
			load: function(event) {
				if(event.target.classList.contains('lazy') === true && event.target.nodeName == 'IMG') {
					event.target.classList.add('lazy-loaded');
				}

				console.log(event.target.nodeName);
			}
		}
	});
});


window.lazySizesConfig = window.lazySizesConfig || {};

window.lazySizesConfig.lazyClass = 'lazy';
window.lazySizesConfig.loadingClass = 'lazy--loading';
window.lazySizesConfig.loadedClass = 'lazy--loaded';

document.addEventListener('lazybeforeunveil', function(event) {
	var background = event.target.getAttribute('data-background-src');

	if(background !== null) {
		event.target.style.backgroundImage = 'url(' + background + ')';
	}
});