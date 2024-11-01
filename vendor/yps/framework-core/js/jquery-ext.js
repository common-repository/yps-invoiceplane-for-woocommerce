jQuery(document).ready(function($){

    /**
     * Serialize complex data (For example a Form) to JSON
     * 
     * @returns array
     */
    (function ($) {

        $.fn.serializeToJson = function () {

            var o = {};
            var a = this.serializeArray();
            $.each(a, function () {
                if (o[this.name]) {
                    if (!o[this.name].push) {
                        o[this.name] = [o[this.name]];
                    }
                    o[this.name].push(this.value || '');
                } else {
                    o[this.name] = this.value || '';
                }
            });
            return o;
        };
        
        /**
         * Hide an option inside a dropdown field (<select ...)
         * Tested on:
         * IE 11, Edge, Firefox, Chrome
         */
        $.fn.hideOption = function() {
            //To hide elements
            this.each(function(index, val){
                if ($(this).is('option') && (!$(this).parent().is('span')))
                    $(this).wrap('<span>').hide();
            });
        };

        /**
         * Show an option inside a dropdown field (<select ...)
         * Tested on:
         * IE 11, Edge, Firefox, Chrome
         */
        $.fn.showOption = function() {
            //To show elements
            this.each(function(index, val) {
                if (this.nodeName.toUpperCase() === 'OPTION') {
                    var span = $(this).parent();
                    var opt = this;
                    if($(this).parent().is('span')) {
                        $(opt).show();
                        $(span).replaceWith(opt);
                    }
                }
            });

        };
        
    })(jQuery);
    
});
