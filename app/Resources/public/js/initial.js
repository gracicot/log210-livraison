(function ( $ ) {
    $.fn.initial = function() {
    	var self = this;
    	var element = function (selector) {
    		return self.find(selector);
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
