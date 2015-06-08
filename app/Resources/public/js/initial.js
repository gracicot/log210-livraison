(function ( $ ) {
    $.fn.initial = function() {
    	var self = this;

    	var element = function (selector) {
            if (self.is('[ng-controller] [ng-controller] *')) {
    		    return self.find(selector + ':not([ng-controller]):not([ng-controller] *)').addBack(selector);
            } else {
                return self.find(selector).addBack(selector);
            }
    	};

    	element('[data-href]').linkify();
    	element('[data-toggle="tooltip"]').tooltip();
    	element('textarea').each(function () {
			this.setAttribute('style', 'height:' + (this.scrollHeight) + 'px;overflow-y:hidden;');
		}).on('input', function () {
			this.style.height = 'auto';
			this.style.height = (this.scrollHeight) + 'px';
		});
    };
}( jQuery ));
