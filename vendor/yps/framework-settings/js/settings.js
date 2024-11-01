jQuery(document).ready(function($){
    
    YPS_Framework = window.YPS_Framework || {};
    
    /**
     * Settings Form
     * @param {type} a
     * @returns {YPS_Framework.Settings_Form}
     */
    YPS_Framework.Settings_Form = function(){
        this.selector           = ".yps-settings-form";
        this.submitFormOpacity  = 0.5;
        
        /**
         * Attach the events to the "selector" forms
         * 
         * @returns void
         */
        this.attach             = function(){

            var selector            = this.selector;
            var submitFormOpacity   = this.submitFormOpacity;
            
            $.each($(selector), function(index, obj){
                
                $(obj).on("click", "[type='submit']", function(e){
                    e.preventDefault();

                    var ajaxDataUrl     = $(obj).attr('data-ajax-url');

                    $(obj).css('opacity', submitFormOpacity);
                    $(obj).find(".alert").hide();
                    
                    $.ajax({
                        type:'POST',
                        url: ajaxDataUrl,
                        data: {
                            form_data: $(obj).serializeJSON()
                        },
                        dataType: "html",
                        
                        success:function(data){
                            $(obj).html(data);
                            $(obj).css('opacity', '1.0');
                        },

                        error: function(data){
                            console.log(data);
                        }
                    });

                    return false;
                });
                
            });
        };

        /**
         * Change the form selector
         * 
         * @param string selector The selector
         * @returns void
         */
        this.set_selector       = function(selector){
            this.selector       = selector;
        };
        
        this.set_submit_form_opacity    = function(opacity){
            this.submitFormOpacity  = opacity;
        }

    };

    new YPS_Framework.Settings_Form().attach();
    
});
