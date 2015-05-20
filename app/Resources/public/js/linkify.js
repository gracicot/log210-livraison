(function ( $ ) {
    $.fn.linkify = function() {
    	var self = this;
    	var element = function (selector) {
    		return self.find(selector);
    	};
    	this.append('<a class="linkify-link" href="' + this.data('href') + '"></a>');
    };
}( jQuery ));
