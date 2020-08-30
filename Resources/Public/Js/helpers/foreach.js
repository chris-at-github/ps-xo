// @see: https://www.mediaevent.de/javascript/for-in-foreach.html
if(window.NodeList && !NodeList.prototype.forEach) {
	NodeList.prototype.forEach = function(callback, thisArg) {
		thisArg = thisArg || window;
		for(var i = 0; i < this.length; i ++) {
			callback.call(thisArg, this[i], i, this);
		}
	};
}