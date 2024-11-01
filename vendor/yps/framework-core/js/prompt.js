jQuery(document).ready(function($){
    
    YPS_Framework = window.YPS_Framework || {};
        
    YPS_Framework.Prompt = function(){

        $(document).on("click", "[data-prompt-message]", function(e){
            var message     = $(this).attr('data-prompt-message');
            var ret         = confirm(message);
            
            if(ret == false){
                e.preventDefault();
            }
        });

    };

});
