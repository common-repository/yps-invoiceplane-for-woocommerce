YPS_Framework.Form.DateTime_Form_Field = class{

    constructor($){

        $.each($('.yps-datetime-form-field'), function(index, el){

            $(el).xdsoft_datetimepicker({
                datepicker: $(el).data('date-picker'),
                timepicker: $(el).data('time-picker'),
                format: $(el).data('format'),
                lazyInit: true,
                validateOnBlur: true,
                allowBlank: true,
                scrollInput: false,
                closeOnDateSelect: true,
            });

        });

    }

};


    
jQuery(document).ready(function($){
    new YPS_Framework.Form.DateTime_Form_Field($);
});
