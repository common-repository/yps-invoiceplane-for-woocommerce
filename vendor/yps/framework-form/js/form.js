YPS_Framework.Form      = class {

    constructor(){
        var $this       = this;

        setInterval(function(){
            $this.update_conditional_groups();
        }, 500);
    }

    /**
     * Display or hide conditional groups
     */
        update_conditional_groups(){

        jQuery.each(jQuery(".card[data-display-if-field-name]"), function(index, el){

            var display_if_field_name       = jQuery(this).data('display-if-field-name');

            if(display_if_field_name != ""){

                var display_if_field_values     = jQuery(this).data('display-if-field-values');
                var field                       = jQuery("[name='" + display_if_field_name + "']");

                if(YPS_Framework.Core.Array.in_array(jQuery(field).val(), display_if_field_values)){
                    jQuery(el).show();
                }else{
                    jQuery(el).hide();
                }

            }
            
        });
    }
};

jQuery(document).ready(function($){
    new YPS_Framework.Form();
});
