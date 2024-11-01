jQuery(document).ready(function($){
    
    YPS_Framework = window.YPS_Framework || {};
    
    YPS_Framework.String = function(){
        
        this.pad_left       = function(string, pad, length){
            return (new Array(length+1).join(pad)+string).slice(-length);
        };
        
    };

});


