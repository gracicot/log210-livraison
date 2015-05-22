(function ( $ ) {
    $.fn.initial = function() {
    	var self = this;
    	var element = function (selector) {
    		return self.find(selector);
    	};

    	element('[data-href]').linkify();
    	element('[data-toggle="tooltip"]').tooltip();
    };
}( jQuery ));
