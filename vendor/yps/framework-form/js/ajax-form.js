/**
 * Settings Form
 * @param {type} a
 * @returns {YPS_Framework.Settings_Form}
 */
YPS_Framework.Ajax_Form = class {

    $                      = null;
    selector               = null;
    submit_form_opacity    = 0.5;
    
    constructor($, selector){

        var $this                   = this;
        this.selector               = selector;        
        this.$                      = $;

        $(document).on("click", "[data-form-submit]", function(){
            var target_form     = $(this).attr("data-form-submit");

            if($(target_form).length != 0){
                $(target_form).submit();
            }else{
                throw "Unable to find target form";
            }
        });
        
        $.each($("[data-ajax-form-hook-submit]"), function(index, obj){

            var object              = $(this);
            var hook_selector       = $(this).attr("data-ajax-form-hook-submit");

            $(document).bind("ajax_form_submit_success", function(ev, args){
                if($(args.form).closest(hook_selector).length != 0){
                    $this.send_ajax(object);
                }
            });

        });
        

        $(document).on("click", selector + " [type='submit']", function(e){
            e.preventDefault();
            
            console.log(selector + " [type='submit']");
            
            $this.send_ajax_form($(this).closest(selector), 'submit', this);

            return false;
        });

        $(document).on("change", selector + " .yps-ajax-form-hook", function(e){
            console.log("BBBB");

            e.preventDefault();
            $this.send_ajax_form($(this).closest(selector), 'change', this);

            return false;
        });
    }

    send_ajax(object){
        var ajax_data_url     = this.$(object).attr('data-ajax-url');
        
        this.$(object).css('opacity', this.submit_form_opacity);
        
        this.$.ajax({
            type:'POST',
            url: ajax_data_url,
            data: {

            },
            dataType: "json",

            success:function(data){
                $(object).html(data.html);
                $(object).css('opacity', '1.0');
            },

            error: function(data){
                console.log(data);
            }
        });
    }
    
    send_ajax_form(form, action, element){

        var $                 = this.$;
        var ajax_data_url     = this.$(form).attr('data-ajax-url');

        if(ajax_data_url !== undefined){
            this.$(form).css('opacity', this.submit_form_opacity);
            this.$(form).find(".alert").hide();

            this.$.ajax({
                type:'POST',
                url: ajax_data_url,
                data: {
                    form_data                   : this.$(form).find("*").serializeJSON(),
                    ajax_action                 : action,
                    ajax_action_type            : this.$(element).val(),
                    ajax_action_attributes      : this.$(element).data(),
                },
                dataType: "json",

                success:function(data){
                    $(form).html(data.html);
                    $(form).css('opacity', '1.0');

                    $(document).trigger('ajax_form_' + action + '_success', {
                        'form'          : form,
                        'element'       : element,
                        'data'          : data,
                    });
                },

                error: function(data){
                    alert(data.responseText);

                    console.log(data);
                }
            });
        }else{
            alert("Can't find data-ajax-url!");
        }

    }

    set_submit_form_opacity(opacity){
        this.submit_form_opacity  = opacity;
    }
    
}

jQuery(document).ready(function($){
    new YPS_Framework.Ajax_Form($, ".yps-ajax-form");
});