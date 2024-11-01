YPS_Framework.Form.Numeric_Form_Field        = class {

    element     = null;

    constructor(element){

        this.element        = element;
    }

    load(){

        var $this       = this;

        var decimals    = $($this.element).data('decimals');

        if(decimals == 0){
            var decimal_separator       = false;
        }else{
            var decimal_separator       = $($this.element).data('decimal_separator');
        }

        $(this.element).numeric({
            decimalPlaces:  decimals,
            decimal:        decimal_separator,
        });
    }

}

jQuery(document).ready(function($){

    $.each($(".yps-numeric-form-field"), function(index, element){
        var numeric_form_field = new YPS_Framework.Form.Numeric_Form_Field(element);

        numeric_form_field.load();
    });
});