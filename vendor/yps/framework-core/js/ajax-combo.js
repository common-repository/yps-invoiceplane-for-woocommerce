jQuery(document).ready(function($){
    
    YPS_Framework = window.YPS_Framework || {};
    
    /**
     * Ajax Combo
     */
    YPS_Framework.Ajax_Combo = function(){

        $this                       = this;
        
        this.input_selectors        = {};
        this.output_selector        = null;
        this.ajax_data_url          = null;
        
        this.construct              = function(){

        };
        
        this.set_ajax_data_url      = function(ajax_data_url){
            $this.ajax_data_url      = ajax_data_url;
        };
        
        this.add_input_selector     = function(name, selector){
            
            this.input_selectors[name]  = {
                name        : name,
                selector    : selector
            };
            
        };
        
        this.set_output_selector    = function(selector){
            this.output_selector    = selector;
        };
        
        this.send_ajax              = function(name, selector, value){

            $($this.output_selector).css('opacity', '0.5');
            
            $.ajax({
                type:'POST',
                url: $this.ajax_data_url,
                data: {
                    name                   : name,
                    value                  : value
                },
                dataType: "json",

                success:function(data){
                    
                    $($this.output_selector).empty();
                    
                    $.each(data, function(value, label){
                        $($this.output_selector).append('<option value="' + value + '">' + label + '</option>');
                    });

                    $($this.output_selector).css('opacity', '1.0');
                },

                error: function(data){
                    console.log(data);
                }
            });
        };

        this.attach     = function(){

            $.each($this.input_selectors, function(index, data){
                $(data.selector).on('change', function(){
                    $this.send_ajax(data.name, data.selector, $(this).val());
                });
            });
            

        };
        
        this.construct();
        
    };
    
    /* Init HTML ajax-combo classes */
    $.each($(".ajax-combo"), function(index, element){

        var ajax_combo      = new YPS_Framework.Ajax_Combo();

        ajax_combo.add_input_selector($(element).attr('name'), element);
        ajax_combo.set_ajax_data_url($(element).attr('data-ajax-url'));
        ajax_combo.set_output_selector($(element).attr('data-output-selector'));
        
        ajax_combo.attach();
    });
});
