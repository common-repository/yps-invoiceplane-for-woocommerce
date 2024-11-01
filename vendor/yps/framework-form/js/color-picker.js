jQuery(document).ready(function($){
    
    YPS_Framework = window.YPS_Framework || {};
        
    YPS_Framework.Color_Picker = function(){

        var $this       = this;

        this.construct  = function(){
            $('.yps-color-picker').spectrum({
                type            : "component",
                showInput       : "true",
                showInitial     : "true"
            });
        };

        this.construct();
    };

    new YPS_Framework.Color_Picker();
    
});
